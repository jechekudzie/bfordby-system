<ul class="navbar-nav navbar-nav-icons flex-row">
    <li class="nav-item">
        <div class="theme-control-toggle fa-icon-wait px-2">
            <input class="form-check-input ms-0 theme-control-toggle-input" type="checkbox" data-theme-control="phoenixTheme" value="dark" id="themeControlToggle" />
            <label class="mb-0 theme-control-toggle-label theme-control-toggle-light bg-body-tertiary rounded-circle p-2" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch to dark theme">
                <span class="icon text-primary fs-0" data-feather="moon">Switch to dark theme</span>
            </label>
            <label class="mb-0 theme-control-toggle-label theme-control-toggle-dark bg-body-tertiary rounded-circle p-2" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch to light theme">
                <span class="icon text-primary fs-0" data-feather="sun">Switch to light theme</span>
            </label>
        </div>
    </li>
    <!-- Profile Dropdown -->
    <li class="nav-item dropdown">
        <a class="nav-link lh-1 pe-0" id="navbarDropdownUser" href="#!" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
            <div class="avatar avatar-l">
                <img class="rounded-circle" src="{{ asset('assets/img/team/40x40/57.webp') }}" alt="" />
            </div>
        </a>
        @include('layouts.partials.profile-dropdown')
    </li>
</ul>
