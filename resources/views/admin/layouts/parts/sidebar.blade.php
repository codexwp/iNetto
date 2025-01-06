<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" data-bg-class="bg-menu-theme">
    <div class="app-brand demo mb-3" style="border-bottom: 1px solid #eee;">
        <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
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

        <!-- Dashboards -->
        <li class="menu-item {{ is_active_menu('admin.dashboard') }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate" data-i18n="Basic">Dashboard</div>
            </a>
        </li>

        <li class="menu-item {{ is_active_menu('admin.seller.', true) }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bxs-user-detail"></i>
                <div class="text-truncate" data-i18n="Misc">Sellers</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ is_active_menu('admin.seller.index') }}">
                    <a href="{{ route('admin.seller.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Error">All Sellers</div>
                    </a>
                </li>
                <li class="menu-item {{ is_active_menu('admin.seller.create') }}">
                    <a href="{{ route('admin.seller.create') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Error">New Seller</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ is_active_menu('admin.user.', true) }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-user-pin"></i>
                <div class="text-truncate" data-i18n="Misc">Users</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ is_active_menu('admin.user.index') }}">
                    <a href="{{ route('admin.user.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Error">All Users</div>
                    </a>
                </li>
                <li class="menu-item {{ is_active_menu('admin.user.create') }}">
                    <a href="{{ route('admin.user.create') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Error">New User</div>
                    </a>
                </li>
                <li class="menu-item {{ is_active_menu('admin.user.csv_manage') }}">
                    <a href="{{ route('admin.user.csv_manage') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Error">Ex.Import</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ is_active_menu('admin.sms.', true) }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-message-rounded-detail"></i>
                <div class="text-truncate" data-i18n="Misc">SMS</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ is_active_menu('admin.sms.send') }}">
                    <a href="{{ route('admin.sms.send') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Error">Send</div>
                    </a>
                </li>
                <li class="menu-item {{ is_active_menu('admin.sms.index') }}">
                    <a href="{{ route('admin.sms.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Error">History</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ is_active_menu('admin.payment.index') }}">
            <a href="{{ route('admin.payment.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-dollar-circle"></i>
                <div class="text-truncate" data-i18n="Basic">Payments</div>
            </a>
        </li>


        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Configuration</span>
        </li>

        <li class="menu-item {{ is_active_menu('admin.tariff.index') }}">
            <a href="{{ route('admin.tariff.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-offer"></i>
                <div class="text-truncate" data-i18n="Basic">Tariffs</div>
            </a>
        </li>

        <li class="menu-item {{ is_active_menu('admin.package.index') }}">
            <a href="{{ route('admin.package.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-package"></i>
                <div class="text-truncate" data-i18n="Basic">Packages</div>
            </a>
        </li>

        <li class="menu-item {{ is_active_menu('admin.server', true) }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-server"></i>
                <div class="text-truncate" data-i18n="Misc">MikroTik</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ is_active_menu('admin.server.index') }}">
                    <a href="{{ route('admin.server.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Error">Servers</div>
                    </a>
                </li>
                <li class="menu-item {{ is_active_menu('admin.server.profile') }}">
                    <a href="{{ route('admin.server.profile') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Under Maintenance">Profiles</div>
                    </a>
                </li>

                <li class="menu-item {{ is_active_menu('admin.server.client') }}">
                    <a href="{{ route('admin.server.client') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Under Maintenance">Secrets</div>
                    </a>
                </li>

                <li class="menu-item {{ is_active_menu('admin.server.client_live') }}">
                    <a href="{{ route('admin.server.client_live') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Under Maintenance">Active</div>
                    </a>
                </li>

            </ul>
        </li>

        <li class="menu-item {{ is_active_menu('admin.setting.sms_gateway', true) }} {{ is_active_menu('admin.setting.payment_gateway', true) }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-network-chart"></i>
                <div class="text-truncate" data-i18n="Misc">Gateways</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ is_active_menu('admin.setting.sms_gateway') }}">
                    <a href="{{ route('admin.setting.sms_gateway') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Under Maintenance">SMS</div>
                    </a>
                </li>
                <li class="menu-item {{ is_active_menu('admin.setting.payment_gateway') }}">
                    <a href="{{ route('admin.setting.payment_gateway') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Under Maintenance">Payment</div>
                    </a>
                </li>
            </ul>
        </li>


        <li class="menu-item {{ is_active_menu('admin.setting.system') }}">
            <a href="{{ route('admin.setting.system') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div class="text-truncate" data-i18n="Basic">System</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Profile</span>
        </li>

        <li class="menu-item {{ is_active_menu('admin.profile.index') }}">
            <a href="{{ route('admin.profile.index') }}" class="menu-link">
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
