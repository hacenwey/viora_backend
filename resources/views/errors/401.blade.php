<!DOCTYPE html>
<html lang="en">

<head>

  @include('backend.layouts.head')

</head>

<body>
  
  <div class="container-fluid">

    <div class="row" style="margin-top:10%">
        <!-- 404 Error Text -->
      <div class="col-md-12">
        <div class="text-center">
          <div class="error mx-auto" data-text="401">401</div>
          <p class="lead text-gray-800 mb-5">Unauthorized</p>
          <p class="text-gray-500 mb-2">It looks like you're Unauthorized to access this page, check if you have the right permissions for that or if the url is still valid!</p>
          <p class="text-gray-500 mb-0">To find out more about this issue please contact the <a href="mailto:contact@e-marsa.mr">administrator</a></p>
          {{-- {{dd(auth()->user())}}; --}}
            {{-- <a href="{{route('backend.home')}}">&larr; Back to Home</a> --}}

        </div>
      </div>
    </div>

    </div>


    @include('backend.layouts.footer')

</body>

</html>
