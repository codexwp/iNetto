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

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Services</span>
        </li>


        <!-- Dashboards -->
        <li class="menu-item {{ is_active_menu('dashboard') }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate" data-i18n="Basic">Home</div>
            </a>
        </li>

        <li class="menu-item {{ is_active_menu('package.index') }}">
            <a href="{{ route('package.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-package"></i>
                <div class="text-truncate" data-i18n="Basic">Packages</div>
            </a>
        </li>

        <li class="menu-item {{ is_active_menu('payment.bill_pay') }}">
            <a href="{{ route('payment.bill_pay') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-money"></i>
                <div class="text-truncate" data-i18n="Basic">Bill Pay</div>
            </a>
        </li>


        <li class="menu-item {{ is_active_menu('payment.index') }}">
            <a href="{{ route('payment.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-dollar-circle"></i>
                <div class="text-truncate" data-i18n="Basic">Payments</div>
            </a>
        </li>

        <li class="menu-item {{ is_active_menu('support.index') }}">
            <a href="{{ route('support.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-help-circle"></i>
                <div class="text-truncate" data-i18n="Basic">Support</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Profile</span>
        </li>

        <li class="menu-item {{ is_active_menu('profile.index') }}">
            <a href="{{ route('profile.index') }}" class="menu-link">
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
