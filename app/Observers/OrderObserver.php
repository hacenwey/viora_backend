<?php

namespace App\Observers;

use PDF;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\File;
use App\Notifications\StatusNotification;

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

        $users = User::with(['permissions'])->whereHas('permissions', function($q) {
                    $q->where('title', 'can_receive_orders_notifications');
                })->get();

        $details = [
            'title' => trans('global.new_order_arrived'),
            'reference' => '#'.$order->reference,
            'actionURL' => route('backend.order.show', $order->id),
            'fas' => 'fa-file-alt'
        ];
        $pdf = PDF::loadview('backend.order.pdf', compact('order'));

        $path = public_path();
        $fileName =  'Facture #'.$order->reference . '.' . 'pdf' ;
        $pdf->save($path . '/' . $fileName);

        if($users->count() > 0){
            setMailConfig();
            Notification::send($users, new StatusNotification($details));
        }

        sendMessage(trans('global.order_placed_success').': #'.$order->reference.', '.trans('global.thankYouForUsingOurApplication').'.', $order->phone, 'sms');

        try {
            File::delete('Facture '.$details['reference'].".pdf");
        } catch (\Throwable $th) {

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
        if($order->isDirty('status')){
            $phoneNumber = $order->phone; // replace with your user's phone number field
            $message = "Your order #" . $order->reference . " has been updated to " . $order->status; // replace with your desired message
            //contact here api sms 
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
