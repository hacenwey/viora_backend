@extends('backend.layouts.master')

@section('title',settings()->get('app_name').' | '. trans('global.edit').' '. trans('cruds.comment.title_singular'))

@section('main-content')
<div class="card">
  <h5 class="card-header">@lang('cruds.comment.title_singular') @lang('global.edit')</h5>
  <div class="card-body">
    <form action="{{route('backend.comment.update',$comment->id)}}" method="POST">
      @csrf
      @method('PATCH')
      <div class="form-group">
        <label for="name">@lang('global.by'):</label>
        <input type="text" disabled class="form-control" value="{{$comment->user_info->name}}">
      </div>
      <div class="form-group">
        <label for="comment">@lang('cruds.comment.title_singular')</label>
      <textarea name="comment" id="" cols="20" rows="10" class="form-control">{{$comment->comment}}</textarea>
      </div>
      <div class="form-group">
        <label for="status">@lang('cruds.comments.fields.status') :</label>
        <select name="status" id="" class="form-control">
          <option value="">--@lang('global.select') @lang('cruds.comments.fields.status')--</option>
          <option value="active" {{(($comment->status=='active')? 'selected' : '')}}>@lang('global.active')</option>
          <option value="inactive" {{(($comment->status=='inactive')? 'selected' : '')}}>@lang('global.inactive')</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">@lang('global.update')</button>
    </form>
  </div>
</div>
@endsection

@push('styles')
<style>
    .order-info,.shipping-info{
        background:#ECECEC;
        padding:20px;
    }
    .order-info h4,.shipping-info h4{
        text-decoration: underline;
    }
</style>
@endpush
