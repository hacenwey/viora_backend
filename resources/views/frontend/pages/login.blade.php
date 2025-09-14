<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    @include('frontend.layouts.head')
    <style>
        .shop.login .form .btn {
            margin-right: 0;
        }
        .btn-facebook {
            background: #39579A;
        }
        .btn-facebook:hover {
            background: #073088 !important;
        }
        .btn-github {
            background: #444444;
            color: white;
        }
        .btn-github:hover {
            background: black !important;
        }
        .btn-google {
            background: #ea4335;
            color: white;
        }
        .btn-google:hover {
            background: rgb(243, 26, 26) !important;
        }
    </style>
</head>
<body class="js {{ app()->getLocale() == 'ar' ? 'rtl' : '' }}">
 
    <!--section start-->
    <section class="login-page section-b-space d-flex align-items-center">
       
        <div class="container">
            <h1 class="app-name text-center">{{ settings()->get('app_name') }}</h1>
            <div class="row justify-content-center">
                <div class="col-lg-6">
            
                    <div class="theme-card">
                        <!-- <h4 class="title-border text-center">Administration</h4> -->
                        <form class="theme-form" method="post" action="{{route('backend.login.submit')}}">
                            @csrf
                            <div class="form-group">
                                <label for="email">{{ trans('global.login_email') }}</label>
                                <input type="text" class="form-control" id="email" value="{{old('email')}}" name="email" placeholder="{{ trans('global.login_email') }}" required>
                            </div>
                            <div class="form-group te">
                                <label for="review">{{ trans('global.login_password') }}</label>
                                <input type="password" class="form-control" id="review" name="password" placeholder="{{ trans('global.login_password') }}" required>
                            </div>
                            <div class="w-100 d-flex align-items-center justify-content-center">

                            <button type="submit" class=" btn-login ">{{ trans('global.login') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>
</html>
<style>
    .btn-login {
        background-color: #000;
        color:white;
        border-radius: 0;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        font-weight: bold;
        width: 100%;
        height: 50px;
        margin-top: 10px;
    }

    .app-name {
     color: #b8860b;
     margin-bottom: 30px;
    
    }
</style>