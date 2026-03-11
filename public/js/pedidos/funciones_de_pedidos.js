

        let pedido = [];
        let total = 0;

        function agregarPlato(nombre, precio) {
            const index = pedido.findIndex(item => item.nombre === nombre);
            if (index > -1) {
                pedido[index].cant++;
            } else {
                pedido.push({ nombre, precio, cant: 1 });
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
            if(confirm('¿Borrar el pedido actual?')) {
                pedido = [];
                renderizarPedido();
            }
        }

        function abrirCobro() {
            if(total === 0) return alert('¡El pedido está vacío!');
            document.getElementById('total-modal').innerText = `$${total}`;
            new bootstrap.Modal(document.getElementById('modalPago')).show();
        }

        function finalizarVenta(metodo) {
            alert(`Venta registrada ($${total}) por ${metodo}. Stock actualizado.`);
            pedido = [];
            renderizarPedido();
            bootstrap.Modal.getInstance(document.getElementById('modalPago')).hide();
        }
    