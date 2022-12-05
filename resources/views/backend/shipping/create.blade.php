@extends('backend.layouts.master')

@section('title',settings()->get('app_name').' | '. trans('global.new') . trans('cruds.shipping.title_singular'))

@section('main-content')

<div class="card">
    <h5 class="card-header">@lang('global.add') @lang('cruds.shipping.title_singular')</h5>
    <div class="card-body">
      <form method="post" action="{{route('backend.shipping.store')}}">
        @csrf
        <div class="form-group">
          <label for="inputTitle" class="col-form-label">@lang('cruds.shipping.fields.type') <span class="text-danger">*</span></label>
        <input id="inputTitle" type="text" name="type" placeholder="@lang('global.enter') @lang('cruds.shipping.fields.type')"  value="{{old('type')}}" class="form-control">
        @error('type')
            <span class="text-danger">{{$message}}</span>
        @enderror
        </div>

        <div class="form-group">
          <label for="price" class="col-form-label">@lang('cruds.shipping.fields.price') <span class="text-danger">*</span></label>
        <input id="price" type="number" name="price" placeholder="@lang('global.enter') @lang('cruds.shipping.fields.price')"  value="{{old('price')}}" class="form-control">
        @error('price')
        <span class="text-danger">{{$message}}</span>
        @enderror
        </div>

        <div class="form-group">
          <label for="free_price" class="col-form-label">@lang('cruds.shipping.fields.free_price') <span class="text-danger">*</span></label>
        <input id="free_price" type="number" name="free_price" placeholder="@lang('global.enter') @lang('cruds.shipping.fields.free_price')"  value="{{old('free_price')}}" class="form-control">
        @error('free_price')
        <span class="text-danger">{{$message}}</span>
        @enderror
        </div>

        <div class="form-group">
          <label for="urgent_price" class="col-form-label">@lang('cruds.shipping.fields.urgent_price') <span class="text-danger">*</span></label>
        <input id="urgent_price" type="number" name="urgent_price" placeholder="@lang('global.enter') @lang('cruds.shipping.fields.urgent_price')"  value="{{old('urgent_price')}}" class="form-control">
        @error('urgent_price')
        <span class="text-danger">{{$message}}</span>
        @enderror
        </div>

        <div class="form-group">
          <label for="status" class="col-form-label">@lang('cruds.shipping.fields.price') <span class="text-danger">*</span></label>
          <select name="status" class="form-control">
              <option value="active">@lang('global.active')</option>
              <option value="inactive">@lang('global.inactive')</option>
          </select>
          @error('status')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group mb-3">
          <button type="reset" class="btn btn-warning">@lang('global.reset')</button>
           <button class="btn btn-success" type="submit">@lang("global.submit")</button>
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
    $('#lfm').filemanager('image');

    $(document).ready(function() {
    $('#description').summernote({
      placeholder: "{!! trans('global.write_short_desc') !!}",
        tabsize: 2,
        height: 150
    });
    });
</script>
@endpush
