<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPaymentRequest;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Transaction;
use App\Services\BankilyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

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
        if ($request->has_api) {
            $validator = Validator::make($request->all(), [
                'api_key' => 'required',
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
        if ($request->has_api) {
            $validator = Validator::make($request->all(), [
                'api_key' => 'required',
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

    public function processPayment(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'clientPhone' => 'required|max:8',
            'passcode' => 'required',
            'amount' => 'required',
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->messages(), 'message' => 'Une donnée transmise n\'est pas conforme'], 400);
        }
        return BankilyService::processPayment($validator->validated());

    }

    public function checkTransaction(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'operationId' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->messages(), 'message' => 'Une donnée transmise n\'est pas conforme'], 400);
        }
        return BankilyService::checkTransaction($validator->validated());

    }

    public function walletInquiryAndGenerateOtp(Request $request)
    {
        $validationRules = [
            'MobileNumber' => 'required',
            'Amount' => 'required',
            'Language' => 'required',
            'order_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->messages(),
                'message' => 'Une donnée transmise n\'est pas conforme',
            ], 400);
        }

        try {
            Log::info("======== START WALLET INQUIRY =======");
            $payload = $validator->validated();
            $payload["MerchantCode"] = config('constants.EMWALI_MARCHANT_CODE');
            $payload["ApiKey"] = config('constants.EMWALI_API_KEY');
            Log::info('Validated Request Payload: ' . json_encode($payload));
            $responseWalletInquiry = Http::withHeaders(['Content-Type' => 'application/json'])
                ->withOptions(['verify' => false])
                ->withBody(json_encode($payload), 'application/json')
                ->get(config('constants.EMWALI_BASE_URL') . 'walletInquiry');

            $responseWalletInquiryDecode = $responseWalletInquiry->body();
        } catch (\Exception $e) {
            Log::error("Error in payment transaction: {$e->getMessage()}");
            Log::info('Validated Request Payload: ' . json_encode($payload));
            return response([
                'message' => 'An error occurred while processing the payment.',
                'data' => null,
            ], 500);
        }

        $data = json_decode($responseWalletInquiryDecode);




      
        if ($data !== null && $data->result == 0) {
            Log::info("======== START GENERATE OTP =======");
            try {
                $merchantReference = 'EM-' . strtoupper(uniqid());
                $responseGenerateOtpPayload = [
                    "MobileNumber" => $request->MobileNumber,
                    "WalletNumber" => $data->accountNumber,
                    "MerchantReference" => $merchantReference,
                    "Language" => $request->Language,
                    "Amount" => $request->Amount,
                    "MerchantCode" => config('constants.EMWALI_MARCHANT_CODE'),
                    "ApiKey" => config('constants.EMWALI_API_KEY'),
                ];

                $responseGenerateOTP = Http::withHeaders(['Content-Type' => 'application/json'])
                    ->withOptions(['verify' => false])
                    ->post(config('constants.EMWALI_BASE_URL') . 'generateOtp', $responseGenerateOtpPayload);
                $response = json_decode($responseGenerateOTP->body());
                $response->MerchantReference = $merchantReference;
                $response->WalletNumber = $data->accountNumber;

            } catch (\Exception $e) {
                Log::error("======== ERROR IN GENERATE OTP =======");
                Log::error("Error in payment transaction: {$e->getMessage()}");
                return response([
                    'message' => 'An error occurred while processing the payment.',
                    'data' => null,
                ], 500);
            }
            try {
                $transaction = new Transaction([
                    'clientPhone' => $request->MobileNumber,
                    'merchant_reference' => $merchantReference,
                    'amount' => $request->Amount,
                    'order_id' => $request->order_id,
                ]);

                $transaction->save();

            } catch (\Exception $e) {
                Log::error("Error in save transaction: {$e->getMessage()}");

            }
        
            return $response;
        } else {
            Log::error("======== ERROR IN WALLET INQUIRY =======");
            Log::error("response  walletInquiry endpoints: " . var_export($responseWalletInquiryDecode, true));
            return response([
                'message' => 'An error occurred while processing the payment.',
            ], $responseWalletInquiry->status());
        }
    }

    public function Pay(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'MobileNumber' => 'required',
            'WalletNumber' => 'required',
            "MerchantReference" => 'required',
            'Amount' => 'required',
            'OTP' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->messages(), 'message' => 'Une donnée transmise n\'est pas conforme'], 400);
        }
        try {
            Log::info("======== START PAY PROCCESS =======");
            $PayPayload = [
                "MobileNumber" => $request->MobileNumber,
                "WalletNumber" => $request->WalletNumber,
                "Amount" => $request->Amount,
                "OTP" => $request->OTP,
                "MerchantReference" => $request->MerchantReference,
                "MerchantCode" => config('constants.EMWALI_MARCHANT_CODE'),
                "MerchantChannelCode" => config('constants.EWMALI_MARCHANT_CHANNEL_CODE'),
                "ApiKey" => config('constants.EMWALI_API_KEY'),
            ];

            $requestHttp = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->withOptions([
                'verify' => false,
            ])->post(config('constants.EMWALI_BASE_URL') . 'pay', $PayPayload);

            $response = json_decode($requestHttp->body());

        } catch (\Exception $e) {
            Log::error("======== ERROR IN PAY PROCCESS =======");
            Log::error("Error in payment transaction: {$e->getMessage()}");
            Log::info('Validated Request Payload: ' . json_encode($PayPayload));
            return response([
                'message' => 'An error occurred while processing the payment.',
                'data' => null,
            ], 500);
        }

        if ($response->result === 0) {
            try {

                $transaction = new Transaction([
                    'clientPhone' => $request->MobileNumber,
                    'merchant_reference' => $merchantReference,
                    'amount' => $request->Amount,
                    'transactionId' => $response->paymentRefrence,
                    'order_id' => $request->order_id,
                ]);

                Order::find($request->order_id)
                    ->update(['payment_status' => 'paid', 'payment_method' => 'emwali']);

            } catch (\Exception $e) {
                Log::error("Error in save transaction: {$e->getMessage()}");
            }

            return $response;
        } else {
            Log::error("======== ERROR IN PAY PROCCESS =======");
            Log::error("response  pay endpoints: " . var_export($response, true));
            return response([
                'message' => 'An error occurred while processing the payment.',
            ], $requestHttp->status());

        }

    }
}
