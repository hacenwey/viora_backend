@extends('backend.layouts.master')
@section('title',settings()->get('app_name').' | '. trans('global.edit') . trans('cruds.city.title_singular'))
@section('main-content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.city.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("backend.cities.update", [$city->id]) }}" enctype="multipart/form-data">
            {{csrf_field()}}
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.city.fields.name') }} <span class="text-danger">*</span></label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $city->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.city.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="name_ar">{{ trans('cruds.city.fields.name_ar') }} <span class="text-danger">*</span></label>
                <input class="form-control {{ $errors->has('name_ar') ? 'is-invalid' : '' }}" type="text" name="name_ar" id="name_ar" value="{{ old('name_ar', $city->name_ar) }}" required>
                @if($errors->has('name_ar'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name_ar') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.city.fields.name_ar_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-control">
                    <option value="">@lang('global.pleaseSelect')</option>
                    <option value="Enabled" {{ $city->status == "Enabled" ? 'selected' : '' }}>@lang('global.enabled')</option>
                    <option value="Disabled" {{ $city->status == "Disabled" ? 'selected' : '' }}>@lang('global.disabled')</option>
                </select>
            </div>
            <div class="form-group text-end">
                <button class="btn btn-primary" type="submit">
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
<script src="{{ asset('vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
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
