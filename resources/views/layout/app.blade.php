<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>Layout Empty - Vuexy - Bootstrap HTML admin template</title>
    <link rel="apple-touch-icon" href='{{asset("app-assets/images/ico/apple-icon-120.png")}}'>
    <link rel="shortcut icon" type="image/x-icon" href='{{asset("app-assets/images/ico/favicon.ico")}}'>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href='{{asset("app-assets/vendors/css/vendors.min.css")}}'>
    @yield('vendor-css')
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href='{{asset("app-assets/css/bootstrap.css")}}'> 
    <link rel="stylesheet" type="text/css" href='{{asset("app-assets/css/bootstrap-extended.css")}}'>
    <link rel="stylesheet" type="text/css" href='{{asset("app-assets/css/colors.css")}}'>
    <link rel="stylesheet" type="text/css" href='{{asset("app-assets/css/components.css")}}'>
    <link rel="stylesheet" type="text/css" href='{{asset("app-assets/css/themes/dark-layout.css")}}'>
    <link rel="stylesheet" type="text/css" href='{{asset("app-assets/css/themes/bordered-layout.css")}}'>
    <link rel="stylesheet" type="text/css" href='{{asset("app-assets/css/themes/semi-dark-layout.css")}}'>

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href='{{asset("app-assets/css/core/menu/menu-types/vertical-menu.css")}}'>
    @yield('page-css')
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href='{{asset("assets/css/style.css")}}'>
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="">

    <!-- BEGIN: Header-->
    <nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow">
        <div class="navbar-container d-flex content">
            
            <ul class="nav navbar-nav align-items-center ml-auto">
               
                
                @php
                    $unreadNotifications = Auth::user()->unreadNotifications;
                    $unreadCount = $unreadNotifications->count();
                @endphp
                <li class="nav-item dropdown dropdown-notification mr-25">
                    <a class="nav-link" href="javascript:void(0);" data-toggle="dropdown">
                        <i class="ficon" data-feather="bell"></i>
                        @if($unreadCount > 0)
                            <span class="badge badge-pill badge-danger badge-up">{{ $unreadCount }}</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                        <li class="dropdown-menu-header">
                            <div class="dropdown-header d-flex">
                                <h4 class="notification-title mb-0 mr-auto">Notifications</h4>
                                <div class="badge badge-pill badge-light-primary">{{ $unreadCount }} New</div>
                            </div>
                        </li>
                        <li class="scrollable-container media-list">
                            @forelse($unreadNotifications as $notification)
                                @php
                                    $data = $notification->data;
                                    $icon = $data['icon'] ?? 'bell';
                                    $type = $data['type'] ?? 'info';
                                    $bgClass = 'bg-light-primary';
                                    if ($type === 'danger') $bgClass = 'bg-light-danger';
                                    elseif ($type === 'warning') $bgClass = 'bg-light-warning';
                                    elseif ($type === 'success') $bgClass = 'bg-light-success';
                                @endphp
                                <a class="d-flex" href="{{ $data['link'] ?? 'javascript:void(0)' }}">
                                    <div class="media d-flex align-items-start">
                                        <div class="media-left">
                                            <div class="avatar {{ $bgClass }}">
                                                <div class="avatar-content">
                                                    <i class="avatar-icon" data-feather="{{ $icon }}"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <p class="media-heading">
                                                <span class="font-weight-bolder">{{ $data['title'] }}</span>
                                            </p>
                                            <small class="notification-text">{{ $data['message'] }}</small>
                                            <small class="d-block text-muted mt-25">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="media d-flex align-items-center justify-content-center p-2">
                                    <p class="mb-0 text-muted">No new notifications</p>
                                </div>
                            @endforelse
                        </li>
                        @if($unreadCount > 0)
                            <li class="dropdown-menu-footer">
                                <form action="{{ route('dashboard.notifications.mark-all-read') }}" method="POST" class="d-block px-1 py-50">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-block btn-sm">Mark all as read</button>
                                </form>
                            </li>
                        @endif
                    </ul>
                </li>
                <li class="nav-item dropdown dropdown-user">
                    <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="user-nav d-sm-flex d-none">
                            <span class="user-name font-weight-bolder">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</span>
                            <span class="user-status">{{ ucfirst(Auth::user()->role) }}</span>
                        </div>
                        <span class="avatar">
                            <img class="round" src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->firstname . ' ' . Auth::user()->lastname) . '&color=7F9CF5&background=EBF4FF' }}" alt="avatar" height="40" width="40">
                            <span class="avatar-status-online"></span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">
                        <a class="dropdown-item" href="{{ route('dashboard.profile.edit') }}"><i class="mr-50" data-feather="user"></i> Profile</a>
                        @if(Auth::user()->hasRole('super admin'))
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('dashboard.settings.index') }}"><i class="mr-50" data-feather="settings"></i> Settings</a>
                        @endif
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}"><i class="mr-50" data-feather="power"></i> Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <ul class="main-search-list-defaultlist d-none">
        <li class="d-flex align-items-center"><a href="javascript:void(0);">
                <h6 class="section-label mt-75 mb-0">Files</h6>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100" href="app-file-manager.html">
                <div class="d-flex">
                    <div class="mr-75"><img src="../../../app-assets/images/icons/xls.png" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Two new item submitted</p><small class="text-muted">Marketing Manager</small>
                    </div>
                </div><small class="search-data-size mr-50 text-muted">&apos;17kb</small>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100" href="app-file-manager.html">
                <div class="d-flex">
                    <div class="mr-75"><img src="../../../app-assets/images/icons/jpg.png" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">52 JPG file Generated</p><small class="text-muted">FontEnd Developer</small>
                    </div>
                </div><small class="search-data-size mr-50 text-muted">&apos;11kb</small>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100" href="app-file-manager.html">
                <div class="d-flex">
                    <div class="mr-75"><img src="../../../app-assets/images/icons/pdf.png" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">25 PDF File Uploaded</p><small class="text-muted">Digital Marketing Manager</small>
                    </div>
                </div><small class="search-data-size mr-50 text-muted">&apos;150kb</small>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100" href="app-file-manager.html">
                <div class="d-flex">
                    <div class="mr-75"><img src="../../../app-assets/images/icons/doc.png" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Anna_Strong.doc</p><small class="text-muted">Web Designer</small>
                    </div>
                </div><small class="search-data-size mr-50 text-muted">&apos;256kb</small>
            </a></li>
        <li class="d-flex align-items-center"><a href="javascript:void(0);">
                <h6 class="section-label mt-75 mb-0">Members</h6>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100" href="app-user-view.html">
                <div class="d-flex align-items-center">
                    <div class="avatar mr-75"><img src="../../../app-assets/images/portrait/small/avatar-s-8.jpg" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">John Doe</p><small class="text-muted">UI designer</small>
                    </div>
                </div>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100" href="app-user-view.html">
                <div class="d-flex align-items-center">
                    <div class="avatar mr-75"><img src="../../../app-assets/images/portrait/small/avatar-s-1.jpg" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Michal Clark</p><small class="text-muted">FontEnd Developer</small>
                    </div>
                </div>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100" href="app-user-view.html">
                <div class="d-flex align-items-center">
                    <div class="avatar mr-75"><img src="../../../app-assets/images/portrait/small/avatar-s-14.jpg" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Milena Gibson</p><small class="text-muted">Digital Marketing Manager</small>
                    </div>
                </div>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100" href="app-user-view.html">
                <div class="d-flex align-items-center">
                    <div class="avatar mr-75"><img src="../../../app-assets/images/portrait/small/avatar-s-6.jpg" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Anna Strong</p><small class="text-muted">Web Designer</small>
                    </div>
                </div>
            </a></li>
    </ul>
    <ul class="main-search-list-defaultlist-other-list d-none">
        <li class="auto-suggestion justify-content-between"><a class="d-flex align-items-center justify-content-between w-100 py-50">
                <div class="d-flex justify-content-start"><span class="mr-75" data-feather="alert-circle"></span><span>No results found.</span></div>
            </a></li>
    </ul>
    <!-- END: Header-->

    @include('partials.sidebar')

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
             @yield('content')
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">COPYRIGHT &copy; 2020<a class="ml-25" href="https://1.envato.market/pixinvent_portfolio" target="_blank">Pixinvent</a><span class="d-none d-sm-inline-block">, All rights Reserved</span></span><span class="float-md-right d-none d-md-block">Hand-crafted & Made with<i data-feather="heart"></i></span></p>
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
    <!-- END: Footer-->


    <!-- BEGIN: Vendor JS-->
    <script src='{{asset("app-assets/vendors/js/vendors.min.js")}}'></script>
    @yield('vendor-scripts')
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src='{{asset("app-assets/js/core/app-menu.js")}}'></script>
    <script src='{{asset("app-assets/js/core/app.js")}}'></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    @yield('page-scripts')
    <!-- END: Page JS-->

    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>
    <script>
        (function () {
            var selectors = [
                '.content-header',
                '.card',
                '.table-responsive',
                '.breadcrumb-wrapper',
                '.alert'
            ];

            var nodes = [];
            selectors.forEach(function (sel) {
                try {
                    nodes = nodes.concat(Array.prototype.slice.call(document.querySelectorAll(sel)));
                } catch (e) {}
            });

            nodes.forEach(function (el) {
                if (!el.classList.contains('ll-reveal')) {
                    el.classList.add('ll-reveal');
                }
            });

            if (!('IntersectionObserver' in window)) {
                nodes.forEach(function (el) {
                    el.classList.add('ll-visible');
                });
                return;
            }

            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('ll-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });

            nodes.forEach(function (el) {
                observer.observe(el);
            });
        })();
    </script>
</body>
<!-- END: Body-->

</html>
