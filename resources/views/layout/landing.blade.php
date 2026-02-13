<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">

    <!--Page Title-->
    <title>Namari - Free Landing Page Template</title>

    <!--Meta Keywords and Description-->
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>

    <!--Favicon-->
    <link rel="shortcut icon" href="{{asset('landing/images/favicon.ico')}}" title="Favicon"/>

    <!-- Main CSS Files -->
    <link rel="stylesheet" href="{{asset('landing/css/style.css')}}">

    <!-- Namari Color CSS -->
    <link rel="stylesheet" href="{{asset('landing/css/namari-color.css')}}">

    <!--Icon Fonts - Font Awesome Icons-->
    <link rel="stylesheet" href="{{asset('landing/css/font-awesome.min.css')}}">

    <!-- Animate CSS-->
    <link href="{{asset('landing/css/animate.css')}}" rel="stylesheet" type="text/css">

    <!--Google Webfonts-->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
</head>
<body>

<!-- Preloader -->
<div id="preloader">
    <div id="status" class="la-ball-triangle-path">
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>
<!--End of Preloader-->

<div class="page-border" data-wow-duration="0.7s" data-wow-delay="0.2s">
    <div class="top-border wow fadeInDown animated" style="visibility: visible; animation-name: fadeInDown;"></div>
    <div class="right-border wow fadeInRight animated" style="visibility: visible; animation-name: fadeInRight;"></div>
    <div class="bottom-border wow fadeInUp animated" style="visibility: visible; animation-name: fadeInUp;"></div>
    <div class="left-border wow fadeInLeft animated" style="visibility: visible; animation-name: fadeInLeft;"></div>
</div>

<div id="wrapper">

    <header id="banner" class="scrollto clearfix" data-enllax-ratio=".5">
        <div id="header" class="nav-collapse">
            <div class="row clearfix">
                <div class="col-1">
                        <!--Logo-->
                        <div id="logo">

                            @php
                                $siteLogo = \App\Models\Setting::get('site_logo');
                                $logoUrl = $siteLogo ? asset(str_replace('public/', 'storage/', $siteLogo)) : null;
                            @endphp

                            <!--Logo that is shown on the banner-->
                            <img src="{{ $logoUrl ?? 'images/logo.png' }}" id="banner-logo" alt="Landing Page"/>
                            <!--End of Banner Logo-->

                            <!--The Logo that is shown on the sticky Navigation Bar-->
                            <img src="{{ $logoUrl ?? 'images/logo-2.png' }}" id="navigation-logo" alt="Landing Page"/>
                            <!--End of Navigation Logo-->

                        </div>
                        <!--End of Logo-->

                    @if(!request()->routeIs('login', 'register', 'password.request', 'password.reset', 'verification.notice'))
                    <aside>
                        @php
                            $facebook = \App\Models\Setting::get('social_facebook');
                            $twitter = \App\Models\Setting::get('social_twitter');
                            $instagram = \App\Models\Setting::get('social_instagram');
                        @endphp

                        <!--Social Icons in Header-->
                        <ul class="social-icons">
                            @if($facebook)
                            <li>
                                <a target="_blank" title="Facebook" href="{{ $facebook }}">
                                    <i class="fa fa-facebook fa-1x"></i><span>Facebook</span>
                                </a>
                            </li>
                            @endif
                            @if($twitter)
                            <li>
                                <a target="_blank" title="Twitter" href="{{ $twitter }}">
                                    <i class="fa fa-twitter fa-1x"></i><span>Twitter</span>
                                </a>
                            </li>
                            @endif
                            @if($instagram)
                            <li>
                                <a target="_blank" title="Instagram" href="{{ $instagram }}">
                                    <i class="fa fa-instagram fa-1x"></i><span>Instagram</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                        <!--End of Social Icons in Header-->

                    </aside>

                    <!--Main Navigation-->
                    <nav id="nav-main">
                        <ul>
                            <li>
                                <a href="/">Home</a>
                            </li>
                            <li>
                                <a href="{{ route('about') }}">About</a>
                            </li>
                            <li>
                                <a href="{{ route('lyric-builder') }}">Lyric Builder</a>
                            </li>
                            <!--<li>
                                <a href="#gallery">Gallery</a>
                            </li>
                            <li>
                                <a href="#services">Services</a>
                            </li>
                            <li>
                                <a href="#testimonials">Testimonials</a>
                            </li>
                            <li>
                                <a href="#clients">Clients</a>
                            </li>-->
                            <li>
                                <a href="#pricing">API Docs</a>
                            </li>
                        </ul>
                    </nav>
                    <!--End of Main Navigation-->

                    <div id="nav-trigger"><span></span></div>
                    <nav id="nav-mobile"></nav>
                    @endif

                </div>
            </div>
        </div><!--End of Header-->
        @yield('content')
    
 <!--Footer-->
 <footer id="landing-footer" class="clearfix">
        <div class="row clearfix">

            <p id="copyright" class="col-2">Made with love by <a href="/">ShapingRain</a></p>

            @if(!request()->routeIs('login', 'register', 'password.request', 'password.reset', 'verification.notice'))
            <!--Social Icons in Footer-->
            <ul class="col-2 social-icons">
                @if(isset($facebook) && $facebook)
                <li>
                    <a target="_blank" title="Facebook" href="{{ $facebook }}">
                        <i class="fa fa-facebook fa-1x"></i><span>Facebook</span>
                    </a>
                </li>
                @endif
                @if(isset($twitter) && $twitter)
                <li>
                    <a target="_blank" title="Twitter" href="{{ $twitter }}">
                        <i class="fa fa-twitter fa-1x"></i><span>Twitter</span>
                    </a>
                </li>
                @endif
                @if(isset($instagram) && $instagram)
                <li>
                    <a target="_blank" title="Instagram" href="{{ $instagram }}">
                        <i class="fa fa-instagram fa-1x"></i><span>Instagram</span>
                    </a>
                </li>
                @endif
            </ul>
            <!--End of Social Icons in Footer-->
            @endif
        </div>
    </footer>
    <!--End of Footer-->

</div>

<!-- Include JavaScript resources -->
<script src="{{asset('landing/js/jquery.1.8.3.min.js')}}"></script>
<script src="{{asset('landing/js/wow.min.js')}}"></script>
<script src="{{asset('landing/js/featherlight.min.js')}}"></script>
<script src="{{asset('landing/js/featherlight.gallery.min.js')}}"></script>
<script src="{{asset('landing/js/jquery.enllax.min.js')}}"></script>
<script src="{{asset('landing/js/jquery.scrollUp.min.js')}}"></script>
<script src="{{asset('landing/js/jquery.easing.min.js')}}"></script>
<script src="{{asset('landing/js/jquery.stickyNavbar.min.js')}}"></script>
<script src="{{asset('landing/js/jquery.waypoints.min.js')}}"></script>
<script src="{{asset('landing/js/images-loaded.min.js')}}"></script>
<script src="{{asset('landing/js/lightbox.min.js')}}"></script>
<script src="{{asset('landing/js/site.js')}}"></script>


</body>
</html>