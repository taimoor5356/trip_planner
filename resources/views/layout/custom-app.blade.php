<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>{{$header_title}}</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{asset('assets/img/favicon/favicon.ico')}}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{asset('assets/vendor/fonts/boxicons.css')}}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{asset('assets/vendor/css/core.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{asset('assets/vendor/css/theme-default.css')}}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{asset('assets/css/demo.css')}}" />

    <link rel="stylesheet" href="{{asset('css/styles.css')}}">

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />

    <link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}" />
    
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    @yield('_styles')
    <style>
      .position-relative {
          position: relative;
      }
      .notification-count {
          position: absolute;
          top: -7px;
          right: -7px;
          background-color: red;
          color: white;
          border-radius: 50%;
          padding: 0.25em 0.5em;
          font-size: 0.75em;
          line-height: 1;
          font-weight: bold;
      }
    </style>
    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{asset('assets/vendor/js/helpers.js')}}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{asset('assets/js/config.js')}}"></script>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="w-100">
          <!-- Navbar -->
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
                    <a class="btn-sm btn btn-primary sign-in-modal" href="#">
                      <i class="bx bx-user"></i> <span class="align-middle"> Sign In</span>
                    </a>
                  </li>
                  @endif
                @endif
              </ul>
            </div>
          </nav>
          <!-- / Navbar -->
        <!-- Layout container -->
        <div class="container-fluid">

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <div class="alert-mesg">

            </div>
            <!-- Content -->
             <div class="container">
              <div id="alert-message-notification">
                
              </div>
             </div>
              @yield('content')
            <!-- / Content -->

            <!-- Footer -->
             @include('layout.footer')
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    
    <div class="modal fade modal-center" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="login-modal" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content modal-center">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <!-- Logo -->
                            <div class="text-center">
                            <i class="bx bx-user bg-primary text-white p-2 rounded-circle fs-2"></i>
                            </div>
                            <div class="app-brand text-center justify-content-center mb-0 mt-3">
                            <a href="#" class="app-brand-link">
                                <span class="app-brand-text text-body fw-bolder" style="font-size:xx-large">Sign In</span>
                            </a>
                            </div>
                            <p class="text-center mt-2">Access your Trip Planner Pro account.</p>
                            <!-- /Logo -->
                            <p class="mb-4">
                            @include('_messages')
                            </p>
                            <form class="mb-3" id="login-form">
                                @csrf
                                <div class="mb-3">
                                    <label for="sign_in_method" class="form-label">{{ __('Login with') }}</label>
                                    <select name="sign_in_method" id="sign_in_method" class="form-control bg-light">
                                    <option value="email_address"><i class="bx bx-user"></i> Email Address</option>
                                    <option value="mobile_number"><i class="bx bx-user"></i> Mobile Number</option>
                                    </select>
                                </div>
                                <div class="mobile-number-card d-none">
                                    <div class="mb-3">
                                    <label for="mobile_number" class="form-label">{{ __('Mobile Number') }}</label>
                                    <input
                                        type="number"
                                        class="form-control bg-light"
                                        id="mobile_number"
                                        name="mobile_number"
                                        placeholder="Enter your mobile number"
                                        value="{{old('mobile_number')}}" />
                                    <small class="text-danger d-none">Required</small>
                                    </div>
                                </div>
                                <div class="email-address-card">
                                    <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                    <input
                                        type="email"
                                        class="form-control bg-light login-input-field"
                                        id="email"
                                        name="email"
                                        placeholder="Enter your email"
                                        required
                                        value="{{old('email')}}" />
                                    <small class="text-danger d-none">Required</small>
                                    </div>
                                </div>
                                <div class="mb-3 form-password-toggle">
                                    <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">{{ __('Password') }}</label>
                                    <!-- <a href="auth-forgot-password-basic.html">
                                        <small>Forgot Password?</small>
                                        </a> -->
                                    </div>
                                    <div class="input-group input-group-merge">
                                    <input
                                        type="password"
                                        id="password"
                                        class="form-control login-input-field"
                                        name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password"
                                        required />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                    <small class="text-danger d-none">Required</small>
                                </div>

                                <input type="hidden" name="trip_login" id="trip_login" value="trip_login">
                                <input type="hidden" name="trip_signup" id="trip_signup" value="trip_signup">
                                <input type="hidden" name="link" id="link" value="{{url()->full()}}">

                                <div class="mb-3">
                                    <button class="btn btn-primary d-grid w-100" id="submit-trip-login">Sign in</button>
                                </div>
                                <div class="text-center">
                                Don't have account? 
                                <a class="mb-3 text-center" id="signup-button" href="#">
                                    Sign up
                                </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-center" id="signup-modal" tabindex="-1" role="dialog" aria-labelledby="signup-modal" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content modal-center">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <!-- Logo -->
                            <div class="text-center">
                            <i class="bx bx-user bg-primary text-white p-2 rounded-circle fs-2"></i>
                            </div>
                            <div class="app-brand text-center justify-content-center mb-0 mt-3">
                            <a href="#" class="app-brand-link">
                                <span class="app-brand-text text-body fw-bolder" style="font-size:xx-large">Sign Up</span>
                            </a>
                            </div>
                            <p class="text-center mt-2">Access your Trip Planner Pro account.</p>
                            <!-- /Logo -->
                            <p class="mb-4">
                            @include('_messages')
                            </p>
                            <form class="mb-3">
                                @csrf
                                <div class="name-card">
                                    <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Full Name') }}</label>
                                    <input
                                        type="name"
                                        class="form-control bg-light signup-input-field"
                                        id="signup_name"
                                        name="name"
                                        placeholder="Enter your name"
                                        required
                                        value="{{old('name')}}" />
                                    <small class="text-danger d-none">Required</small>
                                    </div>
                                </div>
                                <!-- <div class="mb-3">
                                    <label for="sign_up_method" class="form-label">{{ __('Sign up via Mobile number or Email') }}</label>
                                    <select name="sign_up_method" id="sign_up_method" class="form-control bg-light">
                                    <option value="email_address">Email Address</option>
                                    <option value="mobile_number">Mobile Number</option>
                                    </select>
                                </div> -->
                                <div class="mobile-number-card">
                                    <div class="mb-3">
                                    <label for="mobile_number" class="form-label">{{ __('Mobile Number') }}</label>
                                    <input
                                        type="number"
                                        class="form-control bg-light signup-input-field"
                                        id="signup_mobile_number"
                                        name="mobile_number"
                                        placeholder="Enter your mobile number"
                                        value="{{old('mobile_number')}}" />
                                    <small class="text-danger d-none">Required</small>
                                    </div>
                                </div>
                                <div class="email-address-card">
                                    <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                    <input
                                        type="email"
                                        class="form-control bg-light signup-input-field"
                                        id="signup_email"
                                        name="email"
                                        placeholder="Enter your email"
                                        required
                                        value="{{old('email')}}" />
                                    <small class="text-danger d-none">Required</small>
                                    </div>
                                </div>
                                <div class="mb-3 form-password-toggle">
                                    <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">{{ __('Password') }}</label>
                                    <!-- <a href="auth-forgot-password-basic.html">
                                        <small>Forgot Password?</small>
                                        </a> -->
                                    </div>
                                    <div class="input-group input-group-merge">
                                    <input
                                        type="password"
                                        id="signup_password"
                                        class="form-control bg-light signup-input-field"
                                        name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password"
                                        required />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                    <small class="text-danger d-none">Required</small>
                                </div>
                                <div class="mb-3 form-privacy-policy">
                                    <div class="d-flex justify-content-between">
                                    <label class="form-label" for="privacy-policy">{{ __('privacy-policy') }}</label>
                                    <!-- <a href="auth-forgot-privacy-policy-basic.html">
                                        <small>Forgot privacy-policy?</small>
                                        </a> -->
                                    </div>
                                    <div class="input-group input-group-merge">
                                    <span class="border border-primary form-control fs-6 d-flex align-items-center">
                                        <input type="checkbox" required name="privacy_policy" class="privacy-policy-checkbox"> &nbsp; &nbsp;<small> I accept the Privacy Policy and Terms of Service.</small>
                                    </span>
                                    </div>
                                    <small class="text-danger d-none">Required</small>
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-primary d-grid w-100" type="button" id="submit-trip-signup">Sign up</button>
                                </div>
                                <div class="mb-3 text-center sign-in-modal">
                                    Already have an account? <a href="#">Sign in</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{asset('assets/vendor/libs/jquery/jquery.js')}}"></script>
    
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{asset('assets/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{asset('assets/vendor/js/bootstrap.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>

    <script src="{{asset('assets/vendor/js/menu.js')}}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>

    <!-- Main JS -->
    <script src="{{asset('assets/js/main.js')}}"></script>

    <!-- Page JS -->
    <script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script>
      // Enable pusher logging - don't include this in production
      Pusher.logToConsole = true;

      var pusher = new Pusher('749fe8beaacad593048d', {
        cluster: 'ap1'
      });

      var channel = pusher.subscribe('my-channel');
      channel.bind('my-event', function(data) {
        // $('#alert-message-notification').html(`<div class="alert alert-success alert-message-badge" role="alert">${data}</div>`);
      });

      (function() {
        function checkCount() {
          $.ajax({
            url: "{{url('get-notifications')}}",
            method: 'POST',
            data: {
              _token: "{{csrf_token()}}",
            },
            success: function (res) {
            }
          });
        }
        // checkCount();
        $('.date_range_picker').daterangepicker({
          autoUpdateInput: false,      
          locale: {
              cancelLabel: 'Clear'
          }
        }).on('apply.daterangepicker', function(ev, picker) {
            var startDate = picker.startDate ? picker.startDate.format('YYYY/MM/DD') : '';
            var endDate = picker.endDate ? picker.endDate.format('YYYY/MM/DD') : '';
            $(this).val(startDate + ' - ' + endDate).trigger('change');
        }).on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('').trigger('change');
        });
        $('.date_range_picker').val('');
        let idleTime = 0;

        function timerIncrement() {
            idleTime++;
            if (idleTime > 1) { // 10 minutes
                // window.location.href = '{{route("postlogout")}}'; // Change to your logout route
            }
        }

        // Increment the idle time counter every minute
        setInterval(timerIncrement, 60000); // 1 minute

        // Zero the idle timer on mouse movement, key press, etc.
        window.onmousemove = window.onkeypress = window.onload = () => {
            idleTime = 0;
        };
        
        setTimeout(() => {
          $('.alert-message-badge').hide();
        }, 5000);
        // setInterval(function() {
        //   // checkCount();
        // }, 2000);
      8})();

      $(document).ready(function() {
        $(document).on('click', '#signup-button', function(e) {
            $('#login-modal').modal('hide');
            $('#signup-modal').modal('show');
        });

        $(document).on('click', '.sign-in-modal', function(e) {
            $('#signup-modal').modal('hide');
            $('#login-modal').modal('show');
        });

        $(document).on('click', '#submit-trip-login', function(e) {
            e.preventDefault();
            var tripLogin = 'trip_login';

            var email = $('#email');
            var phone = $('#mobile_number');
            var password = $('#password');
            
            let isValid = true;

            $('.login-input-field').each(function() {
                let _thisLoginInput = $(this);
                let val = _thisLoginInput.val();
                let errorMessage = _thisLoginInput.closest('.mb-3').find('small.text-danger');

                if (val == null || val.trim() == '' || val == undefined) {
                    _thisLoginInput.css('border', '1px solid red');
                    errorMessage.removeClass('d-none'); // Show "Required"
                    isValid = false;
                } else {
                    _thisLoginInput.css('border', '');
                    errorMessage.addClass('d-none'); // Hide "Required"
                }
            });

            if (!isValid) {
                return false; // prevent form submission if used inside a submit handler
            }

            $.ajax({
                url: "{{route('post_login')}}",
                method: 'POST',
                data: {
                    _token: "{{csrf_token()}}",
                    email: email.val(), 
                    mobile_number: phone.val(),
                    password: password.val(),
                    trip_login: tripLogin
                },
                success: function(response) {
                    if (response.status == true) {
                        $('#login-modal').modal('hide');
                        Swal.fire(
                            'Logged in successfully!',
                            'success'
                        );
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    } else {
                        $('#login-modal').modal('hide');
                        Swal.fire(
                            'Failed!',
                            response.message,
                            'error'
                        );
                    }
                }
            });
        });

        $(document).on('click', '#submit-trip-signup', function(e) {
            e.preventDefault();
            var tripSignup = 'trip_signup';

            var full_name = $('#signup_name');
            var email = $('#signup_email');
            var phone = $('#signup_mobile_number');
            var password = $('#signup_password');
            
            let isValid = true;

            $('.signup-input-field').each(function() {
                let _thisSignupInput = $(this);
                let val = _thisSignupInput.val();
                let errorMessage = _thisSignupInput.closest('.mb-3').find('small.text-danger');

                if (val === null || val.trim() === '') {
                    _thisSignupInput.css('border', '1px solid red');
                    errorMessage.removeClass('d-none'); // Show "Required"
                    isValid = false;
                } else {
                    _thisSignupInput.css('border', '');
                    errorMessage.addClass('d-none'); // Hide "Required"
                }
            });

            if (!$('.privacy-policy-checkbox').is(':checked')) {
              $('.privacy-policy-checkbox').css('border', '1px solid red');
              $('.privacy-policy-checkbox').closest('.mb-3').find('small.text-danger').removeClass('d-none'); // Show "Required"
              isValid = false;
            }

            if (!isValid) {
                return false; // prevent form submission if used inside a submit handler
            }

            $.ajax({
                url: "{{route('post_register')}}",
                method: 'POST',
                data: {
                    _token: "{{csrf_token()}}",
                    name: full_name.val(), 
                    email: email.val(), 
                    mobile_number: phone.val(), 
                    password: password.val(), 
                    trip_signup: tripSignup
                },
                success: function(response) {
                    if (response.status == true) {
                        $('#signup-modal').modal('hide');
                        Swal.fire(
                            'Account created successfully!',
                            'success'
                        );
                        if (response.resp == 'login_now') {
                            setTimeout(() => {
                                $('#login-modal').modal('show');
                            }, 200);
                        }
                    } else {
                        // $('#signup-modal').modal('hide');
                        // Swal.fire(
                        //     'Failed!',
                        //     response.message,
                        //     'error'
                        // );
                    }
                }
            });
        });

      });
    </script>
    @yield('_scripts')
  </body>
</html>