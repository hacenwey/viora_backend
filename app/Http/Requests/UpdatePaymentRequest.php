<?php

namespace App\Http\Requests;

use App\Models\Payment;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdatePaymentRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('payment_edit') || Gate::allows('edit_payments');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
        ];
    }
}
