<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Bienvenida - Mi Proyecto</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">
  <div class="max-w-2xl text-center p-6 bg-white rounded shadow">
    <h1 class="text-3xl font-bold mb-4">Bienvenido a Mi Proyecto</h1>
    <p class="mb-4">Página pública para visitantes. Inicia sesión o regístrate para acceder al dashboard.</p>
    <div class="space-x-3">
      <a href="{{ route('login') }}" class="px-4 py-2 border rounded">Iniciar sesión</a>
      <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Registrarse</a>
    </div>
  </div>
</body>
</html>
