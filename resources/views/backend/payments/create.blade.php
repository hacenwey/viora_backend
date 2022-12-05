@extends('backend.layouts.master')
@section('title',settings()->get('app_name').' | '. trans('global.new') . trans('cruds.payment.title_singular'))
@section('main-content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.payment.title_singular') }}
    </div>

    <div class="card-body">
        <form class="row" method="POST" action="{{ route("backend.payments.store") }}" enctype="multipart/form-data">
            @csrf
            @csrf
            <div class="form-group col-md-4">
                <label class="required" for="name">{{ trans('cruds.payment.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.payment.fields.name_helper') }}</span>
            </div>
            <div class="form-group col-md-4">
                <label class="required" for="has_api">{{ trans('cruds.payment.fields.has_api') }} (<span class="help-block">{{ trans('cruds.payment.fields.has_api_helper') }}</span>)</label><br>
                <input class="form-control {{ $errors->has('has_api') ? 'is-invalid' : '' }}" type="checkbox" name="has_api" id="has_api" @if(old('has_api')) checked @endif >
                @if($errors->has('has_api'))
                <br>
                    <div class="invalid-feedback">
                        {{ $errors->first('has_api') }}
                    </div>
                @endif
            </div>
            <div class="form-group col-md-4">
                <label class="required" for="api_key">{{ trans('cruds.payment.fields.api_key') }}</label>
                <input class="form-control {{ $errors->has('api_key') ? 'is-invalid' : '' }}" type="text" name="api_key" id="api_key" value="{{ old('api_key', '') }}">
                @if($errors->has('api_key'))
                    <div class="invalid-feedback">
                        {{ $errors->first('api_key') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.payment.fields.api_key_helper') }}</span>
            </div>
            <div class="form-group col-md-12">
                <label class="required" for="description">{{ trans('cruds.payment.fields.description') }}</label>
                <textarea class="form-control" id="description" name="description">{{old('description')}}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.payment.fields.name_helper') }}</span>
            </div>
            <div class="form-group col-md-12">
                <label for="image" class="col-form-label">Image <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-btn">
                        <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                        <i class="fa fa-picture-o"></i> Choose
                        </a>
                    </span>
                  <input id="thumbnail" class="form-control" type="text" name="image" value="{{old('image')}}">
                </div>
                <div id="holder" style="margin-top:15px;max-height:100px;"></div>
                @error('logo')
                    <span class="text-danger">{{$message}}</span>
                @enderror
            </div>
            <div class="form-group col-md-12">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{asset('backend/summernote/summernote.min.css')}}">
@endpush
@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="{{asset('backend/summernote/summernote.min.js')}}"></script>
<script>
    $('#lfm').filemanager('file');

    $(document).ready(function() {
    $('#description').summernote({
      placeholder: "{!! trans('global.write_short_desc') !!}",
        tabsize: 2,
        height: 150
    });
    });
</script>
@endpush
