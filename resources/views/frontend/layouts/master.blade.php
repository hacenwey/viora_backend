<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
	@include('frontend.layouts.head')
</head>
<body class="js {{ app()->getLocale() == 'ar' ? 'rtl' : '' }}">

	@include('frontend.layouts.notification')
	<!-- Header -->
	@include('frontend.layouts.header')
    <!--/ End Header -->
    @yield('main-content')

    @include('frontend.layouts.footer')
    @include('frontend.layouts.foot')

    <!-- tap to top start -->
    <div class="tap-top" style="bottom: 150px">
        <div><i class="fa fa-angle-double-up"></i></div>
    </div>
    <div class="tap-whatsapp">
        <a href="https://web.whatsapp.com/send?phone={{ settings()->get('whatsapp') }}&text=" style="color:#FFFFFF" target="_blank" rel="noopener noreferrer"><i class="fa fa-whatsapp"></i></a>
    </div>
    <!-- tap to top end -->
    <script type="text/javascript">
        (function(d, m){
            var kommunicateSettings =
                {"appId":"1f2385bd3eb6a8895a8e29b553f385a60","popupWidget":true,"automaticChatOpenOnNavigation":true};
            var s = document.createElement("script"); s.type = "text/javascript"; s.async = true;
            s.src = "https://widget.kommunicate.io/v2/kommunicate.app";
            var h = document.getElementsByTagName("head")[0]; h.appendChild(s);
            window.kommunicate = m; m._globals = kommunicateSettings;
        })(document, window.kommunicate || {});
    /* NOTE : Use web server to view HTML files as real-time update will not work if you directly open the HTML file in the browser. */
    </script>
</body>
</html>
