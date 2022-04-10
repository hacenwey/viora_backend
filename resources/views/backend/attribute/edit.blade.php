@extends('backend.layouts.master')
@section('title', settings()->get('app_name').' | '. trans('global.edit').' '. trans('cruds.product.title_singular'))
@section('main-content')
<div class="row">
    <div class="col-md-3">
        <ul class="nav flex-column" id="attributeTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" href="#general" id="general-tab" data-toggle="tab" role="tab" aria-controls="general" aria-selected="true">@lang('global.general')</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="#values" id="values-tab" data-toggle="tab" role="tab" aria-controls="values" aria-selected="true">@lang('global.attribute_values')</a>
            </li>
        </ul>
    </div>
    <div class="col-md-9">
        <div class="tab-content">
            <div class="tab-pane active" id="general" role="tabpanel" aria-labelledby="general-tab">
                <div class="card">
                    <h5 class="card-header">{{ trans('global.edit') }} {{ trans('cruds.product.title_singular') }}</h5>
                    <div class="card-body">
                      <form method="post" class="row" action="{{route('backend.attribute.update', ['attribute' => $attribute->id])}}">
                        @csrf
                        @method('PATCH')
                        <div class="form-group col-md-4">
                            <label for="inputTitle" class="col-form-label">@lang('cruds.attribute.fields.code') <span class="text-danger">*</span></label>
                            <input id="inputTitle" type="text" name="code" placeholder="@lang('global.enter') @lang('cruds.attribute.fields.code')"  value="{{old('code', $attribute->code)}}" class="form-control">
                                @error('code')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="name" class="col-form-label">@lang('cruds.attribute.fields.name') <span class="text-danger">*</span></label>
                                <input id="name" type="text" name="name" placeholder="@lang('global.enter') @lang('cruds.attribute.fields.name')"  value="{{old('name', $attribute->name)}}" class="form-control">
                                @error('name')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="frontend_type" class="col-form-label">@lang('cruds.attribute.fields.frontend_type') <span class="text-danger">*</span></label>
                                @php $types = ['select' => 'Select Box', 'radio' => 'Radio Button', 'text' => 'Text Field', 'text_area' => 'Text Area']; @endphp
                                <select name="frontend_type" id="frontend_type" class="form-control">
                                    @foreach($types as $key => $label)
                                        <option value="{{ $key }}" {{ $attribute->frontend_type == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('frontend_type')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                              <label for="is_filterable">@lang('cruds.attribute.fields.filterable')</label><br>
                              <input type="checkbox" id="is_filterable" name="is_filterable" {{ $attribute->is_filterable == 1 ? 'checked' : '' }}/> Yes
                              @error('is_filterable')
                              <span class="text-danger">{{$message}}</span>
                              @enderror
                            </div>
                            <div class="form-group col-md-6">
                              <label for="is_required">@lang('cruds.attribute.fields.required')</label><br>
                              <input type="checkbox" id="is_required" name="is_required" {{ $attribute->is_required == 1 ? 'checked' : '' }}/> Yes
                              @error('is_required')
                              <span class="text-danger">{{$message}}</span>
                              @enderror
                            </div>
                            <div class="form-group mb-3 col-md-12 text-right">
                                <button class="btn btn-success" type="submit">@lang('global.update')</button>
                            </div>
                      </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="values" role="tabpanel" aria-labelledby="values-tab">
                <attribute-values :attributeid="{{ $attribute->id }}"></attribute-values>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>

</script>
@endpush
