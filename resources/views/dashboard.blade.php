@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto py-8">
  <h1 class="text-2xl font-bold">Dashboard</h1>
  <p class="mt-2">Bienvenido, {{ auth()->user()->name }} ({{ auth()->user()->email }})</p>

  <div class="mt-6">
    <a href="{{ route('estudiantes.index') }}" class="px-4 py-2 bg-green-600 text-white rounded">Ir a Estudiantes</a>
  </div>
</div>
@endsection
