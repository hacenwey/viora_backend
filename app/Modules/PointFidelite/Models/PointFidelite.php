<?php

namespace App\Modules\PointFidelite\Models;

use App\Models\User;
use App\Modules\PointFidelite\Enums\eCreatedVia;
use App\Modules\PointFidelite\Enums\eKeyPointConfig;
use App\Modules\PointFidelite\Enums\eTypePointHistory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PointFidelite extends Model
{
    protected $table = 'point_fidelite';
    protected $fillable = [
        'id', 'user_id', 'solde','expired_at'
    ];

    public static function addPointsTypeIn(User $user , $point , $created_via=eCreatedVia::FO, $created_by=null ){
        if($point <= 0){
            return null;
        }

        $delay_time = PointsConfig::firstWhere('key', eKeyPointConfig::POINTS_DELAY_TIME)
                                    ->value;

        if(!$user->pointFidelite){
            $pointFidelite = self::create(
                [
                    "user_id" => $user->id,
                    "solde" => $point,
                    "expired_at" => Carbon::now()->addDays($delay_time)
                ]
            );

            // add to history points
            self::addPointsToHistory(
                [
                    "user_id" => $user->id,
                    "type" => eTypePointHistory::IN,
                    "prev_solde" => 0,
                    "point" => $point,
                    "solde" => $pointFidelite->solde,
                    "created_via" => $created_via,
                    "created_by" => $created_by,
                    "expired_at" => $pointFidelite->expired_at
                ]
            );
        }else{
            $pointFidelite = $user->pointFidelite;
            if(Carbon::now()->gt($pointFidelite->expired_at)){
                // point fidelite is expired ( reset points !!)
                $pointFidelite = self::cancelExpiredPoints($user->id , $created_via , $created_by);
            }
            $pointFidelite->solde += $point;
            $pointFidelite->expired_at = self::updateExpiredDate($pointFidelite);
            $pointFidelite->save();

            // add to history points
            self::addPointsToHistory(
                [
                    "user_id" => $user->id,
                    "type" => eTypePointHistory::IN,
                    "prev_solde" => $pointFidelite->solde - $point,
                    "point" => $point,
                    "solde" => $pointFidelite->solde,
                    "created_via" => $created_via,
                    "created_by" => $created_by,
                    "expired_at" => $pointFidelite->expired_at
                ]
            );
        }

        return $pointFidelite;
    }

    public static function addPointsTypeOut(User $user , $debitPoint){
        $pointFidelite = $user->pointFidelite;
        
        // check if points can be debited
        $pointsCanbeDebited = self::validPointsFidelite($pointFidelite , $debitPoint);
        if(!$pointsCanbeDebited['status']){
            return $pointsCanbeDebited;
        }
        
        // update points
        $pointFidelite->solde -= $debitPoint;
        $pointFidelite->save();

        // add to history points
        self::addPointsToHistory(
            [
                "user_id" => $user->id,
                "type" => eTypePointHistory::OUT,
                "prev_solde" => $pointFidelite->solde + $debitPoint,
                "point" => $debitPoint,
                "solde" => $pointFidelite->solde
            ]
        );

        return ["status" => true , "data"=> $pointFidelite];
    }

    public static function validPointsFidelite($pointFidelite , $debitPoint){
        $min_valid_points = PointsConfig::firstWhere('key', eKeyPointConfig::MIN_POINTS)->value;

        /* if points does not exits or expired or less than min valid points (configurable in BO) or not enough */
        if(!$pointFidelite){
            return ["status" => false , "message"=> "Point fidelite not found !"];
        }
        if(Carbon::now()->gt($pointFidelite->expired_at)){
            return ["status" => false , "message"=> "Point fidelite already expired !"];
        }
        if($pointFidelite->solde < $min_valid_points){
            return ["status" => false , "message"=> "Point fidelite less than min valid points {$min_valid_points} !"];
        }
        if(!isset($debitPoint) || $debitPoint < 0 || $debitPoint > $pointFidelite->solde){
            return ["status" => false , "message"=> "Point fidelite not enough !"];
        }

        return ["status" => true];
    }

    public static function updateExpiredDate($pointFidelite){
        $delay_time = PointsConfig::firstWhere('key', eKeyPointConfig::POINTS_DELAY_TIME)
                                    ->value;
        $now = Carbon::now();
        if($pointFidelite->expired_at){
            $diff = $pointFidelite->expired_at->diffInDays($now);
            if($diff < 0){
                // points fidelite is expired !!
                return $now->addDays($delay_time);
            }
            return $pointFidelite->expired_at->addDays((int)(($delay_time + $diff)/2));
        }
        return $now->addDays($delay_time);
    }

    public static function convertPointsToCurrency($point){
        $devise = PointsConfig::firstWhere('key', eKeyPointConfig::POINTS_COST)->value;
        return (int)($point / $devise);
    }

    public static function cancelExpiredPoints(User $user , $created_via, $created_by){
        if(!$user->pointFidelite){
            return null;
        }
        $prev_solde = $user->pointFidelite->solde;

        // reset points fidelite
        $pointFidelite = $user->pointFidelite;
        $pointFidelite->solde = 0;
        $pointFidelite->expired_at = null;
        $pointFidelite->save();

        // add to history points
        self::addPointsToHistory(
            [
                "user_id" => $user->id,
                "type" => eTypePointHistory::EXPIRED,
                "prev_solde" => $prev_solde,
                "point" => -$prev_solde,
                "solde" => 0,
                "created_via" => $created_via,
                "created_by" => $created_by,
            ]
        );

        return $pointFidelite;

    }

    public static function addPointsToHistory($data){
        PointsHistory::create(
            [
                "user_id" => $data["user_id"],
                "order_id" => $data["order_id"] ?? null,
                "type" => $data['type'],
                "prev_solde" => $data['prev_solde'],
                "point" => $data['point'],
                "solde" => $data['solde'],
                "created_via" => $data['created_via'] ?? eCreatedVia::FO,
                "created_by" => $data['created_by'] ?? null,
                "expired_at" => $data['expired_at'] ?? null
            ]
        );
    }

   
}
