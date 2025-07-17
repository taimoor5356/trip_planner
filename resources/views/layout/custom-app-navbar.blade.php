<nav class="layout-navbar container-fluid navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
  <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
      <i class="bx bx-menu bx-sm"></i>
    </a>
  </div>
  <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
    <img src="{{ asset('assets/img/1.png') }}" height="40px" alt="">
    <ul class="navbar-nav flex-row align-items-center ms-auto">
      <!-- Place this tag where you want the button to render. -->

      <!-- <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link m-2 dropdown-toggle hide-arrow text-center" href="javascript:void(0);" data-bs-toggle="dropdown">
          <span class="lh-1 p-1 border border-primary rounded-circle position-relative">
            <i class="bx bx-bell border-primary text-primary"></i>
            <span class="notification-count" id="notification-count"></span>
          </span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" id="exported-files-record">
        </ul>
      </li> -->
      @if(Auth::user())
      <!-- User -->
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          <div class="avatar avatar-online">
            <img src="{{asset('assets/img/avatars/1.png')}}" alt class="w-px-40 h-auto rounded-circle" />
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <a class="dropdown-item" href="#">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar avatar-online">
                    <img src="{{asset('assets/img/avatars/1.png')}}" alt class="w-px-40 h-auto rounded-circle" />
                  </div>
                </div>
                <div class="flex-grow-1">
                  <span class="fw-semibold d-block">@if(Auth::user()){{Auth::user()->name}}@endif</span>
                  <small class="text-muted">@if(Auth::user() && Auth::user()->role){{ucfirst(Auth::user()->role->name)}}@endif</small>
                </div>
              </div>
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="{{route('customer.trips.list')}}">
              <i class="bx bx-trip me-2"></i><span class="align-middle"> View Trips</span>
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="{{route('customer_logout')}}">
              <i class="bx bx-power-off me-2"></i>
              <i class="bx bx-login"></i> <span class="align-middle"> Log Out</span>
            </a>
          </li>
        </ul>
      </li>
      <!--/ User -->
      @else
        @if(Request::route()->getName() != 'custom')
        <li class="ms-2">
          <a class="btn-sm btn btn-secondary" href="{{route('custom')}}">
            <i class="bx bx-home"></i> <span class="align-middle"> Home</span>
          </a>
        </li>
        @endif
        @if (empty($login))
        <li class="ms-2">
          <a class="btn-sm btn btn-primary" href="{{route('login')}}">
            <i class="bx bx-user"></i> <span class="align-middle"> Sign In</span>
          </a>
        </li>
        @endif
      @endif
    </ul>
  </div>
</nav>