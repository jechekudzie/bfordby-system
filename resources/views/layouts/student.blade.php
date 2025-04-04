<!DOCTYPE html>
<html lang="en-US" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>@yield('title', 'BLACKFORDBY COLLEGE - Student Portal')</title>


    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicons/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicons/favicon.ico">
    <link rel="manifest" href="assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="assets/img/favicons/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">
    <script src="{{ asset('/vendors/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('/vendors/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>


    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&amp;display=swap"
        rel="stylesheet">
    <link href="{{ asset('/vendors/simplebar/simplebar.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link href="{{ asset('assets/css/theme-rtl.min.css') }}" type="text/css" rel="stylesheet" id="style-rtl">
    <link href="{{ asset('assets/css/theme.min.css') }}" type="text/css" rel="stylesheet" id="style-default">
    <link href="{{ asset('assets/css/user-rtl.min.css') }}" type="text/css" rel="stylesheet"
        id="user-style-rtl">
    <link href="{{ asset('assets/css/user.min.css') }}" type="text/css" rel="stylesheet"
        id="user-style-default">

    <script>
        var phoenixIsRTL = window.config.config.phoenixIsRTL;
        if (phoenixIsRTL) {
            var linkDefault = document.getElementById('style-default');
            var userLinkDefault = document.getElementById('user-style-default');
            linkDefault.setAttribute('disabled', true);
            userLinkDefault.setAttribute('disabled', true);
            document.querySelector('html').setAttribute('dir', 'rtl');
        } else {
            var linkRTL = document.getElementById('style-rtl');
            var userLinkRTL = document.getElementById('user-style-rtl');
            linkRTL.setAttribute('disabled', true);
            userLinkRTL.setAttribute('disabled', true);
        }
    </script>
    <link href="{{ asset('/vendors/leaflet/leaflet.css') }}" rel="stylesheet">
    <link href="{{ asset('/vendors/leaflet.markercluster/MarkerCluster.css') }}" rel="stylesheet">
    <link href="{{ asset('/vendors/leaflet.markercluster/MarkerCluster.Default.css') }}" rel="stylesheet">

    <link href="{{ asset('vendors/flatpickr/flatpickr.min.css') }}" rel="stylesheet">


    <!-- Gijgo config file -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <style>
        .gj-tree-bootstrap-4 ul.gj-list-bootstrap li.active {
            background-color: gray !important;
        }

        .nav-link.active {
            color: #2c7be5 !important;
            /* Primary blue color */
            font-weight: 600;
            background-color: rgba(44, 123, 229, 0.1);
        }

        .nav-link.active .nav-link-text {
            color: #2c7be5 !important;
        }

        .nav-category-header {
            padding: 0.5rem 1rem 0.25rem;
        }

        .nav-category-text {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #6c757d;
        }
    </style>
    @stack('head')
</head>


<body>

    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
        <nav class="navbar navbar-vertical navbar-expand-lg">
            <script>
                var navbarStyle = window.config.config.phoenixNavbarStyle;
                if (navbarStyle && navbarStyle !== 'transparent') {
                    document.querySelector('body').classList.add(`navbar-${navbarStyle}`);
                }
            </script>
            <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
                <div class="navbar-vertical-content">
                    <ul class="navbar-nav flex-column" id="navbarVerticalNav">
                        <!-- Student Portal Links -->
                        <li class="nav-item">
                            <p class="navbar-vertical-label">Student Portal</p>
                            <hr class="navbar-vertical-line" />
                            <div class="nav-item-wrapper">
                                <a class="nav-link dropdown-indicator label-1 {{ Request::routeIs('student.*') ? 'active' : '' }}" href="#student" role="button"
                                    data-bs-toggle="collapse" aria-expanded="{{ Request::routeIs('student.*') ? 'true' : 'false' }}" aria-controls="student">
                                    <div class="d-flex align-items-center">
                                        <div class="dropdown-indicator-icon">
                                            <span class="fas fa-caret-right"></span>
                                        </div>
                                        <span class="nav-link-icon">
                                            <span class="fas fa-user-graduate"></span>
                                        </span>
                                        <span class="nav-link-text">STUDENT PORTAL</span>
                                    </div>
                                </a>

                                <div class="parent-wrapper label-1">

                                    <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse"
                                        id="student">
                                        <!-- Dashboard -->
                                        <li class="nav-item">
                                            <div class="nav-category-header">
                                                <span class="nav-category-text">My Dashboard</span>
                                            </div>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link {{ Request::routeIs('student.dashboard') ? 'active' : '' }}" href="{{ route('student.dashboard') }}">
                                                <span class="nav-link-text">Dashboard</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link {{ Request::routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                                                <span class="nav-link-text">My Profile</span>
                                            </a>
                                        </li>

                                        <!-- Academic -->
                                        <li class="nav-item mt-3">
                                            <div class="nav-category-header">
                                                <span class="nav-category-text">Academic</span>
                                            </div>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link {{ Request::routeIs('students.assessments.*') ? 'active' : '' }}" href="{{ route('students.assessments.list') }}">
                                                <span class="nav-link-text">My Assessments</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link {{ Request::routeIs('student.library.*') ? 'active' : '' }}" href="{{ route('student.library.index') }}">
                                                <span class="nav-link-text">E-Library</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link {{ Request::routeIs('student.transcript.*') ? 'active' : '' }}" href="{{ route('student.transcript.show') }}">
                                                <span class="nav-link-text">Academic Transcript</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="navbar-vertical-footer">
                <button class="btn navbar-vertical-toggle border-0 fw-semi-bold w-100" data-bs-toggle="tooltip" data-bs-placement="left" title="Collapse sidebar">
                    <span class="uil uil-left-arrow-to-left"></span>
                    <span class="uil uil-arrow-from-right navbar-vertical-toggle-icon"></span>
                    <span class="navbar-vertical-toggle-text ms-2">Collapse</span>
                </button>
            </div>
        </nav>
        <nav class="navbar navbar-top navbar-expand" id="navbarDefault">
            <div class="collapse navbar-collapse justify-content-between">
                <div class="navbar-logo">
                    <button class="btn navbar-toggler navbar-toggler-humburger-icon hover-bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse" aria-controls="navbarVerticalCollapse" aria-expanded="false" aria-label="Toggle Navigation">
                        <span class="navbar-toggle-icon"><span class="toggle-line"></span></span>
                    </button>
                    <a class="navbar-brand me-1 me-sm-3" href="{{ route('student.dashboard') }}">
                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('assets/img/icons/logo.png') }}" alt="phoenix" width="27">
                                <p class="logo-text ms-2 d-none d-sm-block">BLACKFORDBY</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="search-box d-none navbar-top-search-box" style="max-width: 30rem;">
                    <form class="position-relative" data-bs-toggle="search" data-bs-display="static">
                        <input class="form-control search-input fuzzy-search rounded-pill form-control-sm" type="search" placeholder="Search..." aria-label="Search" data-bs-theme="dark">
                        <span class="fas fa-search search-box-icon"></span>
                    </form>
                </div>
                <ul class="navbar-nav navbar-nav-icons flex-row">
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-auto-close="outside">
                            <div class="avatar avatar-xl">
                                <span class="avatar-name rounded-circle">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-profile shadow border border-300" data-bs-theme="dark" aria-labelledby="navbarDropdownUser">
                            <div class="card position-relative border-0">
                                <div class="card-body p-0">
                                    <div class="text-center pt-4 pb-3">
                                        <div class="avatar avatar-xl">
                                            <span class="avatar-name rounded-circle">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                        </div>
                                        <h6 class="mt-2 text-white">{{ auth()->user()->name }}</h6>
                                    </div>
                                </div>
                                <div class="overflow-auto scrollbar">
                                    <ul class="nav d-flex flex-column mb-2 pb-1">
                                        <li class="nav-item">
                                            <a class="nav-link px-3" href="{{ route('profile.edit') }}">
                                                <span class="me-2 text-900" data-feather="user"></span>
                                                <span>Profile</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-footer p-0 border-top">
                                    <div class="px-3 py-3">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button class="btn btn-phoenix-secondary d-flex flex-center w-100" type="submit">
                                                <span class="me-2" data-feather="log-out"></span>
                                                Sign out
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="content">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
    <script src="{{ asset('/vendors/popper/popper.min.js') }}"></script>
    <script src="{{ asset('/vendors/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/vendors/anchorjs/anchor.min.js') }}"></script>
    <script src="{{ asset('/vendors/is/is.min.js') }}"></script>
    <script src="{{ asset('/vendors/fontawesome/all.min.js') }}"></script>
    <script src="{{ asset('/vendors/lodash/lodash.min.js') }}"></script>
    <script src="{{ asset('vendors/list.js/list.min.js') }}"></script>
    <script src="{{ asset('assets/js/theme.js') }}"></script>
    <script src="{{ asset('/vendors/dayjs/dayjs.min.js') }}"></script>
    <script src="{{ asset('vendors/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('vendors/choices/choices.min.js') }}"></script>
    <script src="{{ asset('vendors/dropzone/dropzone.min.js') }}"></script>
    <script src="{{ asset('vendors/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('vendors/feather-icons/feather.min.js') }}"></script>

    @stack('scripts')
</body>

</html> 