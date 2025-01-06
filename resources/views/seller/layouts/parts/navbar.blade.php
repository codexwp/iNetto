@php $user = auth('seller')->user(); @endphp
<nav
    class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="bx bx-menu bx-md"></i>
        </a>
    </div>
    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center position-relative">
                <i class="bx bx-search bx-md"></i>
                <input
                    name="user_search_item"
                    id="navUserSearch"
                    type="text"
                    class="form-control border-0 shadow-none ps-1 ps-sm-2"
                    placeholder="Search..."
                    aria-label="Search..." autocomplete="new-username"/>
                <div id="suggestions" style="display: none;"></div>
            </div>
        </div>
        <!-- /Search -->

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- Place this tag where you want the button to render. -->
            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a
                    class="nav-link dropdown-toggle hide-arrow p-0"
                    href="javascript:void(0);"
                    data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset('assets/img/avatars/default_profile.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('assets/img/avatars/default_profile.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                    <small class="text-muted">Seller</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('seller.profile.index') }}">
                            <i class="bx bx-user bx-md me-3"></i><span>My Profile</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('seller.profile.change_password') }}"> <i class="bx bx-cog bx-md me-3"></i><span>Change Password</span> </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                        <a class="dropdown-item logout-button" href="javascript:void(0);">
                            <i class="bx bx-power-off bx-md me-3"></i><span>Log Out</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>
@push('scripts')
    <script>
        $(document).ready(function() {
            const users = @json(get_usernames($user->id));
            var data = [];for(const s in users)users.hasOwnProperty(s)&&data.push({value:users[s],url:window.BASE_URL+"/seller/users/"+s});function displaySuggestions(s){const e=$("#suggestions");e.empty(),0!==s.length?(s.forEach((s=>{e.append(`<div class="suggestion-item" data-url="${s.url}">${s.value}</div>`)})),e.show()):e.hide()}$("#navUserSearch").on("input",(function(){const s=$(this).val().toLowerCase();displaySuggestions(data.filter((e=>e.value.toLowerCase().includes(s))))})),$("#suggestions").on("click",".suggestion-item",(function(){const s=$(this).data("url");window.location.href=s})),$(document).click((function(s){$(s.target).closest("#navUserSearch, #suggestions").length||$("#suggestions").hide()}));
        });
    </script>
@endpush
