<nav class="navbar navbar-expand navbar-light bg-white topbar static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-sm-none rounded-circle mr-3">
      <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Search -->
    {{-- <search-autocomplete></search-autocomplete> --}}

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
      {{-- Home page --}}

      <!-- Nav Item - Alerts -->
      <li class="nav-item dropdown no-arrow mx-1">
       @include('backend.notification.show')
      </li>

      <!-- Nav Item - Messages -->
      <li class="nav-item dropdown no-arrow mx-1" id="messageT" data-url="{{route('backend.messages.five')}}">
        @include('backend.message.message')
      </li>

      <div class="topbar-divider d-none d-sm-block"></div>

      <!-- Nav Item - Language switcher -->
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ getAppLocale() }}</span>
        </a>
        <!-- Dropdown - Language switcher -->
        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
            @foreach (config('panel.available_languages') as $code => $lang)
                <a class="dropdown-item {{ app()->getLocale() == $lang['short_code'] ? 'active' : '' }}" href="{{ route('backend.locale', ['locale' => $lang['short_code']]) }}">
                    {{ $lang['title'] }}
                </a>
            @endforeach
        </div>
      </li>
      <div class="topbar-divider d-none d-sm-block"></div>

      <!-- Nav Item - User Information -->
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{Auth::guard()->user()->name}}</span>
          @if(Auth::guard()->user()->photo)
            <img class="img-profile rounded-circle" src="{{Auth::guard()->user()->photo}}">
          @else
            <img class="img-profile rounded-circle" src="{{asset('backend/img/avatar.png')}}">
          @endif
        </a>
        <!-- Dropdown - User Information -->
        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
          <a class="dropdown-item" href="{{route('backend.admin-profile')}}">
            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
            @lang('global.my_profile')
          </a>
          <a class="dropdown-item" href="{{route('backend.change.password.form')}}">
            <i class="fas fa-key fa-sm fa-fw mr-2 text-gray-400"></i>
            @lang('global.change_password')
          </a>
          <a class="dropdown-item" href="{{route('backend.settings')}}">
            <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
            @lang('global.settings')
          </a>
          <div class="dropdown-divider"></div>

          {{-- <form id="logout-form" action="{{ route('backend.e-logout') }}" method="POST">
            @csrf
          </form> --}}
          <a class="dropdown-item" href="{{ route('backend.e-logout') }}">
                 <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> {{ __('global.logout') }}
            </a>
        </div>
      </li>

    </ul>

  </nav>
