document.addEventListener('DOMContentLoaded', () => {

    const formLogin = document.getElementById('formLogin');
    const mensajeJS = document.getElementById('mensajeJS');

    if (formLogin) {
        formLogin.addEventListener('submit', function(e) {
            const correo = document.getElementById('correo').value.trim();
            const password = document.getElementById('password').value.trim();

            mensajeJS.classList.add('hidden');
            mensajeJS.innerHTML = "";

            // Validación simple Frontend
            if (correo === '' || password === '') {
                e.preventDefault(); // Evitar el envío
                mensajeJS.innerHTML = "Todos los campos son obligatorios.";
                mensajeJS.classList.remove('hidden');
                return;
            }

            // Expresión regular simple para correo
            const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!regexCorreo.test(correo)) {
                e.preventDefault(); 
                mensajeJS.innerHTML = "Ingresa un correo válido.";
                mensajeJS.classList.remove('hidden');
                return;
            }

            // Si pasa todo, el formulario continúa su curso al backend.
        });
    }

});
