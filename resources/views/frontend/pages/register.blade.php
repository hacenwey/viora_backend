@extends('frontend.layouts.master')

@section('title',settings()->get('app_name').' | Register Page')

@section('main-content')
	<!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="page-title">
                        <h2>{{ trans('global.create_account') }}</h2>
                    </div>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="theme-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('backend.home') }}">{{ trans('global.home') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('global.create_account') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb End -->

    <!--section start-->
    <section class="register-page section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h3>{{ trans('global.create_account') }}</h3>
                    <div class="theme-card">
                        <form class="theme-form" method="post" action="{{route('backend.register.submit')}}">
                            @csrf
                            <div class="form-row">
                                <div class="col-md-4">
                                    <label for="name">{{ trans('global.name') }}</label>
                                    <input type="text" class="form-control" id="fname" placeholder="{{ trans('global.name') }}" value="{{old('name')}}" name="name" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="first_name">{{ trans('global.first_name') }}</label>
                                    <input type="text" class="form-control" id="first_name" placeholder="{{ trans('global.first_name') }}" value="{{old('first_name')}}" name="first_name" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="last_name">{{ trans('global.last_name') }}</label>
                                    <input type="text" class="form-control" id="last_name" placeholder="{{ trans('global.last_name') }}" value="{{old('last_name')}}" name="last_name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email">{{ trans('global.login_email') }}</label>
                                    <input type="email" class="form-control" id="email" placeholder="{{ trans('global.login_email') }}" value="{{old('email')}}" name="email">
                                </div>
                                <div class="col-md-6">
                                    <label for="phone_number">{{ trans('global.phone_number') }}</label>
                                    <input type="text" class="form-control" id="phone_number" placeholder="{{ trans('global.phone_number') }}" value="{{old('phone_number')}}" name="phone_number" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="password">{{ trans('global.password') }}</label>
                                    <input type="password" name="password" class="form-control" id="password" placeholder="{{ trans('global.password') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation">{{ trans('global.login_password_confirmation') }}</label>
                                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="{{ trans('global.login_password_confirmation') }}" required>
                                </div>
                                <button type="submit" class="btn btn-solid">{{ trans('global.create_account') }}</button>
                                <a href="{{route('backend.login')}}" class="btn btn-outline ml-3">{{ trans('global.login') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Section ends-->

@endsection

@push('styles')
<style>
    .shop.login .form .btn{
        margin-right:0;
    }
    .btn-facebook{
        background:#39579A;
    }
    .btn-facebook:hover{
        background:#073088 !important;
    }
    .btn-github{
        background:#444444;
        color:white;
    }
    .btn-github:hover{
        background:black !important;
    }
    .btn-google{
        background:#ea4335;
        color:white;
    }
    .btn-google:hover{
        background:rgb(243, 26, 26) !important;
    }
</style>
@endpush
