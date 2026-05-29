function confirmarEliminacionSweet(boton) {
    const formulario = boton.closest('.form-eliminar');

    Swal.fire({
        title: '⚠️ ¿Estás seguro?',
        html: `¿Estás seguro de eliminar este registro?<br><br><b>Nota:</b> Luego de eliminar deberá ir al módulo 
        de PRECIOS y PROMOS para actualizar los valores manualmente.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            formulario.submit(); // Se envía al controlador
        }
    });
}

