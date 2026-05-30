<ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('home') }}">
            <svg class="nav-icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-speedometer') }}"></use></svg>
            {{ __('Dashboard') }}
        </a>
    </li>

    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- P1 — GESTIÓN DE USUARIOS Y SEGURIDAD (Solo Admin) --}}
    @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'super-admin']))
    <li class="nav-title" style="color:#CC5CB8; font-weight:600; font-size:0.7rem; letter-spacing:1px; margin-top:1rem;">p1-gestion de usuario y seguridad</li>
    @endif

    @can('ver usuarios')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('users.index') }}">
                <svg class="nav-icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-user') }}"></use></svg>
                {{ __('Usuarios') }}
            </a>
        </li>
    @endcan

    @can('ver roles')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('roles.index') }}">
                <svg class="nav-icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-clipboard') }}"></use></svg>
                {{ __('Roles') }}
            </a>
        </li>
    @endcan

    @can('ver permisos')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('permissions.index') }}">
                <svg class="nav-icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-lock-locked') }}"></use></svg>
                {{ __('Permisos') }}
            </a>
        </li>
    @endcan

    @if(auth()->check() && (auth()->user()->hasAnyRole(['admin', 'super-admin']) || auth()->user()->can('ver bitacora')))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('bitacora.index') }}">
                <svg class="nav-icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-history') }}"></use></svg>
                {{ __('Bitácora') }}
            </a>
        </li>
    @endif

    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- P2 — GESTIÓN DE PERSONAL Y CLIENTES               --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    @if(auth()->check() && (auth()->user()->hasAnyRole(['admin', 'super-admin', 'recepcionista']) || auth()->user()->canAny(['ver clientes', 'ver estilistas', 'ver horarios'])))
    <li class="nav-title" style="color:#CC5CB8; font-weight:600; font-size:0.7rem; letter-spacing:1px; margin-top:1rem;">p2-gestion de personal y clientes</li>
    @endif

    @if(auth()->check() && (auth()->user()->hasAnyRole(['admin', 'super-admin'])))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('recepcionistas.index') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('icons/coreui.svg#cil-user') }}"></use>
                </svg>
                Recepcionistas
            </a>
        </li>
    @endif

    @if(auth()->check() && (auth()->user()->hasAnyRole(['admin', 'super-admin', 'recepcionista']) || auth()->user()->can('ver clientes')))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('clientes.index') }}">
                <svg class="nav-icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-people') }}"></use></svg>
                {{ __('Clientes') }}
            </a>
        </li>
    @endif

    @if(auth()->check() && (auth()->user()->hasAnyRole(['admin', 'super-admin']) || auth()->user()->can('ver estilistas')))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('estilistas.index') }}">
                <svg class="nav-icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-star') }}"></use></svg>
                {{ __('Estilistas') }}
            </a>
        </li>
    @endif

    {{-- Horarios Gestión (Solo Admin) --}}
    @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'super-admin']))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('horarios.index') }}">
                <svg class="nav-icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-calendar') }}"></use></svg>
                {{ __('Horarios (Gestión)') }}
            </a>
        </li>
    @endif

    @if(auth()->check() && (auth()->user()->hasAnyRole(['admin', 'super-admin', 'recepcionista', 'estilista']) || auth()->user()->can('ver horarios')))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('horarios.consultar') }}">
                <svg class="nav-icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-list') }}"></use></svg>
                {{ __('Consultar Disponibilidad') }}
            </a>
        </li>
    @endif

    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- P3 — GESTIÓN DE INVENTARIO Y HERRAMIENTAS         --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    @if(auth()->check() && (auth()->user()->hasAnyRole(['admin', 'super-admin', 'recepcionista']) || auth()->user()->canAny(['ver productos', 'ver herramientas', 'ver stock'])))
    <li class="nav-title" style="color:#CC5CB8; font-weight:600; font-size:0.7rem; letter-spacing:1px; margin-top:1rem;">p3-gestion de inventario y herramientas</li>
    @endif

    @if(auth()->check() && (auth()->user()->hasAnyRole(['admin', 'super-admin', 'recepcionista']) || auth()->user()->can('ver productos')))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('productos.index') }}">
                <svg class="nav-icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-basket') }}"></use></svg>
                {{ __('Productos') }}
            </a>
        </li>
    @endif

    {{-- Herramientas (Estilista puede ver, Cliente NO) --}}
    @if(auth()->check() && (auth()->user()->hasAnyRole(['admin', 'super-admin', 'recepcionista', 'estilista'])))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('herramientas.index') }}">
                <svg class="nav-icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-settings') }}"></use></svg>
                {{ __('Herramientas') }}
            </a>
        </li>
    @endif

    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- P4 — GESTIÓN DE SERVICIOS Y CITAS                 --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    @if(auth()->check() && (auth()->user()->hasAnyRole(['admin', 'super-admin', 'recepcionista', 'estilista', 'cliente']) || auth()->user()->canAny(['ver servicios', 'ver citas', 'ver promociones'])))
    <li class="nav-title" style="color:#CC5CB8; font-weight:600; font-size:0.7rem; letter-spacing:1px; margin-top:1rem;">p4-gestion de servicios y citas</li>

        @can('ver servicios')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('servicios.index') }}">
                <svg class="nav-icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-cut') }}"></use></svg>
                {{ __('Servicios') }}
            </a>
        </li>
        @endcan

        {{-- CU8 — Agendar Cita (Admin, Recepcionista, Cliente) --}}
        @can('ver citas')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('citas.index') }}">
                <svg class="nav-icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-calendar') }}"></use></svg>
                {{ __('Agendar Cita') }}
            </a>
        </li>
        @endcan

        {{-- CU14 — Servicios Realizados (Admin, Recepcionista, Estilista) --}}
        @can('ver servicios realizados')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('servicios-realizados.index') }}">
                <svg class="nav-icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-check-circle') }}"></use></svg>
                {{ __('Servicios Realizados') }}
            </a>
        </li>
        @endcan

        {{-- CU24 — Promociones (Todos pueden ver) --}}
        @can('ver promociones')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('promociones.index') }}">
                <svg class="nav-icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-gift') }}"></use></svg>
                {{ __('Promociones') }}
            </a>
        </li>
        @endcan
    @endif

    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- P5 — PAGOS Y FACTURACIÓN                          --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    @if(auth()->check() && (auth()->user()->hasAnyRole(['admin', 'super-admin']) || auth()->user()->can('ver comisiones')))
    <li class="nav-title" style="color:#CC5CB8; font-weight:600; font-size:0.7rem; letter-spacing:1px; margin-top:1rem;">p5-pagos y facturación</li>

        {{-- CU25 — Comisiones (Admin, Estilista ve las suyas) --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ route('comisiones.index') }}">
                <svg class="nav-icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-wallet') }}"></use></svg>
                {{ __('Comisiones') }}
            </a>
        </li>
    @endif

    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- P6 — REPORTES Y ANÁLISIS (Solo Admin)             --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'super-admin']))
    <li class="nav-title" style="color:#CC5CB8; font-weight:600; font-size:0.7rem; letter-spacing:1px; margin-top:1rem;">p6-reportes y análisis</li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('reportes.index') }}"
               style="{{ request()->routeIs('reportes.*') ? 'background:rgba(204,92,184,0.15); border-right:3px solid #cc5cb8;' : '' }}">
                <svg class="nav-icon"><use xlink:href="{{ asset('icons/coreui.svg#cil-chart-pie') }}"></use></svg>
                {{ __('Reportes') }}
            </a>
        </li>
    @endif
</ul>

