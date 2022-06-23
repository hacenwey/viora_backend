@extends('backend.layouts.master')

@section('title',settings()->get('app_name').' | '. trans('global.edit').' '. trans('cruds.pointsConfig.title_singular'))

@section('main-content')

<div class="card">
    <h5 class="card-header">@lang('global.edit') ( {{$point_config->title}} ) </h5>
    <div class="card-body">
      <form method="post" class="row" action="{{route('backend.pointsConfig.update',$point_config->id)}}">
        @csrf
        @method('PATCH')

        <div class="col-md-6">
            <div class="form-group">
                <label for="inputVal" class="col-form-label">@lang('cruds.pointsConfig.fields.value') <span class="text-danger">*</span></label>
                <input id="inputVal" type="number" name="value" placeholder="@lang('global.enter') @lang('cruds.pointsConfig.fields.value')"  
                  value="{{$point_config->value}}" 
                  class="form-control"
                />
                @error('value')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>
        </div>
        <div class="form-group mb-3 col-md-12">
           <button class="btn btn-success" type="submit">@lang('global.update')</button>
        </div>
      </form>
    </div>
</div>
@endsection
