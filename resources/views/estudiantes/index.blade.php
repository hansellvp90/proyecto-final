@extends('layouts.app')

@section('title', 'Gestión de Estudiantes')

@section('content')
<div class="container mx-auto py-8">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Estudiantes</h1>
    <button id="btnNuevo" class="px-4 py-2 bg-green-600 text-white rounded">Nuevo estudiante</button>
  </div>

  <div id="alert" class="hidden mb-4 p-3 rounded"></div>

  <div class="overflow-x-auto bg-white rounded shadow">
    <table id="tabla-estudiantes" class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left">ID</th>
          <th class="px-6 py-3 text-left">Nombre</th>
          <th class="px-6 py-3 text-left">Correo</th>
          <th class="px-6 py-3 text-left">Curso</th>
          <th class="px-6 py-3 text-left">Acciones</th>
        </tr>
      </thead>
      <tbody id="tbody-estudiantes" class="bg-white divide-y divide-gray-200">
        <!-- Filas generadas por JS -->
      </tbody>
    </table>
  </div>
</div>

<!-- Modal formulario (crear / editar) -->
<div id="modalForm" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
  <div class="bg-white rounded-lg w-full max-w-xl p-6">
    <h2 id="modalTitle" class="text-xl font-semibold mb-4">Nuevo estudiante</h2>

    <form id="formEstudiante" class="space-y-4">
      <input type="hidden" id="estudianteId" value="">
      <div>
        <label class="block text-sm font-medium">Nombre</label>
        <input id="nombre" type="text" class="mt-1 block w-full border rounded p-2" required>
      </div>

      <div>
        <label class="block text-sm font-medium">Correo</label>
        <input id="correo" type="email" class="mt-1 block w-full border rounded p-2" required>
      </div>

      <div>
        <label class="block text-sm font-medium">Curso</label>
        <input id="curso" type="text" class="mt-1 block w-full border rounded p-2">
      </div>

      <div class="flex justify-end space-x-2 mt-4">
        <button type="button" id="btnCerrar" class="px-4 py-2 border rounded">Cancelar</button>
        <button type="submit" id="btnGuardar" class="px-4 py-2 bg-blue-600 text-white rounded">Guardar</button>
      </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
/**
 * frontend.js embebido: consumo de la API con fetch().
 * - Soporta: listar, crear, editar (PUT), eliminar.
 * - Está pensado para la ruta /api/estudiantes.
 */
document.addEventListener('DOMContentLoaded', () => {
  const API_URL = '/api/estudiantes';
  const tbody = document.getElementById('tbody-estudiantes');
  const alertBox = document.getElementById('alert');

  const modal = document.getElementById('modalForm');
  const btnNuevo = document.getElementById('btnNuevo');
  const btnCerrar = document.getElementById('btnCerrar');
  const form = document.getElementById('formEstudiante');
  const modalTitle = document.getElementById('modalTitle');

  const inputId = document.getElementById('estudianteId');
  const inputNombre = document.getElementById('nombre');
  const inputCorreo = document.getElementById('correo');
  const inputCurso = document.getElementById('curso');

  // Mostrar alerta breve
  function showAlert(message, type = 'success') {
    alertBox.textContent = message;
    alertBox.className = '';
    alertBox.classList.add('mb-4', 'p-3', 'rounded');
    if (type === 'success') {
      alertBox.classList.add('bg-green-100', 'text-green-800');
    } else {
      alertBox.classList.add('bg-red-100', 'text-red-800');
    }
    alertBox.classList.remove('hidden');
    setTimeout(() => alertBox.classList.add('hidden'), 3500);
  }

  // Abrir / cerrar modal
  function abrirModal() { modal.classList.remove('hidden'); modal.classList.add('flex'); }
  function cerrarModal() { modal.classList.add('hidden'); modal.classList.remove('flex'); form.reset(); inputId.value = ''; }

  btnNuevo.addEventListener('click', () => {
    modalTitle.textContent = 'Nuevo estudiante';
    abrirModal();
  });
  btnCerrar.addEventListener('click', cerrarModal);

  // Cargar lista de estudiantes
  async function loadEstudiantes() {
    tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4">Cargando...</td></tr>';
    try {
      const res = await fetch(API_URL);
      if (!res.ok) throw new Error('Error al obtener estudiantes');
      const json = await res.json();
      const data = json.data ?? json; // compatibilidad con paginación
      if (!data || data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4">No hay estudiantes</td></tr>';
        return;
      }
      tbody.innerHTML = '';
      data.forEach(est => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td class="px-6 py-4 whitespace-nowrap">${est.id}</td>
          <td class="px-6 py-4 whitespace-nowrap">${escapeHtml(est.nombre)}</td>
          <td class="px-6 py-4 whitespace-nowrap">${escapeHtml(est.email || est.correo || '')}</td>
          <td class="px-6 py-4 whitespace-nowrap">${escapeHtml(est.curso || '')}</td>
          <td class="px-6 py-4 whitespace-nowrap">
            <button data-id="${est.id}" class="btnEdit px-2 py-1 border rounded mr-2">Editar</button>
            <button data-id="${est.id}" class="btnDelete px-2 py-1 border rounded text-red-600">Eliminar</button>
          </td>
        `;
        tbody.appendChild(tr);
      });

      // attach events
      document.querySelectorAll('.btnEdit').forEach(b => b.addEventListener('click', onEdit));
      document.querySelectorAll('.btnDelete').forEach(b => b.addEventListener('click', onDelete));
    } catch (err) {
      tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-red-600">Error cargando estudiantes</td></tr>';
      console.error(err);
    }
  }

  // Escape para evitar XSS al insertar texto
  function escapeHtml(unsafe) {
    return (unsafe === null || unsafe === undefined) ? '' :
      unsafe.toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
  }

  // Crear / Editar (submit del formulario)
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const id = inputId.value;
    const payload = {
      nombre: inputNombre.value.trim(),
      email: inputCorreo.value.trim(),
      curso: inputCurso.value.trim(),
    };

    try {
      let res;
      if (!id) {
        // POST -> crear
        res = await fetch(API_URL, {
          method: 'POST',
          headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
          body: JSON.stringify(payload)
        });
      } else {
        // PUT -> actualizar completo (si prefieres PATCH, cambia method a 'PATCH')
        res = await fetch(`${API_URL}/${id}`, {
          method: 'PUT',
          headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
          body: JSON.stringify(payload)
        });
      }

      const json = await res.json();
      if (!res.ok) {
        // Manejo de errores: pueden venir como json.errors o json.message
        const message = json.message || (json.errors ? JSON.stringify(json.errors) : 'Error');
        throw new Error(message);
      }

      showAlert(id ? 'Estudiante actualizado' : 'Estudiante creado');
      cerrarModal();
      loadEstudiantes();
    } catch (err) {
      console.error(err);
      showAlert('Error: ' + err.message, 'error');
    }
  });

  // Editar (llenar formulario)
  async function onEdit(e) {
    const id = e.currentTarget.dataset.id;
    try {
      const res = await fetch(`${API_URL}/${id}`);
      if (!res.ok) throw new Error('Error al obtener estudiante');
      const json = await res.json();
      const data = json.data ?? json;
      inputId.value = data.id;
      inputNombre.value = data.nombre ?? '';
      inputCorreo.value = data.email ?? data.correo ?? '';
      inputCurso.value = data.curso ?? '';
      modalTitle.textContent = 'Editar estudiante';
      abrirModal();
    } catch (err) {
      console.error(err);
      showAlert('Error al cargar estudiante', 'error');
    }
  }

  // Eliminar
  async function onDelete(e) {
    if (!confirm('¿Eliminar este estudiante?')) return;
    const id = e.currentTarget.dataset.id;
    try {
      const res = await fetch(`${API_URL}/${id}`, { method: 'DELETE', headers: {'Accept': 'application/json'} });
      if (!res.ok) {
        const json = await res.json();
        throw new Error(json.message || 'Error al eliminar');
      }
      showAlert('Estudiante eliminado');
      loadEstudiantes();
    } catch (err) {
      console.error(err);
      showAlert('Error al eliminar', 'error');
    }
  }

  // Inicial
  loadEstudiantes();
});
</script>
@endsection
