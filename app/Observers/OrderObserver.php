<?php

namespace App\Observers;

use PDF;
use App\Models\Order;
use App\Models\SellerTransaction;

use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\File;
use App\Notifications\StatusNotification;
use Illuminate\Support\Facades\Http;
use Log;
use App\Services\SmsService;
use App\Jobs\SendSmsJob;
use App\Models\SellersOrder;
use App\Services\FirebaseNotificationService;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function created(Order $order)
    {
        if($order->payment_method == 'cod'){
        $message =  'طلبات اون لاين: شكرا على طلبكم '.$order->first_name.'.
الطلبية '.$order->reference.' قيد المعالجة.
سيتم توصيل طلبكم في أقل من 24 ساعة.

Talabate Online: Merci pour votre commande, '.$order->first_name.'.
Votre commande '.$order->reference.' est en cours de traitement.
Votre commande sera livrée en moins de 24h.';

        $payload = [
        'phone_numbers' => ['222'.$order->phone],
        'message' => preg_replace('/\. +/', ".\n", $message)
    ];

    try {
        SendSmsJob::dispatch($payload);
    } catch (\Exception $e) {
        Log::error('Error sending SMS: ' . $e->getMessage());
    }
}
    }

    /**
     * Handle the Order "updated" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {

        
        if($order->payment_method == 'bankily'){
            $message =  'طلبات اون لاين: شكرا على طلبكم '.$order->first_name.'.
    الطلبية '.$order->reference.' قيد المعالجة.
    سيتم توصيل طلبكم في أقل من 24 ساعة.

    Talabate Online: Merci pour votre commande, '.$order->first_name.'.
    Votre commande '.$order->reference.' est en cours de traitement.
    Votre commande sera livrée en moins de 24h.';

            $payload = [
            'phone_numbers' => ['222'.$order->phone],
            'message' => preg_replace('/\. +/', ".\n", $message)
        ];

        try {
            SendSmsJob::dispatch($payload);
        } catch (\Exception $e) {
            Log::error('Error sending SMS: ' . $e->getMessage());
        }
    }

    if ($order->isDirty('status')) {
             $sellersOrders = SellersOrder::with('sellersOrderProducts')->where('order_id',$order->id)->where('status','!=','delivered')->get();
             foreach($sellersOrders as $sellersOrder){

                if($sellersOrder){
                    $sellersOrder->update(['status'=> $order->status]);
                 }

                   $totalGain = $sellersOrder->sellersOrderProducts->sum('gain');
                    if ($order->status == 'delivered') {
                    SellerTransaction::create([
                        'solde' =>  $totalGain,
                        'seller_id'=> $sellersOrder->seller_id,
                        'order_id' => $order->id,
                        'type' => 'IN',

                    ]);


                    $user = User::find($sellersOrder->seller_id);
                    $messageToBeSend = "تم تسليم طلبك بنجاح، وتمت إضافة ".$totalGain." MRU إلى رصيد البائع الخاص بك.
                    La livraison de votre commande s'est déroulée avec succès, et votre solde vendeur a été crédité de ".$totalGain." MRU.";

                    FirebaseNotificationService::sendNotificationOrder($user->fcm_token,$messageToBeSend);
                 }


                }

    }


    

            if(!is_null($order->skiped_items)){
                $skippedItemsString = implode(', ', $order->skiped_items);

                $message = 'طلبات اون لاين: عذرًا، إحدى أو أكثر من العناصر غير متاحة في طلبك. ' . $skippedItemsString . ' 
                 Talabate Online: Désolé, un ou plusieurs articles ne sont pas disponibles dans votre commande. ' . $skippedItemsString;
                
                $payload = [
                    'phone_numbers' => ['222' . $order->phone],
                    'message' => preg_replace('/\. +/', ".\n", $message),
                ];
                
                try {
                    SendSmsJob::dispatch($payload);
                } catch (\Exception $e) {
                    \Log::error('Error sending SMS: ' . $e->getMessage());
                }
            }



    }

    /**
     * Handle the Order "deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function deleted(Order $order)
    {
    }

    /**
     * Handle the Order "restored" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }
}
