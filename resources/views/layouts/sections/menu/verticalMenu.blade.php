<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <!-- ! Hide app brand if navbar-full -->
    <div class="app-brand demo">
        <div class="d-flex align-items-center justify-content-center w-100">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ url('/dashboard') }}" class="d-flex align-items-center justify-content-center">
                    <img src="{{ asset('assets/img/icons/Logo_Orangtua.png') }}" alt="Primary Logo" class="app-brand-logo"
                        style="height: 40px; width: auto;">
                    <img src="{{ asset('assets/img/icons/logo_k3.png') }}" alt="Secondary Logo" class="app-brand-logo"
                        style="height: 40px; width: auto;">
                    <span class="app-brand-text ms-2 text-primary fw-bold text-uppercase">
                        {{-- Management System --}}
                    </span>
                </a>
            </div>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-3 d-block d-xl-none">
                <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
        </div>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        @foreach ($menuData[0]->menu as $menu)
            {{-- adding active and open class if child is active --}}

            {{-- menu headers --}}
            @if (isset($menu->menuHeader))
                <li class="menu-header small text-uppercase">
                    <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
                </li>
            @else
                {{-- active menu method --}}
                @php
                    $activeClass = null;
                    $currentRouteName = Route::currentRouteName();

                    if (is_array($menu->slug)) {
                        if (in_array($currentRouteName, $menu->slug)) {
                            $activeClass = 'active';
                        }
                    } elseif ($currentRouteName === $menu->slug) {
                        $activeClass = 'active';
                    } elseif (isset($menu->submenu)) {
                        if (gettype($menu->slug) === 'array') {
                            foreach ($menu->slug as $slug) {
                                if (str_contains($currentRouteName, $slug) and strpos($currentRouteName, $slug) === 0) {
                                    $activeClass = 'active open';
                                }
                            }
                        } else {
                            if (
                                str_contains($currentRouteName, $menu->slug) and
                                strpos($currentRouteName, $menu->slug) === 0
                            ) {
                                $activeClass = 'active open';
                            }
                        }
                    }

                    //filter by role
                    $userRole = auth()->user()->role;
                @endphp


                {{-- main menu --}}
                @if ($userRole === 'user')
                    @if (isset($menu->role) and $menu->role === 'user')
                        <li class="menu-item {{ $activeClass }}">
                            <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}"
                                class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}"
                                @if (isset($menu->target) and !empty($menu->target)) target="_blank" @endif>
                                @isset($menu->icon)
                                    <i class="{{ $menu->icon }}"></i>
                                @endisset
                                <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
                                @isset($menu->badge)
                                    <div class="badge rounded-pill bg-{{ $menu->badge[0] }} text-uppercase ms-auto">
                                        {{ $menu->badge[1] }}</div>
                                @endisset
                            </a>

                            {{-- submenu --}}
                            @isset($menu->submenu)
                                @include('layouts.sections.menu.submenu', ['menu' => $menu->submenu])
                            @endisset
                        </li>
                    @endif
                @else
                    <li class="menu-item {{ $activeClass }}">
                        <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}"
                            class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}"
                            @if (isset($menu->target) and !empty($menu->target)) target="_blank" @endif>
                            @isset($menu->icon)
                                <i class="{{ $menu->icon }}"></i>
                            @endisset
                            <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
                            @isset($menu->badge)
                                <div class="badge rounded-pill bg-{{ $menu->badge[0] }} text-uppercase ms-auto">
                                    {{ $menu->badge[1] }}</div>
                            @endisset
                        </a>

                        {{-- submenu --}}
                        @isset($menu->submenu)
                            @include('layouts.sections.menu.submenu', ['menu' => $menu->submenu])
                        @endisset
                    </li>
                @endif
            @endif
        @endforeach
    </ul>

</aside>
