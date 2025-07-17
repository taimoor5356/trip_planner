@extends('layout.custom-app')
@section('_styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .butns {
        width: auto !important;
        display: flex !important;
        align-items: baseline;
    }
    .adults-kids-button {
        width: 25px;
        height: 25px;
        padding: 0;
        font-size: 20px;
        line-height: 1;
        border-radius: 50% !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
</style>
@endsection

@section('content')
<div class="container flex-grow-1 container-p-y d-flex justify-content-center">
    @include('_messages')
    <div class="card col-md-6 mt-4">
        <div class="card-header p-0" style="margin-bottom: 100px;">
            <div class="banner-image">
                <img src="https://gulmitcontinentalhotel.com/wp-content/uploads/2023/02/Sunrise-on-Rakaposhi-Mountain-Hunza-Valley.jpg" 
                    alt="" 
                    style="height: 140px; width: 100%; object-fit: cover; position: absolute; border-radius: 5px 5px 0px 0px;">
                <div class="img-fluid banner-text" style="position: relative; left: 35px; top: 35px">
                    <div class="d-flex">
                        <i class="menu-icon tf-icons bx bx-box mt-1 text-white"></i><h3 class="text-white">Your Planned Trips</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($records as $record)
                <div class="col-lg-4">
                    <div class="card">
                        @if (!empty($record->itinerary))

                            @if (!empty($record->itinerary?->images))
                                @php
                                $firstImage = $record->itinerary?->images->first();
                                @endphp
                                @if (isset($firstImage))
                                <div class="h-100">
                                    <img src="{{ asset('imgs/itineraries/' . $firstImage->image) }}"
                                        class="img-fluid"
                                        alt=""
                                        style="height: 100px; width: 100%; object-fit: cover; border-radius: 5px 5px 0px 0px;">
                                </div>
                                @endif
                            
                            @endif
                            <div class="card-footer py-1">
                                {{ $record->itinerary?->head_line }}
                            </div>

                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
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
@endsection

@section('_scripts')
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

<script>
    
</script>
@endsection
