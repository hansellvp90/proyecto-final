<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Mi Proyecto')</title>
    {{-- Vite para assets (asegúrate de ejecutar npm install y npm run dev/build) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-sans">
    <nav class="p-4 border-b">
        <div class="container mx-auto flex justify-between">
            <a href="{{ url('/') }}" class="font-bold">MiProyecto</a>
            <div class="space-x-4">
                @auth
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <a href="{{ route('estudiantes.index') }}">Estudiantes</a>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline">
                        @csrf
                        <button type="submit">Cerrar sesión</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Registro</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="py-6">
        @yield('content')
    </main>

    {{-- Sección para scripts individuales --}}
    @yield('scripts')
</body>
</html>
