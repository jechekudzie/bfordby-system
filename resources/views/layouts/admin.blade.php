<!DOCTYPE html>
<html lang="en-US" dir="ltr" data-navigation-type="horizontal" data-navbar-horizontal-shape="default">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> @yield('title', 'Admin')</title>

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicons/favicon-16x16.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicons/favicon.ico') }}">
    <link rel="manifest" href="{{ asset('assets/img/favicons/manifest.json') }}">
    <meta name="msapplication-TileImage" content="{{ asset('assets/img/favicons/mstile-150x150.png') }}">
    <meta name="theme-color" content="#ffffff">

    <!-- Scripts -->
    <script src="{{ asset('vendors/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <!-- ===============================================-->
    <!--    Stylesheets -->
    <!-- ===============================================-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Simplebar -->
    <link href="{{ asset('vendors/simplebar/simplebar.min.css') }}" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">

    <!-- Theme Styles -->
    <link href="{{ asset('assets/css/theme-rtl.css') }}" type="text/css" rel="stylesheet" id="style-rtl">
    <link href="{{ asset('assets/css/theme.min.css') }}" type="text/css" rel="stylesheet" id="style-default">
    <link href="{{ asset('assets/css/user-rtl.min.css') }}" type="text/css" rel="stylesheet" id="user-style-rtl">
    <link href="{{ asset('assets/css/user.min.css') }}" type="text/css" rel="stylesheet" id="user-style-default">
    <link href="{{ asset('assets/css/navbar-horizontal.css') }}" rel="stylesheet">

    <!-- Add Flatpickr CSS -->
    <link href="{{ asset('vendors/flatpickr/flatpickr.min.css') }}" rel="stylesheet">

    <!-- RTL Mode Detection -->
    <script>
        var phoenixIsRTL = window.config.config.phoenixIsRTL;
        if (phoenixIsRTL) {
            document.getElementById('style-default').setAttribute('disabled', true);
            document.getElementById('user-style-default').setAttribute('disabled', true);
            document.querySelector('html').setAttribute('dir', 'rtl');
        } else {
            document.getElementById('style-rtl').setAttribute('disabled', true);
            document.getElementById('user-style-rtl').setAttribute('disabled', true);
        }
    </script>

    <!-- Leaflet Maps -->
    <link href="{{ asset('vendors/leaflet/leaflet.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/leaflet.markercluster/MarkerCluster.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/leaflet.markercluster/MarkerCluster.Default.css') }}" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    @stack('head')
</head>

<body class="d-flex flex-column min-vh-100"> <!-- Added Bootstrap Flexbox -->

    <main class="flex-grow-1 mt-5 pt-4"> <!-- Main content expands to push footer down -->
        <!-- Horizontal Navbar -->
        <nav class="navbar navbar-top fixed-top navbar-expand-lg" id="navbarTop">
            <div class="navbar-logo">
                <button class="btn navbar-toggler navbar-toggler-humburger-icon hover-bg-transparent" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navbarTopCollapse">
                    <span class="navbar-toggle-icon"><span class="toggle-line"></span></span>
                </button>
                <a class="navbar-brand me-1 me-sm-3" href="{{ route('admin.dashboard') }}">
                    <div class="d-flex align-items-center">
                        <img style="width: 200px;" src="{{ asset('assets/img/icons/logo.png') }}" alt="logo" width="27" />
                        <!-- <h5 class="logo-text ms-2 d-none d-sm-block">{{ config('app.name') }}</h5> -->
                    </div>
                </a>
            </div>

            <!-- Navigation Items -->
            <div class="collapse navbar-collapse navbar-top-collapse order-1 order-lg-0 justify-content-center"
                id="navbarTopCollapse">
                <ul class="navbar-nav navbar-nav-top">
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><span class="uil me-2 uil-home"></span>Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.utilities.index') }}"><span class="uil me-2 uil-home"></span>Utilities</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('students.index') }}"><span class="uil me-2 uil-graduation-cap"></span>Students</a></li>
                   

                    <li class="nav-item dropdown"><a class="nav-link dropdown-toggle lh-1" href="#!" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false"><span class="uil fs-8 me-2 uil-chart-pie"></span>Home</a>
                        <ul class="dropdown-menu navbar-dropdown-caret">
                            <li><a class="dropdown-item" href="../index.html">
                                    <div class="dropdown-item-wrapper"><span class="me-2 uil" data-feather="shopping-cart"></span>E commerce
                                    </div>
                                </a>
                            </li>
                            <li><a class="dropdown-item" href="../dashboard/project-management.html">
                                    <div class="dropdown-item-wrapper"><span class="me-2 uil" data-feather="clipboard"></span>Project management
                                    </div>
                                </a>
                            </li>
                            <li><a class="dropdown-item" href="../dashboard/crm.html">
                                    <div class="dropdown-item-wrapper"><span class="me-2 uil" data-feather="phone"></span>CRM
                                    </div>
                                </a>
                            </li>
                            <li><a class="dropdown-item" href="../dashboard/travel-agency.html">
                                    <div class="dropdown-item-wrapper"><span class="me-2 uil" data-feather="briefcase"></span>Travel agency
                                    </div>
                                </a>
                            </li>
                            <li><a class="dropdown-item" href="../apps/social/feed.html">
                                    <div class="dropdown-item-wrapper"><span class="me-2 uil" data-feather="share-2"></span>Social feed
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>


                </ul>
            </div>

            <ul class="navbar-nav navbar-nav-icons flex-row">
                <li class="nav-item">
                    <div class="theme-control-toggle fa-icon-wait px-2">
                        <input class="form-check-input ms-0 theme-control-toggle-input" type="checkbox" data-theme-control="phoenixTheme" value="dark" id="themeControlToggle" />
                        <label class="mb-0 theme-control-toggle-label theme-control-toggle-light" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Switch theme" style="height:32px;width:32px;"><span class="icon" data-feather="moon"></span></label>
                        <label class="mb-0 theme-control-toggle-label theme-control-toggle-dark" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Switch theme" style="height:32px;width:32px;"><span class="icon" data-feather="sun"></span></label>
                    </div>
                </li>
                <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#searchBoxModal"><span data-feather="search" style="height:19px;width:19px;margin-bottom: 2px;"></span></a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" style="min-width: 2.25rem" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-auto-close="outside"><span class="d-block" style="height:20px;width:20px;"><span data-feather="bell" style="height:20px;width:20px;"></span></span></a>

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
                                    <div class="px-2 px-sm-3 py-3 notification-card position-relative read border-bottom">
                                        <div class="d-flex align-items-center justify-content-between position-relative">
                                            <div class="d-flex">
                                                <div class="avatar avatar-m status-online me-3"><img class="rounded-circle" src="../assets/img/team/40x40/30.webp" alt="" />
                                                </div>
                                                <div class="flex-1 me-sm-3">
                                                    <h4 class="fs-9 text-body-emphasis">Jessie Samson</h4>
                                                    <p class="fs-9 text-body-highlight mb-2 mb-sm-3 fw-normal"><span class='me-1 fs-10'>💬</span>Mentioned you in a comment.<span class="ms-2 text-body-quaternary text-opacity-75 fw-bold fs-10">10m</span></p>
                                                    <p class="text-body-secondary fs-9 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">10:41 AM </span>August 7,2021</p>
                                                </div>
                                            </div>
                                            <div class="dropdown notification-dropdown">
                                                <button class="btn fs-10 btn-sm dropdown-toggle dropdown-caret-none transition-none" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs-10 text-body"></span></button>
                                                <div class="dropdown-menu py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-2 px-sm-3 py-3 notification-card position-relative unread border-bottom">
                                        <div class="d-flex align-items-center justify-content-between position-relative">
                                            <div class="d-flex">
                                                <div class="avatar avatar-m status-online me-3">
                                                    <div class="avatar-name rounded-circle"><span>J</span></div>
                                                </div>
                                                <div class="flex-1 me-sm-3">
                                                    <h4 class="fs-9 text-body-emphasis">Jane Foster</h4>
                                                    <p class="fs-9 text-body-highlight mb-2 mb-sm-3 fw-normal"><span class='me-1 fs-10'>📅</span>Created an event.<span class="ms-2 text-body-quaternary text-opacity-75 fw-bold fs-10">20m</span></p>
                                                    <p class="text-body-secondary fs-9 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">10:20 AM </span>August 7,2021</p>
                                                </div>
                                            </div>
                                            <div class="dropdown notification-dropdown">
                                                <button class="btn fs-10 btn-sm dropdown-toggle dropdown-caret-none transition-none" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs-10 text-body"></span></button>
                                                <div class="dropdown-menu py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-2 px-sm-3 py-3 notification-card position-relative unread border-bottom">
                                        <div class="d-flex align-items-center justify-content-between position-relative">
                                            <div class="d-flex">
                                                <div class="avatar avatar-m status-online me-3"><img class="rounded-circle avatar-placeholder" src="../assets/img/team/40x40/avatar.webp" alt="" />
                                                </div>
                                                <div class="flex-1 me-sm-3">
                                                    <h4 class="fs-9 text-body-emphasis">Jessie Samson</h4>
                                                    <p class="fs-9 text-body-highlight mb-2 mb-sm-3 fw-normal"><span class='me-1 fs-10'>👍</span>Liked your comment.<span class="ms-2 text-body-quaternary text-opacity-75 fw-bold fs-10">1h</span></p>
                                                    <p class="text-body-secondary fs-9 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">9:30 AM </span>August 7,2021</p>
                                                </div>
                                            </div>
                                            <div class="dropdown notification-dropdown">
                                                <button class="btn fs-10 btn-sm dropdown-toggle dropdown-caret-none transition-none" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs-10 text-body"></span></button>
                                                <div class="dropdown-menu py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-2 px-sm-3 py-3 notification-card position-relative unread border-bottom">
                                        <div class="d-flex align-items-center justify-content-between position-relative">
                                            <div class="d-flex">
                                                <div class="avatar avatar-m status-online me-3"><img class="rounded-circle" src="../assets/img/team/40x40/57.webp" alt="" />
                                                </div>
                                                <div class="flex-1 me-sm-3">
                                                    <h4 class="fs-9 text-body-emphasis">Kiera Anderson</h4>
                                                    <p class="fs-9 text-body-highlight mb-2 mb-sm-3 fw-normal"><span class='me-1 fs-10'>💬</span>Mentioned you in a comment.<span class="ms-2 text-body-quaternary text-opacity-75 fw-bold fs-10"></span></p>
                                                    <p class="text-body-secondary fs-9 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">9:11 AM </span>August 7,2021</p>
                                                </div>
                                            </div>
                                            <div class="dropdown notification-dropdown">
                                                <button class="btn fs-10 btn-sm dropdown-toggle dropdown-caret-none transition-none" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs-10 text-body"></span></button>
                                                <div class="dropdown-menu py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-2 px-sm-3 py-3 notification-card position-relative unread border-bottom">
                                        <div class="d-flex align-items-center justify-content-between position-relative">
                                            <div class="d-flex">
                                                <div class="avatar avatar-m status-online me-3"><img class="rounded-circle" src="../assets/img/team/40x40/59.webp" alt="" />
                                                </div>
                                                <div class="flex-1 me-sm-3">
                                                    <h4 class="fs-9 text-body-emphasis">Herman Carter</h4>
                                                    <p class="fs-9 text-body-highlight mb-2 mb-sm-3 fw-normal"><span class='me-1 fs-10'>👤</span>Tagged you in a comment.<span class="ms-2 text-body-quaternary text-opacity-75 fw-bold fs-10"></span></p>
                                                    <p class="text-body-secondary fs-9 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">10:58 PM </span>August 7,2021</p>
                                                </div>
                                            </div>
                                            <div class="dropdown notification-dropdown">
                                                <button class="btn fs-10 btn-sm dropdown-toggle dropdown-caret-none transition-none" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs-10 text-body"></span></button>
                                                <div class="dropdown-menu py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-2 px-sm-3 py-3 notification-card position-relative read ">
                                        <div class="d-flex align-items-center justify-content-between position-relative">
                                            <div class="d-flex">
                                                <div class="avatar avatar-m status-online me-3"><img class="rounded-circle" src="../assets/img/team/40x40/58.webp" alt="" />
                                                </div>
                                                <div class="flex-1 me-sm-3">
                                                    <h4 class="fs-9 text-body-emphasis">Benjamin Button</h4>
                                                    <p class="fs-9 text-body-highlight mb-2 mb-sm-3 fw-normal"><span class='me-1 fs-10'>👍</span>Liked your comment.<span class="ms-2 text-body-quaternary text-opacity-75 fw-bold fs-10"></span></p>
                                                    <p class="text-body-secondary fs-9 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">10:18 AM </span>August 7,2021</p>
                                                </div>
                                            </div>
                                            <div class="dropdown notification-dropdown">
                                                <button class="btn fs-10 btn-sm dropdown-toggle dropdown-caret-none transition-none" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs-10 text-body"></span></button>
                                                <div class="dropdown-menu py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer p-0 border-top border-translucent border-0">
                                <div class="my-2 text-center fw-bold fs-10 text-body-tertiary text-opactity-85"><a class="fw-bolder" href="../pages/notifications.html">Notification history</a></div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" id="navbarDropdownNindeDots" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" data-bs-auto-close="outside" aria-expanded="false">
                        <svg width="16" height="16" viewbox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="2" cy="2" r="2" fill="currentColor"></circle>
                            <circle cx="2" cy="8" r="2" fill="currentColor"></circle>
                            <circle cx="2" cy="14" r="2" fill="currentColor"></circle>
                            <circle cx="8" cy="8" r="2" fill="currentColor"></circle>
                            <circle cx="8" cy="14" r="2" fill="currentColor"></circle>
                            <circle cx="14" cy="8" r="2" fill="currentColor"></circle>
                            <circle cx="14" cy="14" r="2" fill="currentColor"></circle>
                            <circle cx="8" cy="2" r="2" fill="currentColor"></circle>
                            <circle cx="14" cy="2" r="2" fill="currentColor"></circle>
                        </svg></a>

                    <div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-nine-dots shadow border" aria-labelledby="navbarDropdownNindeDots">
                        <div class="card bg-body-emphasis position-relative border-0">
                            <div class="card-body pt-3 px-3 pb-0 overflow-auto scrollbar" style="height: 20rem;">
                                <div class="row text-center align-items-center gx-0 gy-0">
                                    <div class="col-4"><a class="d-block bg-body-secondary-hover p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="../assets/img/nav-icons/behance.webp" alt="" width="30" />
                                            <p class="mb-0 text-body-emphasis text-truncate fs-10 mt-1 pt-1">Behance</p>
                                        </a></div>
                                    <div class="col-4"><a class="d-block bg-body-secondary-hover p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="../assets/img/nav-icons/google-cloud.webp" alt="" width="30" />
                                            <p class="mb-0 text-body-emphasis text-truncate fs-10 mt-1 pt-1">Cloud</p>
                                        </a></div>
                                    <div class="col-4"><a class="d-block bg-body-secondary-hover p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="../assets/img/nav-icons/slack.webp" alt="" width="30" />
                                            <p class="mb-0 text-body-emphasis text-truncate fs-10 mt-1 pt-1">Slack</p>
                                        </a></div>
                                    <div class="col-4"><a class="d-block bg-body-secondary-hover p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="../assets/img/nav-icons/gitlab.webp" alt="" width="30" />
                                            <p class="mb-0 text-body-emphasis text-truncate fs-10 mt-1 pt-1">Gitlab</p>
                                        </a></div>
                                    <div class="col-4"><a class="d-block bg-body-secondary-hover p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="../assets/img/nav-icons/bitbucket.webp" alt="" width="30" />
                                            <p class="mb-0 text-body-emphasis text-truncate fs-10 mt-1 pt-1">BitBucket</p>
                                        </a></div>
                                    <div class="col-4"><a class="d-block bg-body-secondary-hover p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="../assets/img/nav-icons/google-drive.webp" alt="" width="30" />
                                            <p class="mb-0 text-body-emphasis text-truncate fs-10 mt-1 pt-1">Drive</p>
                                        </a></div>
                                    <div class="col-4"><a class="d-block bg-body-secondary-hover p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="../assets/img/nav-icons/trello.webp" alt="" width="30" />
                                            <p class="mb-0 text-body-emphasis text-truncate fs-10 mt-1 pt-1">Trello</p>
                                        </a></div>
                                    <div class="col-4"><a class="d-block bg-body-secondary-hover p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="../assets/img/nav-icons/figma.webp" alt="" width="20" />
                                            <p class="mb-0 text-body-emphasis text-truncate fs-10 mt-1 pt-1">Figma</p>
                                        </a></div>
                                    <div class="col-4"><a class="d-block bg-body-secondary-hover p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="../assets/img/nav-icons/twitter.webp" alt="" width="30" />
                                            <p class="mb-0 text-body-emphasis text-truncate fs-10 mt-1 pt-1">Twitter</p>
                                        </a></div>
                                    <div class="col-4"><a class="d-block bg-body-secondary-hover p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="../assets/img/nav-icons/pinterest.webp" alt="" width="30" />
                                            <p class="mb-0 text-body-emphasis text-truncate fs-10 mt-1 pt-1">Pinterest</p>
                                        </a></div>
                                    <div class="col-4"><a class="d-block bg-body-secondary-hover p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="../assets/img/nav-icons/ln.webp" alt="" width="30" />
                                            <p class="mb-0 text-body-emphasis text-truncate fs-10 mt-1 pt-1">Linkedin</p>
                                        </a></div>
                                    <div class="col-4"><a class="d-block bg-body-secondary-hover p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="../assets/img/nav-icons/google-maps.webp" alt="" width="30" />
                                            <p class="mb-0 text-body-emphasis text-truncate fs-10 mt-1 pt-1">Maps</p>
                                        </a></div>
                                    <div class="col-4"><a class="d-block bg-body-secondary-hover p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="../assets/img/nav-icons/google-photos.webp" alt="" width="30" />
                                            <p class="mb-0 text-body-emphasis text-truncate fs-10 mt-1 pt-1">Photos</p>
                                        </a></div>
                                    <div class="col-4"><a class="d-block bg-body-secondary-hover p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="../assets/img/nav-icons/spotify.webp" alt="" width="30" />
                                            <p class="mb-0 text-body-emphasis text-truncate fs-10 mt-1 pt-1">Spotify</p>
                                        </a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown"><a class="nav-link lh-1 pe-0" id="navbarDropdownUser" href="#!" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
                        <div class="avatar avatar-l ">
                            <img class="rounded-circle " src="../assets/img/team/40x40/57.webp" alt="" />

                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-profile shadow border" aria-labelledby="navbarDropdownUser">
                        <div class="card position-relative border-0">
                            <div class="card-body p-0">
                                <div class="text-center pt-4 pb-3">
                                    <div class="avatar avatar-xl ">
                                        <img class="rounded-circle " src="../assets/img/team/72x72/57.webp" alt="" />

                                    </div>
                                    <h6 class="mt-2 text-body-emphasis">Jerry Seinfield</h6>
                                </div>
                                <div class="mb-3 mx-3">
                                    <input class="form-control form-control-sm" id="statusUpdateInput" type="text" placeholder="Update your status" />
                                </div>
                            </div>
                            <div class="overflow-auto scrollbar" style="height: 10rem;">
                                <ul class="nav d-flex flex-column mb-2 pb-1">
                                    <li class="nav-item"><a class="nav-link px-3 d-block" href="#!"> <span class="me-2 text-body align-bottom" data-feather="user"></span><span>Profile</span></a></li>
                                    <li class="nav-item"><a class="nav-link px-3 d-block" href="#!"><span class="me-2 text-body align-bottom" data-feather="pie-chart"></span>Dashboard</a></li>
                                    <li class="nav-item"><a class="nav-link px-3 d-block" href="#!"> <span class="me-2 text-body align-bottom" data-feather="lock"></span>Posts &amp; Activity</a></li>
                                    <li class="nav-item"><a class="nav-link px-3 d-block" href="#!"> <span class="me-2 text-body align-bottom" data-feather="settings"></span>Settings &amp; Privacy </a></li>
                                    <li class="nav-item"><a class="nav-link px-3 d-block" href="#!"> <span class="me-2 text-body align-bottom" data-feather="help-circle"></span>Help Center</a></li>
                                    <li class="nav-item"><a class="nav-link px-3 d-block" href="#!"> <span class="me-2 text-body align-bottom" data-feather="globe"></span>Language</a></li>
                                </ul>
                            </div>
                            <div class="card-footer p-0 border-top border-translucent">
                                <ul class="nav d-flex flex-column my-3">
                                    <li class="nav-item"><a class="nav-link px-3 d-block" href="#!"> <span class="me-2 text-body align-bottom" data-feather="user-plus"></span>Add another account</a></li>
                                </ul>
                                <hr />
                                <div class="px-3"> <a class="btn btn-phoenix-secondary d-flex flex-center w-100" href="#!"> <span class="me-2" data-feather="log-out"> </span>Sign out</a></div>
                                <div class="my-2 text-center fw-bold fs-10 text-body-quaternary"><a class="text-body-quaternary me-1" href="#!">Privacy policy</a>&bull;<a class="text-body-quaternary mx-1" href="#!">Terms</a>&bull;<a class="text-body-quaternary ms-1" href="#!">Cookies</a></div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="container-fluid mt-5 pt-4">
           

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible mb-4">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <h6 class="mb-0">Please correct the following errors:</h6>
                </div>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="d-flex">
                    <i class="fas fa-exclamation-circle me-2 mt-1"></i>
                    <div>
                        {{ session('error') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer (Now correctly positioned) -->
    <footer class="footer mt-auto py-3 bg-light text-center">
        <p class="mb-0">&copy; {{ date('Y') }} {{ 'Blackfordby System' }}</p>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

    <script src="{{ asset('vendors/popper/popper.min.js') }}"></script>
    <script src="{{ asset('vendors/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendors/anchorjs/anchor.min.js') }}"></script>
    <script src="{{ asset('vendors/is/is.min.js') }}"></script>
    <script src="{{ asset('vendors/fontawesome/all.min.js') }}"></script>
    <script src="{{ asset('vendors/lodash/lodash.min.js') }}"></script>
    <script src="{{ asset('vendors/list.js/list.min.js') }}"></script>
    <script src="{{ asset('vendors/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('vendors/dayjs/dayjs.min.js') }}"></script>

    <!-- Leaflet Maps -->
    <script src="{{ asset('vendors/leaflet/leaflet.js') }}"></script>
    <script src="{{ asset('vendors/leaflet.markercluster/leaflet.markercluster.js') }}"></script>
    <script src="{{ asset('vendors/leaflet.tilelayer.colorfilter/leaflet-tilelayer-colorfilter.min.js') }}"></script>

    <!-- Phoenix Framework -->
    <script src="{{ asset('assets/js/phoenix.js') }}"></script>

    <!-- ECharts -->
    <script src="{{ asset('vendors/echarts/echarts.min.js') }}"></script>

    <!-- Custom Dashboard Scripts -->
    <script src="{{ asset('assets/js/ecommerce-dashboard.js') }}"></script>

    <!-- Add Flatpickr JS -->
    <script src="{{ asset('vendors/flatpickr/flatpickr.min.js') }}"></script>

    <!-- jQuery (if not already included) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    @stack('scripts')

</body>

</html>