@extends('backend.layouts.master')
@section('title',settings()->get('app_name').' | '. trans('cruds.message.title'))
@section('main-content')
<div class="card">
  <h5 class="card-header">@lang('cruds.message.title_singular')</h5>
  <div class="card-body">
    @if($message)
        @if($message->photo)
        <img src="{{$message->photo}}" class="rounded-circle " style="margin-left:44%;">
        @else
        <img src="{{asset('backend/img/avatar.png')}}" class="rounded-circle " style="margin-left:44%;">
        @endif
        <div class="py-4">@lang('cruds.message.fields.from'): <br>
            @lang('cruds.user.fields.name') :{{$message->name}}<br>
            @lang('cruds.user.fields.email') :{{$message->email}}<br>
            @lang('cruds.user.fields.phone') :{{$message->phone}}
        </div>
        <hr/>
        <h5 class="text-center" style="text-decoration:underline"><strong>@lang('cruds.message.fields.subject') :</strong> {{$message->subject}}</h5>
        <p class="py-5">{{$message->message}}</p>

    @endif

  </div>
</div>
@endsection
