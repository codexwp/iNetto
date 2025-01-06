<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" data-bg-class="bg-menu-theme">
    <div class="app-brand demo mb-3" style="border-bottom: 1px solid #eee;">
        <a href="{{ route('seller.dashboard') }}" class="app-brand-link">
            @if(config('settings.system_general.logo_path'))
                <img class="w-100" src="{{ asset('storage/' . config('settings.system_general.logo_path')) }}">
            @else
            <i class='bx bx-station' style="font-size: 35px;"></i>
            <span class="app-brand-text demo menu-text fw-bold ms-2">{{ config('settings.system_general.logo_text', 'iNetto') }}</span>
            @endif
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
            <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
        </a>
    </div>

    <div class="menu-inner-shadow" style="display: none;"></div>

    <ul class="menu-inner py-1 ps ps--active-y">

        <li class="menu-item">
            <a href="javascript:void(0)" class="menu-link" style="background: #eee;">
                <i class="menu-icon tf-icons bx bx-money-withdraw" style="font-size: 1.2rem;"></i>
                <div class="text-truncate" data-i18n="Basic">Bal-&nbsp;  {{ auth('seller')->user()->balance . config('settings.system_general.currency_symbol', '$')  }}</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Services</span>
        </li>


        <!-- Dashboards -->
        <li class="menu-item {{ is_active_menu('seller.dashboard') }}">
            <a href="{{ route('seller.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate" data-i18n="Basic">Dashboard</div>
            </a>
        </li>

        <li class="menu-item {{ is_active_menu('seller.user.index') }} {{ is_active_menu('seller.user.detail') }}">
            <a href="{{ route('seller.user.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-pin"></i>
                <div class="text-truncate" data-i18n="Basic">All Users</div>
            </a>
        </li>

        <li class="menu-item {{ is_active_menu('seller.user.create') }}">
            <a href="{{ route('seller.user.create') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-plus"></i>
                <div class="text-truncate" data-i18n="Basic">New User</div>
            </a>
        </li>

        <li class="menu-item {{ is_active_menu('seller.package.index') }}">
            <a href="{{ route('seller.package.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-package"></i>
                <div class="text-truncate" data-i18n="Basic">Packages</div>
            </a>
        </li>

        <li class="menu-item {{ is_active_menu('seller.payment.index') }}">
            <a href="{{ route('seller.payment.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-dollar-circle"></i>
                <div class="text-truncate" data-i18n="Basic">Payments</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Profile</span>
        </li>

        <li class="menu-item {{ is_active_menu('seller.profile.index') }}">
            <a href="{{ route('seller.profile.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div class="text-truncate" data-i18n="Basic">My Profile</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link logout-button">
                <i class="menu-icon tf-icons bx bx-power-off"></i>
                <div class="text-truncate" data-i18n="Basic">Logout</div>
            </a>
        </li>



    </ul>
</aside>
