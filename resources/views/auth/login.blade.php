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
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{asset('assets/')}}"
  data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>{{$header_title}}</title>

  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{asset('assets/img/favicon/favicon.ico')}}" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" />

  <!-- Icons. Uncomment required icon fonts -->
  <link rel="stylesheet" href="{{asset('assets/vendor/fonts/boxicons.css')}}" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="{{asset('assets/vendor/css/core.css')}}" class="template-customizer-core-css" />
  <link rel="stylesheet" href="{{asset('assets/vendor/css/theme-default.css')}}" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="{{asset('assets/css/demo.css')}}" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="{{asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />

  <link rel="stylesheet" href="{{asset('css/styles.css')}}">

  <!-- Page CSS -->
  <!-- Page -->
  <link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}" />
  <!-- Helpers -->
  <script src="{{asset('assets/vendor/js/helpers.js')}}"></script>

  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <script src="{{asset('assets/js/config.js')}}"></script>

  <style>
    .tripplanner-text {
      font-family: 'AlexBrush', sans-serif;
    }
  </style>

</head>

<body>
  <!-- Content -->
  <!-- Navbar -->
  @include('layout.custom-app-navbar', ['login' => 'login'])

  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">
        <!-- Register -->
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
            <form method="POST" class="mb-3" action="{{route('post_login')}}">
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
                </div>
              </div>
              <div class="email-address-card">
                <div class="mb-3">
                  <label for="email" class="form-label">{{ __('Email Address') }}</label>
                  <input
                    type="email"
                    class="form-control bg-light"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    required
                    value="{{old('email')}}" />
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
                    class="form-control"
                    name="password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    aria-describedby="password"
                    required />
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                </div>
              </div>
              <div class="mb-3">
                <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
              </div>
              <div class="mb-3 text-center">
                Don't have account? <a href="{{ route('register') }}">Sign up</a>
              </div>
            </form>
          </div>
        </div>
        <!-- /Register -->
      </div>
    </div>
  </div>

  <!-- / Content -->

  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->
  <script src="{{asset('assets/vendor/libs/jquery/jquery.js')}}"></script>
  <script src="{{asset('assets/vendor/libs/popper/popper.js')}}"></script>
  <script src="{{asset('assets/vendor/js/bootstrap.js')}}"></script>
  <script src="{{asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>

  <script src="{{asset('assets/vendor/js/menu.js')}}"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
        const signInMethod = document.getElementById('sign_in_method');
        const mobileCard = document.querySelector('.mobile-number-card');
        const emailCard = document.querySelector('.email-address-card');
        const mobileInput = document.getElementById('mobile_number');
        const emailInput = document.getElementById('email');

        if (signInMethod) {
            signInMethod.addEventListener('change', function () {
                if (this.value === 'mobile_number') {
                    mobileCard.classList.remove('d-none');
                    mobileInput.setAttribute('required', true);
                    emailCard.classList.add('d-none');
                    emailInput.removeAttribute('required');
                } else if (this.value === 'email_address') {
                    emailCard.classList.remove('d-none');
                    emailInput.setAttribute('required', true);
                    mobileCard.classList.add('d-none');
                    mobileInput.removeAttribute('required');
                }
            });
        }
    });
  </script>
  <!-- endbuild -->

  <!-- Vendors JS -->

  <!-- Main JS -->
  <script src="{{asset('assets/js/main.js')}}"></script>

  <!-- Page JS -->

  <!-- Place this tag in your head or just before your close body tag. -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>