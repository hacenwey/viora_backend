@extends('backend.layouts.master')

@section('title',settings()->get('app_name').' | '. trans('global.edit') . trans('cruds.category.title_singular'))

@section('main-content')

<div class="card">
    <h5 class="card-header">@lang('global.edit') @lang('cruds.category.title_singular')</h5>
    <div class="card-body">
      <form method="post" action="{{route('backend.post-category.update',$postCategory->id)}}">
        @csrf
        @method('PATCH')
        <div class="form-group">
          <label for="inputTitle" class="col-form-label">@lang('cruds.category.fields.title')</label>
          <input id="inputTitle" type="text" name="title" placeholder="@lang('global.select') @lang('cruds.category.fields.title')"  value="{{$postCategory->title}}" class="form-control">
          @error('title')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="status" class="col-form-label">@lang('cruds.category.fields.status')</label>
          <select name="status" class="form-control">
            <option value="active" {{(($postCategory->status=='active') ? 'selected' : '')}}>@lang('global.active')</option>
            <option value="inactive" {{(($postCategory->status=='inactive') ? 'selected' : '')}}>@lang('global.inactive')</option>
          </select>
          @error('status')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group mb-3">
           <button class="btn btn-success" type="submit">@lang('global.update')</button>
        </div>
      </form>
    </div>
</div>

@endsection
