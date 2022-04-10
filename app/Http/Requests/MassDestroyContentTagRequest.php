<?php

namespace App\Http\Requests;

use App\Models\ContentTag;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyContentTagRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('delete_content_tag'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:content_tags,id',
        ];
    }
}
