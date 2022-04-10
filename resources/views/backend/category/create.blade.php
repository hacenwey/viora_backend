@extends('backend.layouts.master')

@section('title',settings()->get('app_name').' | '. trans('global.new').' '. trans('cruds.category.title_singular'))

@section('main-content')

<div class="card">
    <h5 class="card-header">@lang('global.add') @lang('cruds.category.title_singular')</h5>
    <div class="card-body">
      <form method="post" class="row" action="{{route('backend.category.store')}}">
        {{csrf_field()}}
        <div class="colmd-6">
            <div class="form-group">
              <label for="inputTitle" class="col-form-label">@lang('cruds.category.fields.title') <span class="text-danger">*</span></label>
              <input id="inputTitle" type="text" name="title" placeholder="@lang('global.enter') @lang('cruds.category.fields.title')"  value="{{old('title')}}" class="form-control">
              @error('title')
              <span class="text-danger">{{$message}}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="summary" class="col-form-label">@lang('cruds.category.fields.summary')</label>
              <textarea class="form-control" id="summary" name="summary">{{old('summary')}}</textarea>
              @error('summary')
              <span class="text-danger">{{$message}}</span>
              @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="is_parent">@lang('cruds.category.fields.is_parent')</label><br>
                <input type="checkbox" name='is_parent' id='is_parent' value='1' checked> Yes
            </div>
            {{-- {{$categories}} --}}

            <div class="form-group d-none" id='parent_cat_div'>
            <label for="parent_id">@lang('cruds.category.fields.parent_category')</label>
            <select name="parent_id" class="form-control">
                <option value="">--@lang('global.select') @lang('cruds.category.fields.category')--</option>
                @foreach($categories as $key => $category)
                    <option value='{{$key}}'>{{$category}}</option>
                @endforeach
            </select>
            @error('parent_id') {{ $message }} @enderror
            </div>

            <div class="form-group">
            <label for="inputPhoto" class="col-form-label">@lang('cruds.category.fields.photo')</label>
            <div class="input-group">
                <span class="input-group-btn">
                    <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                    <i class="fa fa-picture-o"></i> @lang('global.choose')
                    </a>
                </span>
            <input id="thumbnail" class="form-control" type="text" name="photo" value="{{old('photo')}}">
            </div>
            <div id="holder" style="margin-top:15px;max-height:100px;"></div>

            @error('photo')
            <span class="text-danger">{{$message}}</span>
            @enderror
            </div>

            <div class="form-group">
            <label for="status" class="col-form-label">@lang('cruds.category.fields.status') <span class="text-danger">*</span></label>
            <select name="status" class="form-control">
                <option value="active">@lang('global.active')</option>
                <option value="inactive">@lang('global.inactive')</option>
            </select>
            @error('status')
            <span class="text-danger">{{$message}}</span>
            @enderror
            </div>
        </div>

        <div class="form-group col-md-12 mb-3">
          <button type="reset" class="btn btn-warning">@lang('global.reset')</button>
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
      $('#summary').summernote({
        placeholder: "{!! trans('global.write_short_desc') !!}",
          tabsize: 2,
          height: 120
      });
    });
</script>

<script>
  $('#is_parent').change(function(){
    var is_checked=$('#is_parent').prop('checked');
    // alert(is_checked);
    if(is_checked){
      $('#parent_cat_div').addClass('d-none');
      $('#parent_cat_div').val('');
    }
    else{
      $('#parent_cat_div').removeClass('d-none');
    }
  })
</script>
@endpush
