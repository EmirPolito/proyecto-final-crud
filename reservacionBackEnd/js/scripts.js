const URL = 'http://localhost:4000/clientes';
let data = null;

const T_DATA_CLIENTE = document.getElementById("tDataCliente");

// Cargar datos del cliente
async function loadDataCliente() {
    try {
        const response = await fetch(URL);
        data = await response.json();

        let html = '';
        data.forEach(cliente => {
            html += `
            <tr>
                <td>${cliente.id}</td>
                <td>${cliente.nombre}</td>
                <td>${cliente.correo}</td>
                <td>${cliente.telefono}</td>
                <td>
                    <button type="button" class="btn btn-primary btn-sm" onclick="modificarDataCliente(${cliente.id})">Actualizar</button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteDataCliente(${cliente.id})">Eliminar</button>
                </td>
            </tr>`;
        });

        T_DATA_CLIENTE.innerHTML = html;

    } catch (error) {
        console.error(error);
    }
}


// Abrir diálogo de actualización
function modificarDataCliente(id) {
    const dataCliente = data.find(cliente => cliente.id === id);
    document.getElementById("clientNameU").value = dataCliente.nombre;
    document.getElementById("clientEmailU").value = dataCliente.correo;
    document.getElementById("clientPhoneU").value = dataCliente.telefono;

    const dialog = document.getElementById("formDataCliente");
    dialog.dataset.clienteId = id;
    dialog.showModal();
}

// Actualizar cliente
async function actualizarDataCliente() {
    const dialog = document.getElementById("formDataCliente");
    const id = dialog.dataset.clienteId;

    const dataCliente = {
        nombre: document.getElementById("clientNameU").value,
        correo: document.getElementById("clientEmailU").value,
        telefono: document.getElementById("clientPhoneU").value
    };

    try {
        const response = await fetch(`${URL}/${id}`, {
            method: "PUT",
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(dataCliente)
        });

        if (!response.ok) throw new Error('Error al actualizar cliente');

        dialog.close();
        loadDataCliente();

    } catch (error) {
        console.error(error);
    }
}

// Eliminar cliente
async function deleteDataCliente(id) {
    try {
        const response = await fetch(`${URL}/${id}`, { method: "DELETE" });
        if (!response.ok) throw new Error('Error al eliminar cliente');

        loadDataCliente();

    } catch (error) {
        console.error(error);
    }
}

// Guardar nuevo cliente
async function guardarDataCliente() {
    const dataCliente = {
        nombre: document.getElementById("clientName").value,
        correo: document.getElementById("clientEmail").value,
        telefono: document.getElementById("clientPhone").value
    };

    try {
        const response = await fetch(URL, {
            method: "POST",
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(dataCliente)
        });

        if (!response.ok) throw new Error('Error al guardar cliente');

        loadDataCliente();

    } catch (error) {
        console.error(error);
    }
}

// Cerrar diálogo
function cerrarDialogo() {
    const dialog = document.getElementById("formDataCliente");
    dialog.close();
}


loadDataCliente();
