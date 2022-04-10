<!-- latest jquery-->
<script src="{{ asset('assets/js/jquery-3.3.1.min.js') }}"></script>

<!-- fly cart ui jquery-->
<script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>

<!-- exitintent jquery-->
<script src="{{ asset('assets/js/jquery.exitintent.js') }}"></script>
<script src="{{ asset('assets/js/exit.js') }}"></script>

<!-- portfolio js -->
{{--<script src="{{ asset('assets/js/isotope.min.js') }}"></script>--}}
{{--<script src="{{ asset('assets/js/main.js') }}"></script>--}}
{{--<script src="{{ asset('assets/js/jquery.magnific-popup.js') }}"></script>--}}
{{--<script src="{{ asset('assets/js/zoom-gallery.js') }}"></script>--}}

<!-- menu js-->
<script src="{{ asset('assets/js/menu.js') }}"></script>

<!-- lazyload js-->
<script src="{{ asset('assets/js/lazysizes.min.js') }}"></script>

<!-- popper js-->
<script src="{{ asset('assets/js/popper.min.js') }}"></script>

<!-- price range js -->
<script src="{{ asset('assets/js/price-range.js') }}"></script>

<!-- slick js-->
<script src="{{ asset('assets/js/slick.js') }}"></script>

<!-- Bootstrap js-->
<script src="{{ asset('assets/js/bootstrap.js') }}"></script>

<!-- Bootstrap Notification js-->
<script src="{{ asset('assets/js/bootstrap-notify.min.js') }}"></script>

<!-- Fly cart js-->
<script src="{{ asset('assets/js/fly-cart.js') }}"></script>

<!-- Zoom js-->
<script src="{{ asset('assets/js/jquery.elevatezoom.js') }}"></script>

<!-- Theme js-->
{{-- <script src="{{ asset('assets/js/script.js') }}"></script> --}}
<script src="{{ asset('assets/js/main.js') }}"></script>

<script>
    // $(window).on('load', function () {
    //     setTimeout(function () {
    //         $('#exampleModal').modal('show');
    //     }, 2500);
    // });

    function openSearch() {
        document.getElementById("search-overlay").style.display = "block";
    }

    function closeSearch() {
        document.getElementById("search-overlay").style.display = "none";
    }
    $(function () {
        $('[data-bs-toggle="tooltip"]').tooltip()
    })
</script>

@stack('scripts')
<script>
    setTimeout(function(){
      $('.alert').slideUp();
    },4000);
</script>
