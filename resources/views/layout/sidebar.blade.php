
@if(Auth::user() && Auth::user()->user_type == 1)
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo d-flex justify-content-center">
    <a href="#" class="app-brand-link">
      <span class="app-brand-logo demo">
        <img src="{{ asset('assets/img/1.png') }}" height="60px" alt="">
      </span>
      <div class="app-brand-text menu-text fw-bolder ms-2 d-flex justify-content-center" style="font-size: xx-large;">
      </div>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>
    @can('accommodation_view')
      <ul class="menu-inner py-5 mb-5">
        <!-- Dashboard -->
        <li class="menu-item @if(Request::url() == url('admin/dashboard')) active @endif">
          <a href="{{url('admin/dashboard')}}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-home-circle"></i>
            <div data-i18n="Analytics">Dashboard</div>
          </a>
        </li>
        <!-- Forms & Tables -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Trip Planner</span></li>
        <!-- Forms -->
        @php
          $sideBarItems = \App\Http\Controllers\HomeController::sideBarItems();
        @endphp

        @foreach($sideBarItems as $item)
          @php
              $icon = $item['icon'];
              $name = $item['name'];
          @endphp

          @if (is_array($name) && !is_numeric(key($name)))
              {{-- Handle grouped menus like 'buildings' => [ ... ] --}}
              @php
                  $groupTitle = key($name);
                  $subItems = $name[$groupTitle];
                  $isActive = collect($subItems)->contains(function ($subItem) {
                    if ($subItem == 'roles' || $subItem == 'permissions') {
                      $url = request()->is("admin/acl/$subItem*");
                    } else {
                      $url = request()->is("admin/$subItem*");
                    }
                    return $url;
                  });
              @endphp
              <li class="menu-item {{ $isActive ? 'active open' : '' }}">
                  <a href="javascript:void(0);" class="menu-link menu-toggle">
                      <i class="menu-icon tf-icons bx {{ $icon }}"></i>
                      <div data-i18n="Group">{{ ucwords(str_replace('-', ' ', $groupTitle)) }}</div>
                  </a>
                  <ul class="menu-sub">
                      @foreach($subItems as $subItem)
                            @if ($subItem == 'roles' || $subItem == 'permissions')
                              <li class="menu-item {{ request()->is("admin/acl/$subItem*") ? 'active' : '' }}">
                                <a href="{{ url("admin/acl/$subItem/list") }}" class="menu-link">
                                    <div data-i18n="Basic Inputs">{{ ucwords(str_replace('-', ' ', $subItem)) }}</div>
                                </a>
                              </li>
                            @else
                              <li class="menu-item {{ request()->is("admin/$subItem*") ? 'active' : '' }}">
                                <a href="{{ url("admin/$subItem/list") }}" class="menu-link">
                                    <div data-i18n="Basic Inputs">{{ ucwords(str_replace('-', ' ', $subItem)) }}</div>
                                </a>
                              </li>
                            @endif
                      @endforeach
                  </ul>
              </li>
          @else
              {{-- Handle simple items --}}
              @php
                  $slug = $name;
                  $isActive = request()->is("admin/$slug*");
              @endphp
              <li class="menu-item {{ $isActive ? 'active open' : '' }}">
                  <a href="{{ url("admin/$slug/list") }}" class="menu-link">
                      <i class="menu-icon tf-icons bx {{ $icon }}"></i>
                      <div data-i18n="Basic Inputs">{{ ucwords(str_replace('-', ' ', $slug)) }}</div>
                  </a>
              </li>
          @endif
        @endforeach
      </ul>
    @endcan
</aside>
@endif