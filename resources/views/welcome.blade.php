<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Mujer Bonita') }} - Salón de Belleza</title>
    <!-- Tailwind CSS (via CDN for standalone visual without rebuilds in current environment) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

    <!-- Navbar -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <span class="text-2xl font-bold text-purple-700">Mujer Bonita ✨</span>
                </div>
                <nav class="hidden md:flex space-x-8">
                    <a href="#" class="text-gray-600 hover:text-purple-700 font-medium transition">Servicios</a>
                    <a href="#" class="text-gray-600 hover:text-purple-700 font-medium transition">Galería</a>
                </nav>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="px-5 py-2 rounded-md font-medium text-purple-700 bg-purple-50 hover:bg-purple-100 transition border border-purple-200 shadow-sm">
                        Ingresar al Sistema
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <main class="flex-grow">
        <div class="relative bg-purple-900 border-b border-purple-800">
            <!-- Decorative image -->
            <div class="absolute inset-0">
                <img class="w-full h-full object-cover opacity-30 mix-blend-multiply" src="https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?q=80&w=2069&auto=format&fit=crop" alt="Mujer Bonita Salon">
                <div class="absolute inset-0 bg-gradient-to-t from-purple-950 to-transparent"></div>
            </div>
            
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32 flex flex-col items-center text-center">
                <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
                    Realza tu <span class="text-purple-300">belleza</span> con nosotros
                </h1>
                <p class="mt-6 max-w-2xl text-xl text-purple-100">
                    Experimenta el cuidado premium y los servicios de vanguardia. En Mujer Bonita nos encargamos de que brilles en cada momento especial.
                </p>
                <div class="mt-10 max-w-sm sm:max-w-none sm:flex sm:justify-center gap-4">
                    <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-bold rounded-md text-purple-900 bg-white hover:bg-gray-100 md:py-4 md:text-lg transition shadow-lg hover:shadow-xl hover:-translate-y-0.5 duration-200">
                        Registrarse como Cliente
                    </a>
                    <a href="#servicios" class="mt-4 w-full flex items-center justify-center px-8 py-3 border border-purple-300 text-base font-bold rounded-md text-white hover:bg-purple-800 sm:mt-0 md:py-4 md:text-lg transition">
                        Ver Servicios
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Placeholder for Visuals -->
        <div id="servicios" class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Nuestros Servicios Premium</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 opacity-80">
                    <div class="p-6 bg-purple-50 rounded-xl border border-purple-100">
                        <h3 class="text-xl font-bold text-purple-800">Maquillaje Profesional</h3>
                        <p class="mt-2 text-gray-600">Para bodas, eventos sociales y ocasiones especiales.</p>
                    </div>
                    <div class="p-6 bg-purple-50 rounded-xl border border-purple-100">
                        <h3 class="text-xl font-bold text-purple-800">Cuidado de la Piel</h3>
                        <p class="mt-2 text-gray-600">Tratamientos faciales, hidratación profunda y más.</p>
                    </div>
                    <div class="p-6 bg-purple-50 rounded-xl border border-purple-100">
                        <h3 class="text-xl font-bold text-purple-800">Estilismo y Peinados</h3>
                        <p class="mt-2 text-gray-600">Cortes modernos, coloración premium y peinados de gala.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex flex-col md:flex-row items-center justify-between">
            <div class="text-center md:text-left mb-6 md:mb-0">
                <span class="text-2xl font-bold text-white">Mujer Bonita ✨</span>
                <p class="mt-2 text-gray-400 text-sm">El lugar donde tu belleza se encuentra con el profesionalismo.</p>
            </div>
            
            <div class="text-center text-sm text-gray-400">
                <p>Contacto: info@mujerbonita.com | Tel: (555) 123-4567</p>
                <div class="mt-4">
                    <a href="{{ route('login') }}" class="text-purple-400 hover:text-purple-300 font-semibold text-xs tracking-wider uppercase transition">
                        🛅 Acceso Staff
                    </a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
