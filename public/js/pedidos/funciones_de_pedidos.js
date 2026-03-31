

        let pedido = [];
        let total = 0;

     function agregarPlato(id, nombre, precio) { // Agregamos id
    const index = pedido.findIndex(item => item.id === id);
    if (index > -1) {
        pedido[index].cant++;
    } else {
        pedido.push({ id, nombre, precio, cant: 1 }); // Guardamos el id
    }
    renderizarPedido();
}

        function renderizarPedido() {
            const lista = document.getElementById('lista-pedido');
            lista.innerHTML = '';
            total = 0;

            pedido.forEach((item, i) => {
                const subtotal = item.precio * item.cant;
                total += subtotal;
                lista.innerHTML += `
                    <tr>
                        <td><strong>${item.nombre}</strong></td>
                        <td>
                            <span class="badge-cantidad">${item.cant}</span>
                        </td>
                        <td>$${subtotal}</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-danger py-0" onclick="eliminarItem(${i})">x</button>
                        </td>
                    </tr>
                `;
            });
            document.getElementById('total-monto').innerText = `$${total}`;
        }

        function eliminarItem(i) {
            pedido.splice(i, 1);
            renderizarPedido();
        }

       function cancelarPedido() {
    Swal.fire({
        title: '¿Borrar el pedido actual?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545', // Rojo para borrar
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'SÍ, BORRAR',
        cancelButtonText: 'NO, VOLVER'
    }).then((result) => {
        if (result.isConfirmed) {
            pedido = [];
            renderizarPedido();
            
            // Opcional: un aviso rápido de que se borró
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Pedido borrado',
                showConfirmButton: false,
                timer: 1500
            });
        }
    });
}

function abrirCobro() {
    // Si el total es 0, mostramos una alerta de error/info
    if (total === 0) {
        Swal.fire({
            title: '¡El pedido está vacío!',
            text: 'Agregá al menos un producto para cobrar',
            icon: 'info',
            confirmButtonColor: '#198754'
        });
        return;
    }

    // Si hay productos, mostramos el modal de pago
    document.getElementById('total-modal').innerText = `$${total}`;
    const modalPago = new bootstrap.Modal(document.getElementById('modalPago'));
    modalPago.show();
}

        function finalizarVenta(metodo) {
            alert(`Venta registrada ($${total}) por ${metodo}. Stock actualizado.`);
            pedido = [];
            renderizarPedido();
            bootstrap.Modal.getInstance(document.getElementById('modalPago')).hide();
        }

        