<!-- Lo que hace este archivo es crear un modal de confirmacion de eliminacion -->
 
<div id="modalEliminar" class="modal-eliminar-overlay" style="display:none;">
    <div class="modal-eliminar-content">
        <span class="modal-eliminar-close" title="Cerrar" onclick="cerrarModalEliminar()">&times;</span>
        <h3>¿Seguro que quieres eliminar?</h3>
        <p id="modalEliminarMensaje" style="color: #c6c6c6; margin-top: 10px;">Esta acción no se puede deshacer.</p>
        <div class="modal-eliminar-actions">
            <button id="btnConfirmarEliminar" class="btn btn-danger">Sí</button>
            <button class="btn btn-secondary" onclick="cerrarModalEliminar()">No</button>
        </div>
    </div>
</div>

<style>
.modal-eliminar-overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.6); display: flex; align-items: center; justify-content: center; z-index: 9999;
}
.modal-eliminar-content {
    background: #1e1e1e; border-radius: 8px; padding: 25px; width: 350px;
    text-align: center; position: relative; border-top: 4px solid #e74c3c;
    box-shadow: 0 4px 15px rgba(0,0,0,0.5); color: #fff; font-family: 'Poppins', sans-serif;
}
.modal-eliminar-close {
    position: absolute; top: 10px; left: 15px; font-size: 24px;
    cursor: pointer; color: #aaa; transition: 0.3s; line-height: 1;
}
.modal-eliminar-close:hover { color: #e74c3c; }
.modal-eliminar-actions { margin-top: 25px; display: flex; justify-content: center; gap: 15px; }
.modal-eliminar-actions .btn { min-width: 100px; padding: 8px 15px; font-weight: 600; }
</style>

<script>
let urlEliminar = '';
function confirmarEliminarCustom(url, mensaje) {
    urlEliminar = url;
    if(mensaje) { document.getElementById('modalEliminarMensaje').innerText = mensaje; }
    document.getElementById('modalEliminar').style.display = 'flex';
}
function cerrarModalEliminar() {
    document.getElementById('modalEliminar').style.display = 'none';
    urlEliminar = '';
}
document.getElementById('btnConfirmarEliminar').onclick = function() {
    if(urlEliminar) window.location.href = urlEliminar;
};
</script>
