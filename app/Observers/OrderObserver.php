<?php

namespace App\Observers;

use PDF;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\File;
use App\Notifications\StatusNotification;
use Illuminate\Support\Facades\Http;
use Log;
use App\Services\SmsService;
use App\Jobs\SendSmsJob;
use App\Models\SellersOrder;

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
             $sellersOrder = SellersOrder::where('order_id',$order->id)->first();
             if($sellersOrder){
                $sellersOrder->update(['status'=> $order->status]);
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
