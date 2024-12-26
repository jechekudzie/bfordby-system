<nav class="navbar navbar-top fixed-top navbar-expand-lg" id="navbarTop">
    <div class="navbar-logo">
        <button class="btn navbar-toggler navbar-toggler-humburger-icon hover-bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTopCollapse" aria-controls="navbarTopCollapse" aria-expanded="false" aria-label="Toggle Navigation">
            <span class="navbar-toggle-icon"><span class="toggle-line"></span></span>
        </button>
        <a class="navbar-brand me-1 me-sm-3" href="{{ route('dashboard') }}">
            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('assets/img/icons/logo.png') }}" alt="phoenix" width="27" />
                    <h5 class="logo-text ms-2 d-none d-sm-block">{{ config('app.name') }}</h5>
                </div>
            </div>
        </a>
    </div>

    @include('layouts.partials.navigation-menu')
    @include('layouts.partials.navigation-right')
</nav>
