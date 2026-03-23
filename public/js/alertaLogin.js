 function confirmarLogout() {
    Swal.fire({
        title: '¿Cerrar sesión?',
        text: "Tendrás que ingresar tus credenciales nuevamente.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#e84118', // Tu color --red
        cancelButtonColor: '#4b2c20',  // Tu color --wood
        confirmButtonText: 'SÍ, SALIR',
        cancelButtonText: 'CANCELAR'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirigimos a la ruta de logout que ya tenés en el index.php
            window.location.href = 'index.php?route=logout';
        }
    });
}