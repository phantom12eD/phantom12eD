document.addEventListener('DOMContentLoaded', function () {
    // Mostrar el mensaje emergente al cargar la página (puedes modificar esto según tus necesidades)
    mostrarMensajeEmergente();

    // Función para mostrar el mensaje emergente
    function mostrarMensajeEmergente() {
        var mensajeEmergente = document.getElementById('mensajeEmergente');
        mensajeEmergente.style.display = 'block';

        // Ocultar el mensaje después de 3 segundos (puedes ajustar el tiempo)
        setTimeout(function () {
            mensajeEmergente.style.display = 'none';
        }, 3000);
    }
});
