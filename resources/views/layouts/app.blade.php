<!DOCTYPE html>
<html lang="en">

<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="theme-color" content="#ffffff">
    @vite('resources/sass/app.scss')
</head>

<body>
    <div class="sidebar sidebar-dark sidebar-fixed" id="sidebar" style="background-color:#381432; border-right:1px solid #5a2450;">
        <div class="sidebar-brand d-none d-md-flex" style="background-color:#381432; border-bottom:1px solid #5a2450; padding:1rem;">
            <div style="display:flex; align-items:center; justify-content:center; width:100%;">
                <div style="width:40px; height:40px; background-color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:0.75rem;">
                    <svg class="icon" style="color:#662a5b; width:24px; height:24px;">
                        <use xlink:href="{{ asset('icons/coreui.svg#cil-star') }}"></use>
                    </svg>
                </div>
                <!-- <span style="color:white; font-weight:600; font-size:1.1rem;">MUJER BONITA</span> -->
            </div>
        </div>
        
        <!-- Logo para modo responsivo (móvil) -->
        <div class="sidebar-brand d-md-none" style="background-color:#381432; border-bottom:1px solid #5a2450; padding:1rem;">
            <div style="display:flex; align-items:center; justify-content:center; width:100%;">
                <div style="width:35px; height:35px; background-color:white; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                    <svg class="icon" style="color:#381432; width:20px; height:20px;">
                        <use xlink:href="{{ asset('icons/coreui.svg#cil-star') }}"></use>
                    </svg>
                </div>
            </div>
        </div>
        
        @include('layouts.navigation')
        <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable" style="background-color:#5a2450; border:none; color:white;"></button>
    </div>
    <div class="wrapper d-flex flex-column min-vh-100" style="background-color:#f8f9fa;">
        <header class="header header-sticky mb-4" style="background-color:white; border-bottom:1px solid #dee2e6; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div class="container-fluid">
                <button class="header-toggler px-md-0 me-md-3" type="button"
                    onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()"
                    style="background-color:#662a5b; border:none; border-radius:8px; padding:0.5rem; color:white;">
                    <svg class="icon icon-lg">
                        <use xlink:href="{{ asset('icons/coreui.svg#cil-menu') }}"></use>
                    </svg>
                </button>
                <a class="header-brand d-md-none" href="#">
                    <!-- <svg width="118" height="46" alt="CoreUI Logo">
                        <use xlink:href="{{ asset('icons/brand.svg#full') }}"></use>
                    </svg> -->
                    <!-- <a class="nav-link" href="{{ route('home') }}" style="color:#212529; font-weight:600; font-size:1.2rem;">
                            MUJER BONITA
                    </a> -->
                    <a class="nav-link" href="{{ route('users.index') }}" style="color:#212529; font-weight:600; font-size:1.2rem;">
                            MUJER BONITA
                    </a>
                </a>
                {{-- <ul class="header-nav d-none d-md-flex">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}" style="color:#212529; font-weight:600; font-size:1.2rem;">
                            MUJER BONITA
                        </a>
                    </li>
                </ul> --}}
                <ul class="header-nav ms-auto">

                </ul>
                <ul class="header-nav ms-3">
                    <li class="nav-item dropdown">
                        <a class="nav-link py-0" data-coreui-toggle="dropdown" href="#" role="button"
                            aria-haspopup="true" aria-expanded="false" 
                            style="color:#212529; font-weight:500; display:flex; align-items:center;">
                            <div style="width:32px; height:32px; background-color:#662a5b; border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:0.5rem;">
                                <svg class="icon" style="color:white; width:16px; height:16px;">
                                    <use xlink:href="{{ asset('icons/coreui.svg#cil-user') }}"></use>
                                </svg>
                            </div>
                            {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-end pt-0" style="background-color:#5a2450; border:none; border-radius:12px; box-shadow: 0 8px 25px rgba(190, 97, 97, 0.15);">
                            <a class="dropdown-item" href="{{ route('profile.show') }}"  
                               style="font-weight:500; color:#fff; padding:0.75rem 1rem; border-radius:8px; margin:0.25rem;" >
                                <svg class="icon me-2" style="color:#662a5b;">
                                    <use xlink:href="{{ asset('icons/coreui.svg#cil-user') }}"></use>
                                </svg>
                                {{ __('Mi perfil') }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}" >
                                @csrf
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    style="font-weight:500; color:#fff; padding:0.75rem 1rem; border-radius:8px; margin:0.25rem;">
                                    <svg class="icon me-2" style="color:#662a5b;">
                                        <use xlink:href="{{ asset('icons/coreui.svg#cil-account-logout') }}"></use>
                                    </svg>
                                    {{ __('Cerrar sesión') }}
                                </a>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </header>
        <div class="body flex-grow-1 px-3" style="background-color:#f8f9fa;">
            <div class="container-lg">
                @yield('content')
            </div>
        </div>
        <!-- <footer class="footer" style="background-color:#662a5b; color:white; padding:1rem 0; text-align:center; border-top:1px solid #5a2450;">
            <div class="container">
                <small style="color:white; opacity:0.8;">© 2024 MUJER BONITA - Sistema de Gestión</small>
            </div>
        </footer> -->
    </div>
    <script src="{{ asset('js/coreui.bundle.min.js') }}"></script>
</body>

</html>
