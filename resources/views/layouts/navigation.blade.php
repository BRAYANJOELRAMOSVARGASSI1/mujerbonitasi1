<ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('home') }}">
            <svg class="nav-icon">
                <use xlink:href="{{ asset('icons/coreui.svg#cil-speedometer') }}"></use>
            </svg>
            {{ __('Dashboard') }}
        </a>
    </li>

    @can('ver roles')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('roles.index') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('icons/coreui.svg#cil-clipboard') }}"></use>
                </svg>
                {{ __('Roles') }}
            </a>
        </li>
    @endcan

    @can('ver permisos')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('permissions.index') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('icons/coreui.svg#cil-lock-locked') }}"></use>
                    {{-- user-plus --}}
                </svg>
                {{ __('Permisos') }}
            </a>
        </li>
    @endcan

    @can('ver usuarios')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('users.index') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('icons/coreui.svg#cil-user') }}"></use>
                </svg>
                {{ __('Usuarios') }}
            </a>
        </li>
    @endcan

    {{-- @can('ver libros')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('books.index') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('icons/coreui.svg#cil-book') }}"></use>
                </svg>
                {{ __('Libros') }}
            </a>
        </li>
    @endcan --}}

    @if(auth()->check() && (auth()->user()->hasAnyRole(['admin', 'super-admin']) || auth()->user()->can('ver bitacora')))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('bitacora.index') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('icons/coreui.svg#cil-history') }}"></use>
                </svg>
                {{ __('Bitácora') }}
            </a>
        </li>
    @endif
</ul>
