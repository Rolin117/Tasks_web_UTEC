
function mostrarFechaHora() {
    const ahora = new Date();

    const fecha = ahora.toLocaleDateString('es-SV');
    const hora = ahora.toLocaleTimeString('es-SV');

    const elemento = document.getElementById('fecha-hora');

    if (elemento) {
        elemento.textContent = `Fecha: ${fecha} | Hora: ${hora}`;
    }
}

mostrarFechaHora();

setInterval(mostrarFechaHora, 1000);