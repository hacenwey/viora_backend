@extends('backend.layouts.master')

@section('title',settings()->get('app_name').' | '. trans('global.edit').' '. trans('cruds.category.title_singular'))

@section('main-content')

<div class="card">
    <h5 class="card-header">@lang('global.edit') @lang('cruds.category.title_singular')</h5>
    <div class="card-body">
      <form method="post" class="row" action="{{route('backend.category.update',$category->id)}}">
        {{csrf_field()}}
        @method('PATCH')
        <div class="col-md-6">
            <div class="form-group">
                <label for="inputTitle" class="col-form-label">@lang('cruds.category.fields.title') <span class="text-danger">*</span></label>
                <input id="inputTitle" type="text" name="title" placeholder="@lang('global.enter') @lang('cruds.category.fields.title')"  value="{{$category->title}}" class="form-control">
                @error('title')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <div class="form-group">
            <label for="summary" class="col-form-label">@lang('cruds.category.fields.summary')</label>
            <textarea class="form-control" id="summary" name="summary">{{$category->summary}}</textarea>
            @error('summary')
            <span class="text-danger">{{$message}}</span>
            @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="is_parent">@lang('cruds.category.fields.is_parent')</label><br>
                <input type="checkbox" name='is_parent' id='is_parent' value='{{$category->is_parent}}' {{(($category->is_parent==1)? 'checked' : '')}}> Yes
              </div>
              {{-- {{$categories}} --}}
              {{-- {{$category}} --}}

            <div class="form-group {{(($category->is_parent==1) ? 'd-none' : '')}}" id='parent_cat_div'>
                <label for="parent_id">@lang('cruds.category.fields.parent_category')</label>
                <select name="parent_id" class="form-control">
                    <option value="">--@lang('global.select') @lang('cruds.category.title_singular')--</option>
                    @foreach($categories as  $parent_cat)

                        <option value='{{$parent_cat->id}}' {{(($parent_cat->id== $category->parent_id) ? 'selected' : '')}}>{{$parent_cat->title}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
            <label for="inputPhoto" class="col-form-label">@lang('cruds.category.fields.photo')</label>
            <div class="input-group">
                <span class="input-group-btn">
                    <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                    <i class="fa fa-picture-o"></i> @lang('global.choose')
                    </a>
                </span>
            <input id="thumbnail" class="form-control" type="text" name="photo" value="{{$category->photo}}">
            </div>
            <div id="holder" style="margin-top:15px;max-height:100px;"></div>
            @error('photo')
            <span class="text-danger">{{$message}}</span>
            @enderror
            </div>

            <div class="form-group">
            <label for="status" class="col-form-label">@lang('cruds.category.fields.status') <span class="text-danger">*</span></label>
            <select name="status" class="form-control">
                <option value="active" {{(($category->status=='active')? 'selected' : '')}}>@lang('global.active')</option>
                <option value="inactive" {{(($category->status=='inactive')? 'selected' : '')}}>@lang('global.inactive')</option>
            </select>
            @error('status')
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
        height: 150
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
