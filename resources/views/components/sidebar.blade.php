
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="./index.html" class="brand-link">
            <!--begin::Brand Image-->
            <img
              src="{{ asset('images/logo.png') }}"
              alt="Veron Indonesia"
              class="brand-image opacity-75 shadow"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">Veron Indonesia</span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="navigation"
              aria-label="Main navigation"
              data-accordion="false"
              id="navigation"
            >
              @foreach (config('combobox.menu') as $item)
                @if (!isset($item['children']))
                    <li class="nav-item">
                        <a href="{{ route($item['route']) }}"
                        class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                            <i class="nav-icon bi {{ $item['icon'] }}"></i>
                            <p>{{ $item['label'] }}</p>
                        </a>
                    </li>
                @endif

                @if (isset($item['children']))
                    @php
                        $isOpen = collect($item['children'])->contains(function ($child) {
                            return request()->routeIs($child['route']);
                        });

                    @endphp

                    <li class="nav-item {{ $isOpen ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $isOpen ? 'active' : '' }}">
                            <i class="nav-icon bi {{ $item['icon'] }}"></i>
                            <p>
                                {{ $item['label'] }}
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            @foreach ($item['children'] as $child)
                                <li class="nav-item">
                                    <a href="{{ route($child['route']) }}"
                                    class="nav-link {{ request()->routeIs($child['route']) ? 'active' : '' }}">
                                        <i class="nav-icon {{ request()->routeIs($child['route']) ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                        <p>{{ $child['label'] }}</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
              @endforeach
            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
