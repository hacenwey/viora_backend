@extends('backend.layouts.master')
@section('title',settings()->get('app_name').' | '. trans('global.edit').' '. trans('cruds.brand.title_singular'))
@section('main-content')

<div class="card">
    <h5 class="card-header">@lang('global.edit') @lang('cruds.brand.title_singular')</h5>
    <div class="card-body">
      <form method="post" action="{{route('backend.brand.update',$brand->id)}}">
        @csrf
        @method('PATCH')
        <div class="form-group">
          <label for="inputTitle" class="col-form-label">@lang('cruds.brand.fields.title') <span class="text-danger">*</span></label>
        <input id="inputTitle" type="text" name="title" placeholder="@lang('global.enter') @lang('cruds.brand.fields.title')"  value="{{$brand->title}}" class="form-control">
        @error('title')
        <span class="text-danger">{{$message}}</span>
        @enderror
        </div>

        <div class="form-group">
            <label for="logo" class="col-form-label">@lang('cruds.brand.fields.logo') <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-btn">
                    <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                    <i class="fa fa-picture-o"></i> @lang('global.choose')
                    </a>
                </span>
              <input id="thumbnail" class="form-control" type="text" name="logo" value="{{$brand->logo}}">
            </div>
            <div id="holder" style="margin-top:15px;max-height:100px;"></div>
            @error('logo')
                <span class="text-danger">{{$message}}</span>
            @enderror
        </div>

        <div class="form-group">
          <label for="status" class="col-form-label">@lang('cruds.brand.fields.status') <span class="text-danger">*</span></label>
          <select name="status" class="form-control">
            <option value="active" {{(($brand->status=='active') ? 'selected' : '')}}>@lang('global.active')</option>
            <option value="inactive" {{(($brand->status=='inactive') ? 'selected' : '')}}>@lang('global.inactive')</option>
          </select>
          @error('status')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group mb-3">
           <button class="btn btn-success" type="submit">@lang('global.submit')</button>
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
      placeholder: "{!! trans('global.write_short_description') !!}",
        tabsize: 2,
        height: 150
    });
    });
</script>
@endpush
