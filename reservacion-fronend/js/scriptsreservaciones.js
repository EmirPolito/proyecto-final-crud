const URL_RESERVACIONES = 'http://localhost:4000/reservaciones';
const T_DATA_RESERVACIONES = document.getElementById("tDataReservaciones");

let reservaciones = [];
let reservacionSeleccionada = null;

// Cargar datos de reservaciones
async function loadReservaciones() {
    try {
        const response = await fetch(URL_RESERVACIONES);
        reservaciones = await response.json();

        let html = '';
        reservaciones.forEach(reservacion => {
            html += `
            <tr>
                <td>${reservacion.folio}</td>
                <td>${reservacion.nombrecliente}</td>
                <td>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#updateReservacion" onclick="seleccionarReservacion(${reservacion.id})">Actualizar</button>
                    <button class="btn btn-danger btn-sm" onclick="eliminarReservacion(${reservacion.id})">Eliminar</button>
                </td>
            </tr>`;
        });

        T_DATA_RESERVACIONES.innerHTML = html;

    } catch (error) {
        console.error('Error al cargar las reservaciones:', error);
    }
}

// Guardar nueva reservación
async function guardarReservacion() {
    const folio = document.getElementById("folioReservacion").value;
    const nombrecliente = document.getElementById("clienteReservacion").value;

    const data = { folio, nombrecliente };

    try {
        const response = await fetch(URL_RESERVACIONES, {
            method: "POST",
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });

        if (!response.ok) throw new Error('Error al guardar reservación');

        loadReservaciones();
        limpiarFormulario('formDataReservacion');

    } catch (error) {
        console.error('Error al guardar reservación:', error);
    }
}

// Seleccionar reservación para actualizar
function seleccionarReservacion(id) {
    reservacionSeleccionada = reservaciones.find(res => res.id === id);
    document.getElementById("folioReservacionU").value = reservacionSeleccionada.folio;
    document.getElementById("clienteReservacionU").value = reservacionSeleccionada.nombrecliente;
}

// Actualizar reservación
async function actualizarReservacion() {
    const folio = document.getElementById("folioReservacionU").value;
    const nombrecliente = document.getElementById("clienteReservacionU").value;

    const data = { folio, nombrecliente };

    try {
        const response = await fetch(`${URL_RESERVACIONES}/${reservacionSeleccionada.id}`, {
            method: "PUT",
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });

        if (!response.ok) throw new Error('Error al actualizar reservación');

        loadReservaciones();
        limpiarFormulario('updateReservacion');

    } catch (error) {
        console.error('Error al actualizar reservación:', error);
    }
}

// Eliminar reservación
async function eliminarReservacion(id) {
    try {
        const response = await fetch(`${URL_RESERVACIONES}/${id}`, { method: "DELETE" });
        if (!response.ok) throw new Error('Error al eliminar reservación');

        loadReservaciones();

    } catch (error) {
        console.error('Error al eliminar reservación:', error);
    }
}

// Limpiar formulario y cerrar modal
function limpiarFormulario(modalId) {
    document.getElementById(modalId).querySelectorAll("input").forEach(input => input.value = '');
    const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
    modal.hide();
}

// Inicialización
loadReservaciones();
