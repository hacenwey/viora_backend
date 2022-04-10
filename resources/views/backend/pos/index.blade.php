<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

@include('backend.layouts.head')

<body id="page-top" class="sidebar-toggled">

    <!-- Page Wrapper -->
    <div id="app">
        <div id="wrapper">

            <!-- Sidebar -->
            @include('backend.pos.sidebar')
            <!-- End of Sidebar -->

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">
                <!-- Topbar -->
                @include('backend.pos.header')
                <!-- End of Topbar -->

                <!-- Main Content -->
                <div id="content" class="p-3">
                    @if(session('message'))
                        <div class="row mb-2">
                            <div class="col-lg-12">
                                <div class="alert alert-success" role="alert">{{ session('message') }}</div>
                            </div>
                        </div>
                    @endif
                    @if($errors->count() > 0)
                        <div class="alert alert-danger">
                            <ul class="list-unstyled">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div id="posApp">
                        <pos-module></pos-module>
                    </div>

                </div>
      <!-- End of Main Content -->

    </div>
    <!-- End of Content Wrapper -->
</div>
<!-- End of App ID -->

</div>
<!-- End of Page Wrapper -->

    @include('backend.layouts.foot')
</body>

</html>
