const URL = 'http://localhost:4000/habitaciones';
let data = null;

const T_DATA_HABITACION = document.getElementById("tDataHabitacion");

// Cargar datos de las habitaciones
async function loadDataHabitacion() {
    try {
        const response = await fetch(URL);
        data = await response.json();

        let html = '';
        data.forEach(habitacion => {
            html += `
            <tr>
                <td>${habitacion.id}</td>
                <td>${habitacion.nombre}</td>
                <td>${habitacion.tipo}</td>
                <td>${habitacion.precio}</td>
                <td>
                    <button type="button" class="btn btn-primary btn-sm" onclick="modificarDataHabitacion(${habitacion.id})">Actualizar</button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteDataHabitacion(${habitacion.id})">Eliminar</button>
                </td>
            </tr>`;
        });

        T_DATA_HABITACION.innerHTML = html;

    } catch (error) {
        console.error(error);
    }
}

// Abrir diálogo de actualización
function modificarDataHabitacion(id) {
    const dataHabitacion = data.find(habitacion => habitacion.id === id);
    document.getElementById("roomNameU").value = dataHabitacion.nombre;
    document.getElementById("roomTypeU").value = dataHabitacion.tipo;
    document.getElementById("roomPriceU").value = dataHabitacion.precio;

    const dialog = document.getElementById("formDataHabitacion");
    dialog.dataset.habitacionId = id;
    dialog.showModal();
}

// Actualizar habitación
async function actualizarDataHabitacion() {
    const dialog = document.getElementById("formDataHabitacion");
    const id = dialog.dataset.habitacionId;

    const dataHabitacion = {
        nombre: document.getElementById("roomNameU").value,
        tipo: document.getElementById("roomTypeU").value,
        precio: document.getElementById("roomPriceU").value
    };

    try {
        const response = await fetch(`${URL}/${id}`, {
            method: "PUT",
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(dataHabitacion)
        });

        if (!response.ok) throw new Error('Error al actualizar habitación');

        dialog.close();
        loadDataHabitacion();

    } catch (error) {
        console.error(error);
    }
}

// Eliminar habitación
async function deleteDataHabitacion(id) {
    try {
        const response = await fetch(`${URL}/${id}`, { method: "DELETE" });
        if (!response.ok) throw new Error('Error al eliminar habitación');

        loadDataHabitacion();

    } catch (error) {
        console.error(error);
    }
}

// Guardar nueva habitación
async function guardarDataHabitacion() {
    const dataHabitacion = {
        nombre: document.getElementById("roomName").value,
        tipo: document.getElementById("roomType").value,
        precio: document.getElementById("roomPrice").value
    };

    try {
        const response = await fetch(URL, {
            method: "POST",
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(dataHabitacion)
        });

        if (!response.ok) throw new Error('Error al guardar habitación');

        loadDataHabitacion();

    } catch (error) {
        console.error(error);
    }
}

// Cerrar diálogo
function cerrarDialogo() {
    const dialog = document.getElementById("formDataHabitacion");
    dialog.close();
}

loadDataHabitacion();
