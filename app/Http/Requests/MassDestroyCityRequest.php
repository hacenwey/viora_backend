<?php

namespace App\Http\Requests;

use App\Models\City;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyCityRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('city_delete') || Gate::denies('delete_cities'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:cities,id',
        ];
    }
}
