<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProvinceResource;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $provinces = Province::all();
        $emptyProvince = $provinces->count() === 0;

        $response = [
            'message' => !$emptyProvince? 'la Liste des provinces a été recupées avec succès' : 'La liste de province est vide',
            'data' => !$emptyProvince ? ProvinceResource::collection($provinces) : []
        ];

        return response($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'state_id' => '',

        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->messages(), 'message' => 'Une donnée transmise n\'est pas conforme'], 400);
        }

        try {
            $province= Province::create([
                'name' => $request->name,
                'state_id' => $request->state_id

            ]);
        } catch (Exception $ex) {
            Log::info("Problem lors de la creation d'un province: " . json_encode($validator->validated()));
            Log::error($ex->getMessage());

            return response(['message' => 'Un problème est survenu lors de la création du province.'], 500);
        }

        $response = [
            'message' => 'province a été créé avec succèss',
            'object' => new  ProvinceResource($province)
        ];

        return response($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $province = Province::find($id);

        $response = [
            'message' =>  $province ? 'province existante' : 'Aucun province n\'est associé avec cet identifiant.',
            'data' =>  $province ? new ProvinceResource($province) : null
        ];

        return response($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'state_id' => '',

        ]);


        if ($validator->fails()) {
            return response(['errors' => $validator->messages(), 'message' => 'Une donnée transmise n\'est pas conforme'], 400);
        }

        try {
            $province = Province::find($id);
            if ($province) {
                $province->update($validator->validated());
            } else {
                return response(['message' => 'province que vous essayé de modifier n\'existe pas'], 400);
            }
        } catch (Exception $ex) {
            Log::info("Problem lors de la lors du mise à jour d'un province: " . json_encode($validator->validated()));
            Log::error($ex->getMessage());

            return response(['message' => 'Un problème est survenu lors du mise à jour de un province.'], 500);
        }


        $response = [
            'message' => 'province a été modifié avec succèss',
            'object' => new  ProvinceResource($province)
        ];

        return response($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $province = Province::find($id);
        $name = '';
        try {
            if ($province) {
                $name =  $province->name;
                $province->delete();
            } else {
                return response(['message' => 'Province que vous essayé de suppriemr n\'existe pas'], 400);
            }
        } catch (Exception $ex) {
            Log::info("Problem lors de la supression de province  avec l'id: " . $id);
            Log::error($ex->getMessage());

            return response(['message' => 'Un problème est survenu lors de la suppression du province.'], 500);
        }

        $response = [
            'message' => "province $name à été supprimée avec succèss",
            'data' =>   null
        ];

        return response($response);
    }
}
