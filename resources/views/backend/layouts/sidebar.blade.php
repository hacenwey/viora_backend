<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('backend.admin') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            {{-- <i class="fas fa-laugh-wink"></i> --}}
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
        <a class="nav-link" href="{{ route('backend.admin') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>@lang('global.dashboard')</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">
    <li class="nav-item {{ request()->is('admin/banner') || request()->is('admin/banner/*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#supply_m" aria-expanded="true"
            aria-controls="supply_m">
            <i class="fas fa-image"></i>
            <span>Système d'approvisionnement</span>
        </a>
        <div id="supply_m" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                {{-- <h6 class="collapse-header">Supplier @lang('global.options'):</h6> --}}
                <a class="collapse-item" href="{{ route('backend.provider.index') }}">
                    <span>Frounisseurs</span></a>
                <a class="collapse-item" href="{{ route('backend.productsSuppliers.index') }}">
                    <span>Produits/Frounisseurs </span></a>
                <a class="collapse-item" href="{{ route('backend.supplies') }}">
                    <span>Approvisionnement </span></a>
                <a class="collapse-item" href="{{ route('backend.pre_orders') }}">
                    <span>Préparer une commande</span></a>
                <a class="collapse-item" href="">
                    <span>Commandes</span></a>
                <a class="collapse-item" href="{{ route('backend.currencys.index') }}">
                    <span>Devise</span></a>
            </div>
        </div>
    </li>
    <hr class="sidebar-divider">
    {{-- <!-- Nav Item - Fourniseur -->
<li class="nav-item {{ request()->is('admin/provider') || request()->is('admin/provider') ? 'active' : '' }}">
    <a class="nav-link" href="{{route('backend.provider.index')}}">
        <i class="fas fa-tags fa-folder"></i>
        <span>Supplier management</span></a>
    </li>
    <hr class="sidebar-divider">
    <li class="nav-item {{ request()->is('admin/productsSuppliers') || request()->is('admin/productsSuppliers') ? 'active' : '' }}">
        <a class="nav-link" href="{{route('backend.productsSuppliers.index')}}">
            <i class="fas fa-tags fa-folder"></i>
            <span>Products Suppliers</span></a>
    </li>
    <hr class="sidebar-divider">
    <li class="nav-item {{ request()->is('admin/commandes') || request()->is('admin/commandes') ? 'active' : '' }}">
        <a class="nav-link" href="{{route('backend.commandes.index')}}">
            <i class="fas fa-tags fa-folder"></i>
            <span>Commandes</span></a>
    </li>
    <hr class="sidebar-divider">
    <li class="nav-item {{ request()->is('admin/supply') || request()->is('admin/supply') ? 'active' : '' }}">
        <a class="nav-link" href="{{route('backend.supply.index')}}">
            <i class="fas fa-tags fa-folder"></i>
            <span>Command lines</span></a>
    </li>
    <hr class="sidebar-divider">
    <li class="nav-item {{ request()->is('admin/currencys') || request()->is('admin/currencys') ? 'active' : '' }}">
        <a class="nav-link" href="{{route('backend.currencys.index')}}">
            <i class="fas fa-tags fa-folder"></i>
            <span>Currencys</span></a>
    </li>
    <hr class="sidebar-divider"> --}}
    <!-- Heading -->
    <div class="sidebar-heading">
        @lang('cruds.banner.title_singular')
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <!-- Nav Item - Charts -->
    <li
        class="nav-item {{ request()->is('admin/file-manager') || request()->is('admin/file-manager/*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('backend.file-manager') }}">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>@lang('global.media_manager')</span></a>
    </li>

    @can('access_banners')
        <li class="nav-item {{ request()->is('admin/banner') || request()->is('admin/banner/*') ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-image"></i>
                <span>@lang('cruds.banner.title')</span>
            </a>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">@lang('cruds.banner.title') @lang('global.options'):</h6>
                    <a class="collapse-item" href="{{ route('backend.banner.index') }}">@lang('cruds.banner.title')</a>
                    <a class="collapse-item" href="{{ route('backend.banner.create') }}">@lang('global.new')
                        @lang('cruds.banner.title_singular')</a>
                </div>
            </div>
        </li>
    @endcan
    <!-- Divider -->
    @can('access_store')
        <hr class="sidebar-divider">
        <!-- Heading -->
        <div class="sidebar-heading">
            @lang('global.store')
        </div>

        <!-- Categories -->
        @can('access_categories')
            <li class="nav-item {{ request()->is('admin/category') || request()->is('admin/category/*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#categoryCollapse"
                    aria-expanded="true" aria-controls="categoryCollapse">
                    <i class="fas fa-sitemap"></i>
                    <span>@lang('cruds.category.title_singular')</span>
                </a>
                <div id="categoryCollapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">@lang('cruds.banner.title') @lang('global.options'):</h6>
                        <a class="collapse-item" href="{{ route('backend.category.index') }}">@lang('cruds.category.title')</a>
                        @can('add_categories')
                            <a class="collapse-item" href="{{ route('backend.category.create') }}">@lang('global.new')
                                @lang('cruds.category.title_singular')</a>
                        @endcan
                    </div>
                </div>
            </li>
        @endcan
        {{-- Products --}}
        @can('access_products')
            <li class="nav-item {{ request()->is('admin/product') || request()->is('admin/product/*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#productCollapse"
                    aria-expanded="true" aria-controls="productCollapse">
                    <i class="fas fa-cubes"></i>
                    <span>@lang('cruds.product.title')</span>
                </a>
                <div id="productCollapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">@lang('cruds.product.title') @lang('global.options'):</h6>
                        <a class="collapse-item" href="{{ route('backend.product.index') }}">@lang('cruds.product.title')</a>
                        @can('add_products')
                            <a class="collapse-item" href="{{ route('backend.product.create') }}">@lang('global.new')
                                @lang('cruds.product.title_singular')</a>
                        @endcan
                    </div>
                </div>
            </li>
        @endcan
        {{-- Collections --}}
        @can('access_collections')
            <li
                class="nav-item {{ request()->is('admin/collections') || request()->is('admin/collection/*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collectionCollapse"
                    aria-expanded="true" aria-controls="collectionCollapse">
                    <i class="fas fa-cubes"></i>
                    <span>@lang('cruds.collection.title')</span>
                </a>
                <div id="collectionCollapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">@lang('cruds.collection.title') @lang('global.options'):</h6>
                        <a class="collapse-item" href="{{ route('backend.collections.index') }}">@lang('cruds.collection.title')</a>
                        @can('add_collections')
                            <a class="collapse-item" href="{{ route('backend.collections.create') }}">@lang('global.new')
                                @lang('cruds.collection.title_singular')</a>
                        @endcan
                    </div>
                </div>
            </li>
        @endcan
        {{-- Brands --}}
        @can('access_brands')
            <li class="nav-item {{ request()->is('admin/brand') || request()->is('admin/brand/*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#brandCollapse"
                    aria-expanded="true" aria-controls="brandCollapse">
                    <i class="fas fa-table"></i>
                    <span>@lang('cruds.brand.title')</span>
                </a>
                <div id="brandCollapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">@lang('cruds.brand.title') @lang('global.options'):</h6>
                        <a class="collapse-item" href="{{ route('backend.brand.index') }}">@lang('cruds.brand.title')</a>
                        @can('add_brands')
                            <a class="collapse-item" href="{{ route('backend.brand.create') }}">@lang('global.new')
                                @lang('cruds.brand.title_singular')</a>
                        @endcan
                    </div>
                </div>
            </li>
        @endcan
        {{-- Shipping --}}
        @can('access_shippings')
            <li class="nav-item {{ request()->is('admin/shipping') || request()->is('admin/shipping/*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#shippingCollapse"
                    aria-expanded="true" aria-controls="shippingCollapse">
                    <i class="fas fa-truck"></i>
                    <span>@lang('cruds.shipping.title')</span>
                </a>
                <div id="shippingCollapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">@lang('cruds.shipping.title') @lang('global.options'):</h6>
                        <a class="collapse-item" href="{{ route('backend.shipping.index') }}">@lang('cruds.shipping.title')</a>
                        @can('add_shippings')
                            <a class="collapse-item" href="{{ route('backend.shipping.create') }}">@lang('global.new')
                                @lang('cruds.shipping.title_singular')</a>
                        @endcan
                    </div>
                </div>
            </li>
        @endcan
        {{-- Clients --}}
        @can('access_clients')
            <li class="nav-item {{ request()->is('admin/clients') || request()->is('admin/clients/*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#clientsCollapse"
                    aria-expanded="true" aria-controls="clientsCollapse">
                    <i class="fas fa-truck"></i>
                    <span>@lang('cruds.client.title')</span>
                </a>
                <div id="clientsCollapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">@lang('cruds.client.title') @lang('global.options'):</h6>
                        <a class="collapse-item" href="{{ route('backend.clients.index') }}">@lang('cruds.client.title')</a>
                        @can('add_clients')
                            <a class="collapse-item" href="{{ route('backend.clients.create') }}">@lang('global.new')
                                @lang('cruds.client.title_singular')</a>
                        @endcan
                    </div>
                </div>
            </li>
        @endcan
        <!--Orders -->
        @can('access_orders')
            <li class="nav-item {{ request()->is('admin/order') || request()->is('admin/order/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('backend.order.index') }}">
                    <i class="fas fa-hammer fa-chart-area"></i>
                    <span>@lang('cruds.order.title')</span>
                </a>
            </li>
        @endcan
        <!--Surveys -->
        @can('access_surveys')
            <li class="nav-item {{ request()->is('admin/surveys') || request()->is('admin/surveys/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('backend.surveys.index') }}">
                    <i class="fas fa-hammer fa-chart-area"></i>
                    <span>@lang('cruds.survey.title')</span>
                </a>
            </li>
        @endcan
        <!-- Reviews -->
        @can('access_reviews')
            <li class="nav-item {{ request()->is('review') || request()->is('review/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('backend.review.index') }}">
                    <i class="fas fa-comments"></i>
                    <span>@lang('cruds.review.title')</span></a>
            </li>
        @endcan
    @endcan

    @can('access_blog')
        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            @lang('global.blog')
        </div>

        <!-- Posts -->
        @can('access_posts')
            <li class="nav-item {{ request()->is('admin/post') || request()->is('admin/post/*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#postCollapse"
                    aria-expanded="true" aria-controls="postCollapse">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>@lang('cruds.post.title')</span>
                </a>
                <div id="postCollapse" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">@lang('cruds.post.title_singular') @lang('global.options'):</h6>
                        <a class="collapse-item" href="{{ route('backend.post.index') }}">@lang('cruds.post.title')</a>
                        @can('add_posts')
                            <a class="collapse-item" href="{{ route('backend.post.create') }}">@lang('global.new')
                                @lang('cruds.post.title_singular')</a>
                        @endcan
                    </div>
                </div>
            </li>
        @endcan
        <!-- Category -->
        @can('access_post_categories')
            <li
                class="nav-item {{ request()->is('admin/post-category') || request()->is('admin/post-category/*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#postCategoryCollapse"
                    aria-expanded="true" aria-controls="postCategoryCollapse">
                    <i class="fas fa-sitemap fa-folder"></i>
                    <span>@lang('cruds.category.title')</span>
                </a>
                <div id="postCategoryCollapse" class="collapse" aria-labelledby="headingPages"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">@lang('cruds.category.title_singular') @lang('global.options'):</h6>
                        <a class="collapse-item" href="{{ route('backend.post-category.index') }}">@lang('cruds.category.title')</a>
                        @can('add_post_categories')
                            <a class="collapse-item" href="{{ route('backend.post-category.create') }}">@lang('global.new')
                                @lang('cruds.category.title_singular')</a>
                        @endcan
                    </div>
                </div>
            </li>
        @endcan
        <!-- Tags -->
        @can('access_post_tags')
            <li class="nav-item {{ request()->is('admin/post-tag') || request()->is('admin/post-tag/*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#tagCollapse"
                    aria-expanded="true" aria-controls="tagCollapse">
                    <i class="fas fa-tags fa-folder"></i>
                    <span>@lang('cruds.tag.title')</span>
                </a>
                <div id="tagCollapse" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">@lang('cruds.tag.title_singular') @lang('global.options'):</h6>
                        <a class="collapse-item" href="{{ route('backend.post-tag.index') }}">@lang('cruds.tag.title')</a>
                        @can('add_post_tags')
                            <a class="collapse-item" href="{{ route('backend.post-tag.create') }}">@lang('global.new')
                                @lang('cruds.tag.title_singular')</a>
                        @endcan
                    </div>
                </div>
            </li>
        @endcan
        <!-- Comments -->
        @can('access_post_comments')
            <li class="nav-item {{ request()->is('admin/comment') || request()->is('admin/comment/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('backend.comment.index') }}">
                    <i class="fas fa-comments fa-chart-area"></i>
                    <span>@lang('cruds.comment.title')</span>
                </a>
            </li>
        @endcan
    @endcan

    <!-- Divider -->
    @can('content_page_access')
        <hr class="sidebar-divider d-none d-md-block">
        <!-- Heading -->
        <div class="sidebar-heading">
            @lang('cruds.content_page.title')
        </div>
        @can('access_content_category')
            <li
                class="nav-item {{ request()->is('admin/content-categories') || request()->is('admin/content-categories/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('backend.content-categories.index') }}">
                    <i class="fas fa-table"></i>
                    <span>@lang('cruds.content_category.title')</span></a>
            </li>
        @endcan
        @can('access_content_tag')
            <li
                class="nav-item {{ request()->is('admin/content-tags') || request()->is('admin/content-tags/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('backend.content-tags.index') }}">
                    <i class="fas fa-table"></i>
                    <span>@lang('cruds.content_tag.title')</span></a>
            </li>
        @endcan
        <li class="nav-item {{ request()->is('') || request()->is('admin/content-pages/*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('backend.content-pages.index') }}">
                <i class="fas fa-table"></i>
                <span>@lang('cruds.content_page.title')</span></a>
        </li>
    @endcan

    <!-- Divider -->
    @can('access_general_settings')
        <hr class="sidebar-divider d-none d-md-block">
        <!-- Heading -->
        <div class="sidebar-heading">
            @lang('global.general_settings')
        </div>
        @can('access_attributes')
            <li
                class="nav-item {{ request()->is('admin/attribute') || request()->is('admin/attribute/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('backend.attribute.index') }}">
                    <i class="fas fa-table"></i>
                    <span>@lang('cruds.attribute.title')</span></a>
            </li>
        @endcan
        @can('access_coupons')
            <li class="nav-item {{ request()->is('admin/coupon') || request()->is('admin/coupon/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('backend.coupon.index') }}">
                    <i class="fas fa-table"></i>
                    <span>@lang('cruds.coupon.title')</span></a>
            </li>
        @endcan
        @can('access_payments')
            <li class="nav-item {{ request()->is('admin/payments') || request()->is('admin/payments/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('backend.payments.index') }}">
                    <i class="fas fa-credit-card"></i>
                    <span>@lang('cruds.payment.title')</span></a>
            </li>
        @endcan
        @can('access_cities')
            <li class="nav-item {{ request()->is('admin/cities') || request()->is('admin/cities/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('backend.cities.index') }}">
                    <i class="fas fa-credit-card"></i>
                    <span>@lang('cruds.city.title')</span></a>
            </li>
        @endcan
        @can('access_messages')
            <li class="nav-item {{ request()->is('admin/message') || request()->is('admin/message/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('backend.message.index') }}">
                    <i class="fas fa-envelope"></i>
                    <span>@lang('global.messages')</span></a>
            </li>
        @endcan
        <!-- Users -->
        @can('access_users')
            <li class="nav-item {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('backend.users.index') }}">
                    <i class="fas fa-users"></i>
                    <span>@lang('cruds.user.title')</span></a>
            </li>
        @endcan
        <!-- Roles -->
        @can('access_roles')
            <li class="nav-item {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('backend.roles.index') }}">
                    <i class="fa-fw fas fa-briefcase"></i>
                    <span>@lang('cruds.role.title')</span></a>
            </li>
        @endcan
        <!-- Permissions -->
        @can('access_permissions')
            <li
                class="nav-item {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('backend.permissions.index') }}">
                    <i class="fa-fw fas fa-unlock-alt"></i>
                    <span>@lang('cruds.permission.title')</span></a>
            </li>
        @endcan
        <!-- General settings -->
        @can('access_settings')
            <li class="nav-item {{ request()->is('admin/settings') || request()->is('admin/settings/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('backend.settings') }}">
                    <i class="fas fa-cog"></i>
                    <span>@lang('global.settings')</span></a>
            </li>
        @endcan
    @endcan

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
