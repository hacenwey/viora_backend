@extends('backend.layouts.master')
@section('title', settings()->get('app_name').' | '. trans('global.new').' '. trans('cruds.attribute.title_singular'))
@section('main-content')

<div class="card">
    <h5 class="card-header">@lang('global.add') @lang('cruds.attribute.title_singular')</h5>
    <div class="card-body">
      <form method="post" class="row" action="{{route('backend.attribute.store')}}">
        {{csrf_field()}}
        <div class="form-group col-md-4">
        <label for="inputTitle" class="col-form-label">@lang('cruds.attribute.fields.code') <span class="text-danger">*</span></label>
        <input id="inputTitle" type="text" name="code" placeholder="@lang('global.enter') @lang('cruds.attribute.fields.code')"  value="{{old('code')}}" class="form-control">
        @error('code')
        <span class="text-danger">{{$message}}</span>
        @enderror
        </div>

        <div class="form-group col-md-4">
            <label for="name" class="col-form-label">@lang('cruds.attribute.fields.name') <span class="text-danger">*</span></label>
            <input id="name" type="text" name="name" placeholder="@lang('global.enter') @lang('cruds.attribute.fields.name')"  value="{{old('name')}}" class="form-control">
            @error('name')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>

        <div class="form-group col-md-4">
            <label for="frontend_type" class="col-form-label">@lang('cruds.attribute.fields.frontend_type') <span class="text-danger">*</span></label>
            @php $types = ['select' => 'Select Box', 'radio' => 'Radio Button', 'text' => 'Text Field', 'text_area' => 'Text Area']; @endphp
            <select name="frontend_type" id="frontend_type" class="form-control">
                @foreach($types as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
            @error('frontend_type')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>

        <div class="form-group col-md-6">
          <label for="is_filterable">@lang('cruds.attribute.fields.filterable')</label><br>
          <input type="checkbox" id="is_filterable" name="is_filterable"/> @lang('global.yes')
          @error('is_filterable')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group col-md-6">
          <label for="is_required">@lang('cruds.attribute.fields.required')</label><br>
          <input type="checkbox" id="is_required" name="is_required"/> @lang('global.yes')
          @error('is_required')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group mb-3 col-md-12 justify-between">
            <button type="reset" class="btn btn-warning">@lang('global.reset')</button>
            <button class="btn btn-success" type="submit">@lang('global.submit')</button>
        </div>
      </form>
    </div>
</div>

@endsection

