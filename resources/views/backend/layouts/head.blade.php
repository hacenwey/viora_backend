<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ settings()->get('favicon', asset('assets/images/favicon/1.png')) }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ settings()->get('favicon', asset('assets/images/favicon/1.png')) }}" type="image/x-icon">
    <!-- Custom fonts for this template-->
    <link href="{{asset('backend/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <!-- Custom styles for this template-->
    <link href="{{asset('backend/css/sb-admin-2.css')}}" rel="stylesheet">

    @if (app()->getLocale() == 'ar')
        {{-- <link rel="stylesheet" href="https://cdn.rtlcss.com/bootstrap/v4.5.3/css/bootstrap.min.css" integrity="sha384-JvExCACAZcHNJEc7156QaHXTnQL3hQBixvj5RV5buE7vgnNEzzskDtx9NQ4p6BJe" crossorigin="anonymous"> --}}
        <link href="{{asset('backend/css/sb-admin-rtl.css')}}" rel="stylesheet">
    @endif
    @stack('styles')
    <script>
        window.App = {!! json_encode([
            'settings' => \settings()->all()
        ]); !!}
    </script>
</head>
