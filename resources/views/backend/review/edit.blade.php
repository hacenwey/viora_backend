@extends('backend.layouts.master')

@section('title', settings()->get('app_name').' | '. trans('cruds.review.title') .' '. trans('global.edit'))

@section('main-content')
<div class="card">
  <h5 class="card-header">@lang('cruds.review.title_singular') @lang('global.edit')</h5>
  <div class="card-body">
    <form action="{{route('backend.review.update',$review->id)}}" method="POST">
      @csrf
      @method('PATCH')
      <div class="form-group">
        <label for="name">@lang('cruds.review.fields.review_by')</label>
        <input type="text" disabled class="form-control" value="{{$review->user_info->name}}">
      </div>
      <div class="form-group">
        <label for="review">@lang('cruds.review.title_singular')</label>
      <textarea name="review" id="" cols="20" rows="10" class="form-control">{{$review->review}}</textarea>
      </div>
      <div class="form-group">
        <label for="status">@lang('cruds.review.fields.status') :</label>
        <select name="status" id="" class="form-control">
          <option value="">--@lang('global.select') @lang('cruds.review.fields.status')--</option>
          <option value="active" {{(($review->status=='active')? 'selected' : '')}}>@lang('global.active')</option>
          <option value="inactive" {{(($review->status=='inactive')? 'selected' : '')}}>@lang('global.inactive')</option>
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
