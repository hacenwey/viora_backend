 <!-- Bootstrap core JavaScript-->
 <script src="{{ mix('js/app.js') }}"></script>
 <script src="{{asset('backend/vendor/jquery/jquery.min.js')}}"></script>
 <script src="{{asset('backend/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

 <!-- Core plugin JavaScript-->
 <script src="{{asset('backend/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

 <!-- Custom scripts for all pages-->
 <script src="{{asset('backend/js/sb-admin-2.min.js')}}"></script>

 <!-- Page level plugins -->
 <script src="{{asset('backend/vendor/chart.js/Chart.min.js')}}"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>

 {{-- @if (app()->getLocale() == 'ar')
 <script src="https://cdn.rtlcss.com/bootstrap/v4.5.3/js/bootstrap.bundle.min.js" integrity="sha384-40ix5a3dj6/qaC7tfz0Yr+p9fqWLzzAXiwxVLt9dw7UjQzGYw6rWRhFAnRapuQyK" crossorigin="anonymous"></script>
   @endif --}}

 <!-- Page level custom scripts -->
 {{-- <script src="{{asset('backend/js/demo/chart-area-demo.js')}}"></script> --}}
 {{-- <script src="{{asset('backend/js/demo/chart-pie-demo.js')}}"></script> --}}
<script src="{{ asset('js/main.js') }}"></script>

 @stack('scripts')

 <script>
   setTimeout(function(){
     $('.alert').slideUp();
   },4000);
 </script>
