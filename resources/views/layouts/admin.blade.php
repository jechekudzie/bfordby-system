<!DOCTYPE html>
<html lang="en-US" dir="ltr" data-navigation-type="horizontal" data-navbar-horizontal-shape="default">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - @yield('title', 'Admin')</title>

    <!-- Favicons -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicons/favicon.ico') }}">
    
    <!-- Stylesheets -->
    <link href="{{ asset('vendors/simplebar/simplebar.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/theme.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/user.min.css') }}" rel="stylesheet">
    @stack('css')
</head>

<body>
    <main class="main" id="top">
        <!-- Horizontal Navbar -->
        <nav class="navbar navbar-top fixed-top navbar-expand-lg" id="navbarTop">
            <div class="navbar-logo">
                <button class="btn navbar-toggler navbar-toggler-humburger-icon hover-bg-transparent" type="button" 
                        data-bs-toggle="collapse" data-bs-target="#navbarTopCollapse">
                    <span class="navbar-toggle-icon"><span class="toggle-line"></span></span>
                </button>
                <a class="navbar-brand me-1 me-sm-3" href="{{ route('admin.dashboard') }}">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('assets/img/icons/logo.png') }}" alt="logo" width="27" />
                        <h5 class="logo-text ms-2 d-none d-sm-block">{{ config('app.name') }}</h5>
                    </div>
                </a>
            </div>

            <!-- Navigation Items -->
            <div class="collapse navbar-collapse navbar-top-collapse order-1 order-lg-0 justify-content-center" 
                 id="navbarTopCollapse">
                <ul class="navbar-nav navbar-nav-top" data-dropdown-on-hover="data-dropdown-on-hover">
                    <!-- Home -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle lh-1" href="#!" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                            <span class="uil fs-8 me-2 uil-chart-pie"></span>Home
                        </a>
                        <ul class="dropdown-menu navbar-dropdown-caret">
                            <li>
                                <a class="dropdown-item" href="#">
                                    <div class="dropdown-item-wrapper">
                                        <span class="me-2 uil" data-feather="shopping-cart"></span>Dashboard
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Students - Now positioned right after Home -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle lh-1" href="#!" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                            <span class="uil fs-8 me-2 uil-graduation-cap"></span>Students
                        </a>
                        <ul class="dropdown-menu navbar-dropdown-caret">
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.students.index') }}">
                                    <div class="dropdown-item-wrapper">
                                        <span class="me-2 uil" data-feather="list"></span>Student List
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.students.create') }}">
                                    <div class="dropdown-item-wrapper">
                                        <span class="me-2 uil" data-feather="user-plus"></span>Add Student
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <div class="dropdown-item-wrapper">
                                        <span class="me-2 uil" data-feather="file-text"></span>Student Records
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <div class="dropdown-item-wrapper">
                                        <span class="me-2 uil" data-feather="award"></span>Student Performance
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <div class="dropdown-item-wrapper">
                                        <span class="me-2 uil" data-feather="calendar"></span>Attendance
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Apps -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle lh-1" href="#!" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                            <span class="uil fs-8 me-2 uil-cube"></span>Apps
                        </a>
                        <ul class="dropdown-menu navbar-dropdown-caret">
                            <!-- CRM -->
                            <li class="dropdown">
                                <a class="dropdown-item dropdown-toggle" id="CRM" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                    <div class="dropdown-item-wrapper">
                                        <span class="uil fs-8 uil-angle-right lh-1 dropdown-indicator-icon"></span>
                                        <span><span class="me-2 uil" data-feather="phone"></span>CRM</span>
                                    </div>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="dropdown-item-wrapper">
                                                <span class="me-2 uil"></span>Analytics
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="dropdown-item-wrapper">
                                                <span class="me-2 uil"></span>Deals
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- Project Management -->
                            <li class="dropdown">
                                <a class="dropdown-item dropdown-toggle" id="project-management" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                    <div class="dropdown-item-wrapper">
                                        <span class="uil fs-8 uil-angle-right lh-1 dropdown-indicator-icon"></span>
                                        <span><span class="me-2 uil" data-feather="clipboard"></span>Project management</span>
                                    </div>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="dropdown-item-wrapper">
                                                <span class="me-2 uil"></span>Projects
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="dropdown-item-wrapper">
                                                <span class="me-2 uil"></span>Tasks
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- Chat -->
                            <li>
                                <a class="dropdown-item" href="#">
                                    <div class="dropdown-item-wrapper">
                                        <span class="me-2 uil" data-feather="message-square"></span>Chat
                                    </div>
                                </a>
                            </li>

                            <!-- Email -->
                            <li class="dropdown">
                                <a class="dropdown-item dropdown-toggle" id="email" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                    <div class="dropdown-item-wrapper">
                                        <span class="uil fs-8 uil-angle-right lh-1 dropdown-indicator-icon"></span>
                                        <span><span class="me-2 uil" data-feather="mail"></span>Email</span>
                                    </div>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="dropdown-item-wrapper">
                                                <span class="me-2 uil"></span>Inbox
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="dropdown-item-wrapper">
                                                <span class="me-2 uil"></span>Compose
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- Pages -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle lh-1" href="#!" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                    <span class="uil fs-8 me-2 uil-files-landscapes-alt"></span>Pages
                                </a>
                                <ul class="dropdown-menu navbar-dropdown-caret">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="dropdown-item-wrapper">
                                                <span class="me-2 uil" data-feather="settings"></span>Settings
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="dropdown-item-wrapper">
                                                <span class="me-2 uil" data-feather="user"></span>Profile
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>

            <!-- After the main navigation items, add: -->
            <ul class="navbar-nav navbar-nav-icons flex-row">
                <li class="nav-item">
                    <div class="theme-control-toggle fa-icon-wait px-2">
                        <input class="form-check-input ms-0 theme-control-toggle-input" type="checkbox" data-theme-control="phoenixTheme" value="dark" id="themeControlToggle" />
                        <label class="mb-0 theme-control-toggle-label theme-control-toggle-light" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch theme">
                            <span class="icon" data-feather="moon"></span>
                        </label>
                        <label class="mb-0 theme-control-toggle-label theme-control-toggle-dark" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch theme">
                            <span class="icon" data-feather="sun"></span>
                        </label>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                        <span data-feather="bell" style="height:20px;width:20px;"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end notification-dropdown-menu py-0 shadow border navbar-dropdown-caret" id="navbarDropdownNotfication" aria-labelledby="navbarDropdownNotfication">
                        <div class="card position-relative border-0">
                            <div class="card-header p-2">
                                <div class="d-flex justify-content-between">
                                    <h5 class="text-body-emphasis mb-0">Notifications</h5>
                                    <button class="btn btn-link p-0 fs-9 fw-normal" type="button">Mark all as read</button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="scrollbar-overlay" style="height: 27rem;">
                                    <div class="border-300">
                                        <div class="px-2 py-3 px-sm-3 border-300 notification-card position-relative read border-bottom">
                                            <div class="d-flex align-items-center justify-content-between position-relative">
                                                <div class="d-flex">
                                                    <div class="avatar avatar-m status-online me-3">
                                                        <img class="rounded-circle" src="../assets/img/team/40x40/30.webp" alt="" />
                                                    </div>
                                                    <div class="flex-1 me-sm-3">
                                                        <h4 class="fs-9 text-body-emphasis">Jessie Samson</h4>
                                                        <p class="fs-9 text-body-highlight mb-2 mb-sm-3 fw-normal">
                                                            <span class='me-1 fs-10'>💬</span>
                                                            Mentioned you in a comment.<span class="ms-2 text-body-quaternary text-opacity-75 fw-bold fs-10">10m</span>
                                                        </p>
                                                        <p class="text-body-secondary fs-9 mb-0">
                                                            <span class="me-1 fas fa-clock"></span>
                                                            <span class="fw-bold">10:41 AM </span>
                                                            August 7,2021
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="d-none d-sm-block">
                                                    <button class="btn fs-10 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent">
                                                        <span class="fas fa-ellipsis-h fs-10 text-body"></span>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end py-2">
                                                        <a class="dropdown-item" href="#!">Mark as unread</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer p-0 border-top border-translucent border-0">
                                <div class="my-2 text-center fw-bold fs-9">
                                    <a class="fw-bold" href="pages/notifications.html">Notification history</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <!-- Profile Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link pe-0 ps-2" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="avatar avatar-l ">
                            <img class="rounded-circle" src="{{ asset('assets/img/team/40x40/57.webp') }}" alt="" />
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-profile shadow border" aria-labelledby="navbarDropdownUser">
                        <div class="card position-relative border-0">
                            <div class="card-body p-0">
                                <div class="text-center pt-4 pb-3">
                                    <div class="avatar avatar-xl">
                                        <img class="rounded-circle" src="{{ asset('assets/img/team/72x72/57.webp') }}" alt="" />
                                    </div>
                                    <h6 class="mt-2 text-body-emphasis">Jerry Seinfield</h6>
                                </div>
                                <div class="mb-3 mx-3">
                                    <input class="form-control form-control-sm" id="statusUpdateInput" type="text" placeholder="Update your status" />
                                </div>
                            </div>
                            <div class="overflow-auto scrollbar" style="height: 10rem;">
                                <ul class="nav d-flex flex-column mb-2 pb-1">
                                    <li class="nav-item">
                                        <a class="nav-link px-3" href="#!">
                                            <span class="me-2 text-body" data-feather="user"></span>
                                            <span>Profile</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link px-3" href="#!">
                                            <span class="me-2 text-body" data-feather="pie-chart"></span>
                                            <span>Dashboard</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link px-3" href="#!">
                                            <span class="me-2 text-body" data-feather="settings"></span>
                                            <span>Settings & Privacy</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link px-3" href="#!">
                                            <span class="me-2 text-body" data-feather="help-circle"></span>
                                            <span>Help Center</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link px-3" href="#!">
                                            <span class="me-2 text-body" data-feather="globe"></span>
                                            <span>Language</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-footer p-0 border-top">
                                <ul class="nav d-flex flex-column my-3">
                                    <li class="nav-item">
                                        <a class="nav-link px-3" href="#!">
                                            <span class="me-2 text-body" data-feather="user-plus"></span>
                                            <span>Add another account</span>
                                        </a>
                                    </li>
                                </ul>
                                <hr />
                                <div class="px-3 mb-3">
                                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                        @csrf
                                        <a class="btn btn-phoenix-secondary d-flex flex-center w-100" href="{{ route('logout') }}" 
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <span class="me-2" data-feather="log-out"></span>
                                            Sign out
                                        </a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="content">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="footer position-absolute">
            <div class="row g-0 justify-content-between align-items-center h-100">
                <div class="col-12 col-sm-auto text-center">
                    <p class="mb-0 mt-2 mt-sm-0 text-body">
                        © {{ date('Y') }} {{ config('app.name') }}
                    </p>
                </div>
            </div>
        </footer>
    </main>

    <!-- Scripts -->
    <script src="{{ asset('vendors/popper/popper.min.js') }}"></script>
    <script src="{{ asset('vendors/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendors/anchorjs/anchor.min.js') }}"></script>
    <script src="{{ asset('vendors/is/is.min.js') }}"></script>
    <script src="{{ asset('vendors/fontawesome/all.min.js') }}"></script>
    <script src="{{ asset('vendors/lodash/lodash.min.js') }}"></script>
    <script src="{{ asset('assets/js/theme.js') }}"></script>
    @stack('scripts')
</body>

</html>