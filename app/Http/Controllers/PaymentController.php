<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\MassDestroyPaymentRequest;
use Illuminate\Support\Facades\Validator;
use Exception;
use Log;
use App\Services\BankilyService;
use App\Models\Transaction;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('access_payments'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $payments = Transaction::orderBy('id', 'DESC')->paginate(10);

        return view('backend.payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('add_payments'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('backend.payments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePaymentRequest $request)
    {
        $data = $request->all();
        if($request->has_api){
            $validator = Validator::make($request->all(), [
                'api_key' => 'required'
            ]);
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            $data['has_api'] = 1;
        }

        $payment = Payment::create($data);

        return redirect()->route('backend.payments.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tenant\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        abort_if(Gate::denies('view_payments'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('backend.payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tenant\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        abort_if(Gate::denies('edit_payments'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('backend.payments.edit', compact('payment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tenant\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $data = $request->all();
        if($request->has_api){
            $validator = Validator::make($request->all(), [
                'api_key' => 'required'
            ]);
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            $data['has_api'] = 1;
        }
        $payment->update($data);

        return redirect()->route('backend.payments.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tenant\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        abort_if(Gate::denies('delete_payments'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $payment->delete();

        return back();
    }

    public function massDestroy(MassDestroyPaymentRequest $request)
    {
        Payment::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function processPayment(Request $request){

        $validator = Validator::make($request->all() , [
            'clientPhone' => 'required|max:8',
            'passcode' => 'required',
            'operationId' => 'required',
            'amount' => 'required' ,
            'language' => 'required',
            'order_id' => 'required'
        ]);

        if($validator->fails()) {
            return response(['errors' => $validator->messages(), 'message' => 'Une donnée transmise n\'est pas conforme'], 400);
        }
        return BankilyService::processPayment($validator->validated());

    }


    public function checkTransaction(Request $request){

        $validator = Validator::make($request->all() , [
            'operationId' => 'required|string',
        ]);

        if($validator->fails()) {
            return response(['errors' => $validator->messages(), 'message' => 'Une donnée transmise n\'est pas conforme'], 400);
        }
        return BankilyService::checkTransaction($validator->validated());

    }
}
