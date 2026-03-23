

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
async function confirmarVentaFinal(btnConfirmar) {

    if (pedido.length === 0) {
        Swal.fire("¡El carrito está vacío!", "", "warning");
        return;
    }

    if (!metodoSeleccionado) {
        Swal.fire("Por favor, selecciona un método de pago.", "", "info");
        return;
    }

    // --- CORRECCIÓN DE CÁLCULO ---
    // Calculamos el subtotal recorriendo el array pedido para evitar el error del $0
    const subtotalCalculado = pedido.reduce((acc, item) => acc + (item.precio * item.cant), 0);
    
    const delivery = parseFloat(document.getElementById('costoDelivery').value) || 0;
    const totalFinal = subtotalCalculado + delivery;
    // -----------------------------

    const data = {
        cliente: {
            nombre: document.getElementById('clienteNombre').value.trim() || "------",
            telefono: document.getElementById('clienteTel').value.trim(),
            direccion: document.getElementById('clienteDir').value.trim(),
            observaciones: document.getElementById('clienteObs').value.trim()
        },
        metodo_pago: metodoSeleccionado,
        subtotal: subtotalCalculado, // Usamos el valor real calculado
        delivery: delivery,
        total: totalFinal,
        items: pedido
    };

    btnConfirmar.disabled = true;
    btnConfirmar.innerText = "⏳ PROCESANDO...";

    try {
        const res = await fetch("index.php?route=pedidos/guardarVenta", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data)
        });

        const response = await res.json();

        if (response.status === "ok") {
            Swal.fire({
                title: '¡Venta Realizada!',
                text: "¿Deseas compartir el comprobante por WhatsApp?",
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '✅ SÍ, COMPARTIR',
                cancelButtonText: '❌ SALIR',
                allowOutsideClick: false
            }).then(async (result) => {

                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Generando comprobante...',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading() }
                    });

                    // ASIGNACIÓN AL TICKET CON FORMATO ARGENTINO
                    document.getElementById('t-fecha').innerText = new Date().toLocaleString();
                    document.getElementById('t-cliente').innerText = data.cliente.nombre;

                    // Usamos los valores calculados arriba para el ticket
                    document.getElementById('t-subtotal').innerText = subtotalCalculado.toLocaleString('es-AR');
                    document.getElementById('t-delivery').innerText = delivery.toLocaleString('es-AR');
                    document.getElementById('t-total').innerText = totalFinal.toLocaleString('es-AR');
                    document.getElementById('t-pago').innerText = metodoSeleccionado;

                    // ITEMS
                    let itemsHtml = "";
                    pedido.forEach(item => {
                        itemsHtml += `
                        <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:14px;">
                            <span><b>${item.cant}x</b> ${item.nombre}</span>
                            <span>$${(item.precio * item.cant).toLocaleString('es-AR')}</span>
                        </div>`;
                    });

                    document.getElementById('t-items').innerHTML = itemsHtml;

                    // GENERAR IMAGEN
                    const element = document.getElementById('ticket-para-imagen');
                    const canvas = await html2canvas(element, {
                        scale: 2,
                        backgroundColor: "#ffffff",
                        useCORS: true
                    });

                    canvas.toBlob(async (blob) => {
                        const item = new ClipboardItem({ "image/png": blob });
                        await navigator.clipboard.write([item]);

                        const telefono = (data.cliente.telefono || "").replace(/\D/g, '');
                        const telFinal = (telefono.length === 10) ? '549' + telefono : telefono;
                        const mensaje = encodeURIComponent("¡Gracias por tu compra en Mugiwara! ");

                        window.open(`https://wa.me/${telFinal}?text=${mensaje}`, '_blank');

                        Swal.fire("¡Listo!", "El comprobante fue copiado. Pégalo en WhatsApp.", "success")
                        .then(() => { 
                            
                            // 1. CERRAR EL MODAL MANUALMENTE
    const modalPagoElement = document.getElementById('modalPago');
    const modalPagoBS = bootstrap.Modal.getInstance(modalPagoElement);
    if (modalPagoBS) modalPagoBS.hide();
                            

    // Limpiar campos de cliente
    document.getElementById('clienteNombre').value = '';
    document.getElementById('clienteTel').value = '';
    document.getElementById('clienteDir').value = '';
    document.getElementById('clienteObs').value = '';


                            finalizarVentaLimpiar(); });
                  }, 'image/png');

                } else {
                    // --- CORRECCIÓN: CERRAMOS EL MODAL AL DARLE A "SALIR" ---
                    const modalPagoElement = document.getElementById('modalPago');
                    const modalPagoBS = bootstrap.Modal.getInstance(modalPagoElement);
                    if (modalPagoBS) {
                        modalPagoBS.hide(); // Cierra el modal de cobro
                    }

                    // Limpiamos los campos del cliente para que no queden datos viejos en la próxima venta
                    document.getElementById('clienteNombre').value = '';
                    document.getElementById('clienteTel').value = '';
                    document.getElementById('clienteDir').value = '';
                    document.getElementById('clienteObs').value = '';

                    // Vaciamos el carrito y reseteamos totales
                    finalizarVentaLimpiar();
                    

                    // Un aviso rápido de que todo salió bien
                    Swal.fire({
                        icon: 'success',
                        title: 'Venta registrada',
                        showConfirmButton: false,
                        timer: 1500
                    });
                           location.reload(); 
                }
            });
        } else {
            Swal.fire("Error", response.message, "error");
        }
    } catch (err) {
        console.error(err);
        Swal.fire("Error", "Error de conexión", "error");
    } finally {
        btnConfirmar.disabled = false;
        btnConfirmar.innerText = "CONFIRMAR VENTA";
    }
}