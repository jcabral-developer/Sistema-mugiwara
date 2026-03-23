
        // Lógica para seleccionar el pago visualmente
        let metodoSeleccionado = 'Efectivo';
        function seleccionarPago(btn, metodo) {
            document.querySelectorAll('.btn-pago').forEach(b => b.classList.remove('active', 'btn-dark', 'btn-primary', 'btn-info'));
            btn.classList.add('active');
            metodoSeleccionado = metodo;
        }

        // Función para abrir el historial y cargar datos (Ejemplo visual)
        function abrirHistorial() {
            const myModal = new bootstrap.Modal(document.getElementById('modalHistorial'));
            myModal.show();

            cargarVentas('HOY');
        }

        // Función para procesar la venta con todos los campos nuevos



    

        function filtrarBusqueda() {
            const texto = document.getElementById('buscadorPlato').value.toLowerCase();
            const platos = document.querySelectorAll('.card-plato');

            platos.forEach(plato => {
                const nombre = plato.getAttribute('data-nombre');
                // Si el nombre incluye el texto, se muestra, si no, se oculta
                if (nombre.includes(texto)) {
                    plato.style.display = "block";
                } else {
                    plato.style.display = "none";
                }
            });
        }

        function filtrarCategoria(cat) {
            const platos = document.querySelectorAll('.card-plato');

            // Cambiar estilo de botones (opcional para feedback visual)
            document.querySelectorAll('.btn-outline-dark, .btn-dark').forEach(btn => {
                btn.classList.replace('btn-dark', 'btn-outline-dark');
            });
            event.target.classList.replace('btn-outline-dark', 'btn-dark');

            platos.forEach(plato => {
                const categoriaPlato = plato.getAttribute('data-categoria');

                if (cat === 'todos' || categoriaPlato === cat) {
                    plato.style.display = "block";
                } else {
                    plato.style.display = "none";
                }
            });
        }

        function cargarVentas(tipoFiltro) {
            const cuerpo = document.getElementById('tablaVentasCuerpo');
            const label = document.getElementById('labelFiltro');

            label.innerText = (tipoFiltro === 'HOY') ? "DEL DÍA" : "TOTALES";

            // Ajustamos el colspan a 7 porque agregamos la columna del botón
            cuerpo.innerHTML = '<tr><td colspan="7" class="text-center py-4">🔍 Buscando en el Grand Line...</td></tr>';

            const requestBody = { datos: tipoFiltro };

            fetch("index.php?route=pedidos/traerVentas", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(requestBody)
            })
                .then(res => res.json())
                .then(data => {
                    cuerpo.innerHTML = '';

                    if (data.status === "ok" && data.ventas.length > 0) {
                        document.getElementById('totalVentasDia').innerText = `$${data.totalSumado}`;
                        document.getElementById('totalGananciaDia').innerText = `$${data.gananciaSumada}`;

                        data.ventas.forEach((v, index) => {
                            let productos = v.productos.split(",").join("<br>");
                            // ID único para que cada botón abra su propio detalle
                            const idDetalle = `detalle_${index}`;

                            cuerpo.innerHTML += `
                    <tr>
                        <td><small>${v.fecha}</small></td>
                        <td>${v.direccion || 'Sin dirección asignada'}</td>
                        <td><span class="badge bg-secondary">${v.productos.split(",").length} Items</span></td>
                        <td><span class="badge bg-dark">${v.metodo_pago}</span></td>
                        <td>$${v.total}</td>
                        <td class="text-success">+$${v.ganancia}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning shadow-sm" 
                                    type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#${idDetalle}">
                                ➕
                            </button>
                        </td>
                    </tr>
                    <tr class="collapse border-start border-warning border-3" id="${idDetalle}">
                        <td colspan="7" class="bg-light p-3">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <h6 class="text-dark fw-bold border-bottom pb-1">👤 CLIENTE</h6>
                                    <p class="mb-1 text-uppercase"><b>Nombre:</b> ${v.cliente_nombre || v.cliente || 'No registrado'}</p>
                                    <p class="mb-0"><b>Tel:</b> ${v.telefono || '---'}</p>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="text-dark fw-bold border-bottom pb-1">📝 DETALLE PEDIDO</h6>
                                    <div class="small">${productos}</div>
                                    <div class="mt-2 text-muted small italic"><b>Delivery:</b> $${v.delivery}</div>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="text-dark fw-bold border-bottom pb-1">💬 OBSERVACIONES</h6>
                                    <div class="p-2 bg-white rounded border small italic">
                                        ${v.observaciones || 'Sin comentarios.'}
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
                        });

                    } else {
                        cuerpo.innerHTML = '<tr><td colspan="7" class="text-center">No se encontraron ventas.</td></tr>';
                        document.getElementById('totalVentasDia').innerText = "$0";
                        document.getElementById('totalGananciaDia').innerText = "$0";
                    }
                })
                .catch(err => {
                    console.error("Error al traer ventas:", err);
                    cuerpo.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Error al conectar con el servidor.</td></tr>';
                });
        }


        ////////////////////////////


        let totalOriginal = 0; // Para guardar el total antes del delivery

        function toggleDelivery() {
            const divCosto = document.getElementById('divCostoDelivery');
            const inputCosto = document.getElementById('costoDelivery');
            const isChecked = document.getElementById('checkDelivery').checked;

            if (isChecked) {
                divCosto.style.display = 'block';
                inputCosto.focus();
            } else {
                divCosto.style.display = 'none';
                inputCosto.value = '';
                actualizarTotalConEnvio(0); // Volver al total original
            }
        }

        function sumarDeliveryAlTotal() {
            const costo = parseInt(document.getElementById('costoDelivery').value) || 0;
            actualizarTotalConEnvio(costo);
        }

        function actualizarTotalConEnvio(envio) {
            // Obtenemos el total del pedido actual (sin el envío anterior si lo hubiera)
            // Supongamos que tu variable global 'totalMonto' tiene el valor base
            const totalBase = parseFloat(
                document.getElementById('total-monto')
                    .innerText.replace('$', '')
            );
            const nuevoTotal = totalBase + envio;

            // Actualizamos los textos del modal y de la pantalla principal
            document.getElementById('total-modal').innerText = `$${nuevoTotal.toLocaleString('es-AR')}`;
        }
        ////////////////////////////

        function finalizarVentaLimpiar() {
            // 1. Vaciar el array de datos
            pedido = [];

            // 2. Limpiar la tabla/lista en el HTML (la que ve tu cuñado en el panel)
            // Asegurate de usar el ID correcto de tu tabla de productos
            const listaProductos = document.getElementById('lista-pedido');
            if (listaProductos) {
                listaProductos.innerHTML = "";
            }

            // 3. Resetear los totales en la pantalla principal
            const totalMonto = document.getElementById('total-monto');
            if (totalMonto) {
                totalMonto.innerText = "$0";
            }

            // 4. Resetear los campos del cliente y delivery en el modal
            if (document.getElementById('clienteNombre')) document.getElementById('clienteNombre').value = '';
            if (document.getElementById('clienteTel')) document.getElementById('clienteTel').value = '';
            if (document.getElementById('clienteDir')) document.getElementById('clienteDir').value = '';
            if (document.getElementById('clienteObs')) document.getElementById('clienteObs').value = '';

            // Resetear el switch de delivery si existe
            const checkDelivery = document.getElementById('checkDelivery');
            checkDelivery.checked = false;
            document.getElementById('divCostoDelivery').style.display = 'none';
            document.getElementById('costoDelivery').value = '';
            document.getElementById('total-modal').value = '0';

            // 5. Resetear método de pago seleccionado
            metodoSeleccionado = null;
            document.querySelectorAll('.btn-metodo-pago').forEach(btn => btn.classList.remove('active'));

            console.log("Sistema Mugiwara: Lista y totales reseteados.");
            location.reload();
        }