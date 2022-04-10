<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('backend.admin')}}">
      <div class="sidebar-brand-icon rotate-n-15">
{{--        <i class="fas fa-laugh-wink"></i>--}}
          <img src="{{ settings()->get('logo') }}" alt="" width="50">
      </div>
      <div class="sidebar-brand-text mx-3">
          {{ settings()->get('app_name') }}
      </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->is('admin') ? 'active' : '' }}">
      <a class="nav-link" href="{{route('backend.pos.index')}}">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>@lang('global.pos')</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Pages Collapse Menu -->
    <!-- Nav Item - Charts -->
    <li class="nav-item {{ request()->is('admin/file-manager') || request()->is('admin/file-manager/*') ? 'active' : '' }}">
        <a class="nav-link" href="{{route('backend.file-manager')}}">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>@lang('global.media_manager')</span></a>
    </li>

    @can('access_banners')
        <li class="nav-item {{ request()->is('admin/banner') || request()->is('admin/banner/*') ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-image"></i>
                <span>@lang('cruds.banner.title')</span>
            </a>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">@lang('cruds.banner.title') @lang('global.options'):</h6>
                    <a class="collapse-item" href="{{route('backend.banner.index')}}">@lang('cruds.banner.title')</a>
                    <a class="collapse-item" href="{{route('backend.banner.create')}}">@lang('global.new') @lang('cruds.banner.title_singular')</a>
                </div>
            </div>
        </li>
    @endcan
</ul>
