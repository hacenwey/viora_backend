@extends('frontend.layouts.master')

@section('title', settings()->get('app_name').' | '. trans('global.login'))

@section('main-content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="page-title">
                        <h2>{{ trans('global.customers_login') }}</h2>
                    </div>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="theme-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('backend.home') }}">{{ trans('global.home') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('global.login') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb End -->

    <!--section start-->
    <section class="login-page section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h3>{{ trans('global.login') }}</h3>
                    <div class="theme-card">
                        <form class="theme-form" method="post" action="{{route('backend.login.submit')}}">
                            @csrf
                            <div class="form-group">
                                <label for="email">{{ trans('global.login_email') }}</label>
                                <input type="text" class="form-control" id="email" value="{{old('email')}}" name="email" placeholder="{{ trans('global.login_email') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="review">{{ trans('global.login_password') }}</label>
                                <input type="password" class="form-control" id="review" name="password" placeholder="{{ trans('global.login_password') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="checkbox-inline" for="2"><input name="news" class="mr-2" id="2" type="checkbox">{{ trans('global.remember_me') }}</label>
                            </div>
                            <button type="submit" class="btn btn-solid">{{ trans('global.login') }}</button>
                            @if (Route::has('backend.password.request'))
                                <a class="lost-pass" href="{{ route('backend.password.request') }}">
                                    @lang('global.lost_password')
                                </a>
                            @endif
                        </form>
                    </div>
                </div>
                <div class="col-lg-6 right-login">
                    <h3>{{ trans('global.new_customer') }}</h3>
                    <div class="theme-card authentication-right">
                        <h6 class="title-font">{{ trans('global.create_account') }}</h6>
                        <p>{{ trans('global.registration_help') }}</p>
                        <a href="{{ route('backend.register') }}" class="btn btn-solid">{{ trans('global.create_account') }}</a>
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
