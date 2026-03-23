<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos - Mugiwara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bangers&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/Sistema_mugiwara/public/css/cssPedido.css">
    <link rel="icon" type="image/x-icon"
        href="/Sistema_mugiwara/public/img/Gemini_Generated_Image_b3vr0wb3vr0wb3vr-removebg-preview.png" />

</head>


<body>

    <div class="topbar">
        <div class="logo-wrapper">
            <img src="/Sistema_mugiwara/public/img/Gemini_Generated_Image_b3vr0wb3vr0wb3vr-removebg-preview.png"
                alt="Logo Mugiwara" class="logo-img logo-animado">
            <a href="index.php?route=" class="logo"> MUGIWARA</a>
        </div>
        <div class="menu">

            <div onclick="cambiar('pedidos')">⚔️ Pedidos</div>

            <div onclick="cambiar('stock')">🍖 Stock <span class="badge"><?php echo $bajoStock ? 'Bajo' : ''; ?></span>
            </div>

            <div onclick="cambiar('precios')">🍳 Precios</div>

            <div onclick="cambiar('caja')">💰 Ganancias</div>

            <div onclick="cambiar('reportes')">📜 Reportes</div>

            <div onclick="cambiar('config')">🛠️ Config</div>


        </div>

    </div>



    <div class="main-container">
        <div class="row g-4">

            <div class="col-lg-7">
                <div class="card-pergamino h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="font-bangers m-0">🍱 MENÚ COMIDAS</h2>
                        <div class="input-group w-50">
                            <span class="input-group-text border-dark bg-white">🔍</span>
                            <input type="text" id="buscadorPlato" class="form-control border-dark"
                                placeholder="Haki de observación (Buscar plato)..." onkeyup="filtrarBusqueda()">
                        </div>
                    </div>

                    <div class="mb-3 d-flex gap-2">
                        <button class="btn btn-sm btn-dark font-bangers"
                            onclick="filtrarCategoria('todos')">TODOS</button>
                        <button class="btn btn-sm btn-outline-dark font-bangers"
                            onclick="filtrarCategoria('pizzas')">PIZZAS</button>
                        <button class="btn btn-sm btn-outline-dark font-bangers"
                            onclick="filtrarCategoria('sandwich')">SÁNDWICH</button>
                        <button class="btn btn-sm btn-outline-dark font-bangers"
                            onclick="filtrarCategoria('bebidas')">BEBIDAS</button>
                    </div>
                    <div class="menu-grid" id="contenedorPlatos">
                        <?php foreach ($platos as $p): ?>
                            <?php
                            $fotoPlato = !empty($p['imagen']) ? $p['imagen'] : 'default.png';
                            $rutaCompleta = "/Sistema_mugiwara/public/img/imagenes_de_comidas/" . $fotoPlato;
                            // Importante: Asegúrate de que $p['categoria'] venga de tu consulta SQL
                            $categoria = strtolower($p['categoria'] ?? 'otros');
                            ?>

                            <div class="card-plato" data-categoria="<?php echo $categoria; ?>"
                                data-nombre="<?php echo strtolower($p['descripcion']); ?>"
                                onclick="agregarPlato(<?php echo $p['id']; ?>, '<?php echo $p['descripcion']; ?>', <?php echo $p['precio_venta']; ?>)">

                                <div class="mb-2">
                                    <img src="<?php echo $rutaCompleta; ?>" alt="<?php echo $p['descripcion']; ?>"
                                        class="rounded-circle border border-2 border-dark shadow-sm"
                                        style="width: 80px; height: 80px; object-fit: cover;">
                                </div>

                                <div class="fw-bold text-uppercase nombre-plato" style="font-size: 0.9rem;">
                                    <?php echo $p['descripcion']; ?>
                                </div>

                                <div class="precio">
                                    $<?php echo number_format($p['precio_venta'], 0, ',', '.'); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card-pergamino h-100 d-flex flex-column">
                    <div class="container-fluid mt-2 text-end">




                        <button class="btn btn-dark font-bangers shadow-sm" onclick="abrirHistorial()">📜 VER VENTAS
                            DEL DÍA</button>
                    </div>
                    <h5 class="font-bangers border-bottom border-dark pb-2">🛒 PEDIDO ACTUAL</h5>


                    <div class="flex-grow-1 overflow-auto mb-3" style="min-height: 250px;">
                        <table class="table table-sm tabla-pedido align-middle">
                            <thead>
                                <tr>
                                    <th>PRODUCTO</th>
                                    <th>CANT.</th>
                                    <th>SUBTOTAL</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="lista-pedido">
                            </tbody>
                        </table>
                    </div>


                    <div class="total-section mb-3">
                        <span class="font-bangers fs-5">TOTAL A COBRAR:</span>
                        <h2 class=" m-0 text-white" id="total-monto">$0</h2>
                    </div>

                    <div class="row g-2">
                        <div class="col-8">
                            <button class="btn btn-success btn-mugiwara w-100" onclick="abrirCobro()">💰 COBRAR
                                (ENTER)</button>
                        </div>
                        <div class="col-4">
                            <button class="btn btn-danger btn-mugiwara w-100" onclick="cancelarPedido()">❌</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalPago" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content card-pergamino p-0 overflow-hidden">
                <div class="modal-header bg-dark text-white border-0">
                    <h5 class="modal-title font-bangers fs-3">🏁 FINALIZAR PEDIDO</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-light">
                    <div class="row g-3">
                        <div class="col-md-7 border-end border-dark pe-md-4">
                            <h6 class="font-bangers text-primary mb-3">📍 DATOS DE ENTREGA / CLIENTE</h6>
                            <div class="mb-2">
                                <label class="small fw-bold">NOMBRE DEL CLIENTE:</label>
                                <input type="text" id="clienteNombre" class="form-control border-dark"
                                    placeholder="Ej: Matias Gimenez Aranda">
                            </div>
                            <div class="mb-2">
                                <label class="small fw-bold">TELÉFONO:</label>
                                <input type="text" id="clienteTel" class="form-control border-dark"
                                    placeholder="Ej: 11 2233 4455">
                            </div>
                            <div class="mb-2">
                                <label class="small fw-bold">DIRECCIÓN:</label>
                                <input type="text" id="clienteDir" class="form-control border-dark"
                                    placeholder="Calle 123, Barrio...">
                            </div>
                            <div class="mb-2">
                                <label class="small fw-bold">OBSERVACIONES:</label>
                                <textarea id="clienteObs" class="form-control border-dark" rows="2"
                                    placeholder="Notas adicionales..."></textarea>
                            </div>
                        </div>

                        <div class="col-md-5 ps-md-4 text-center d-flex flex-column justify-content-between">
                            <div>
                                <h6 class="font-bangers text-danger mb-1">💰 TOTAL A PAGAR</h6>
                                <h1 class=" display-4 mb-3" style="font-weight: bold" id="total-modal">$0</h1>
                                <div class="mt-3 p-3 border border-warning rounded bg-white shadow-sm">
                                    <div class="form-check form-switch d-flex align-items-center gap-2">
                                        <input class="form-check-input border-dark" type="checkbox" id="checkDelivery"
                                            onchange="toggleDelivery()">
                                        <label class="form-check-label fw-bold text-danger" for="checkDelivery">¿INCLUYE
                                            DELIVERY? 🛵</label>
                                    </div>
                                    <div id="divCostoDelivery" class="mt-2" style="display: none;">
                                        <label class="small fw-bold">COSTO DE ENVÍO ($):</label>
                                        <input type="number" id="costoDelivery" class="form-control border-dark"
                                            placeholder="0" onkeyup="sumarDeliveryAlTotal()">
                                    </div>
                                </div>

                                <label class="small fw-bold d-block mb-2">MÉTODO DE PAGO:</label>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-outline-dark fw-bold btn-pago "
                                        onclick="seleccionarPago(this, 'Efectivo')">💵 EFECTIVO</button>
                                    <button class="btn btn-outline-primary fw-bold btn-pago"
                                        onclick="seleccionarPago(this, 'Transferencia bancaria')">📱 TRANSFERENCIA
                                        BANCARIA</button>
                                    <button class="btn btn-outline-info fw-bold btn-pago"
                                        onclick="seleccionarPago(this, 'Mercado pago')">💳 MERCADO PAGO</button>
                                </div>
                            </div>

                            <button class="btn btn-success btn-mugiwara fs-4 mt-4 w-100 py-3"
                                onclick="confirmarVentaFinal(this)">
                                CONFIRMAR VENTA
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalHistorial" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content card-pergamino p-0">
                <div class="modal-header bg-dark text-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="modal-title font-bangers fs-3">📊 REGISTRO DE VENTAS</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-warning font-bangers px-3" onclick="cargarVentas('HOY')">📅
                            HOY</button>
                        <button class="btn btn-sm btn-outline-light font-bangers px-3"
                            onclick="cargarVentas('TODOS')">🌎 TODOS</button>
                        <button type="button" class="btn-close btn-close-white ms-3" data-bs-dismiss="modal"></button>
                    </div>
                </div>

                <div class="modal-body">
                    <div id="contenedorStats" class="row g-3 mb-4 text-center">
                        <div class="col-md-6">
                            <div class="p-3 bg-success text-white rounded shadow-sm">
                                <h6 class="m-0 font-bangers">VENTAS <span id="labelFiltro">DEL DÍA</span></h6>
                                <h2 class="m-0 " id="totalVentasDia">$0</h2>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-primary text-white rounded shadow-sm">
                                <h6 class="m-0 font-bangers">GANANCIA ESTIMADA</h6>
                                <h2 class="m-0 " id="totalGananciaDia">$0</h2>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle border-dark">
                            <thead class="table-dark">
                                <tr class="font-bangers">
                                    <th>FECHA/HORA</th>
                                    <th>DIRECCIÓN</th>
                                    <th>PRODUCTOS</th>
                                    <th>PAGO</th>
                                    <th>TOTAL</th>
                                    <th>GANANCIA</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="tablaVentasCuerpo" class="fw-bold">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

    <div id="ticket-para-imagen"
        style="position: absolute; left: -9999px; width: 450px; background: #f8f9fa; padding: 0; color: #333; font-family: 'Montserrat', sans-serif; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">

        <div
            style="position: relative; background: url('/Sistema_mugiwara/public/img/Gemini_Generated_Image_b3vr0wb3vr0wb3vr-removebg-preview.png') center/cover; padding: 50px 30px; text-align: center; color: #fff;">

            <div
                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); z-index: 1;">
            </div>

            <div style="position: relative; z-index: 2;">
                <h2
                    style="margin: 0; letter-spacing: 4px; text-transform: uppercase; font-size: 35px; font-weight: 900; text-shadow: 2px 2px 10px rgba(0,0,0,0.8), -2px -2px 10px rgba(0,0,0,0.8); color: #ffc107;">
                    MUGIWARA
                </h2>
                <p
                    style="margin: 5px 0 0; font-size: 16px; font-weight: 700; text-shadow: 1px 1px 5px rgba(0,0,0,0.8);">
                    ¡Gracias por tu pedido!
                </p>
            </div>
        </div>

        <div style="padding: 30px; background: #ffffff;">
            <div
                style="display: flex; justify-content: space-between; margin-bottom: 25px; font-size: 13px; color: #666; border-bottom: 1px solid #eee; padding-bottom: 15px;">
                <div>
                    <strong>FECHA:</strong> <br> <span id="t-fecha" style="color: #333;"></span>
                </div>
                <div style="text-align: right;">
                    <strong>CLIENTE:</strong> <br> <span id="t-cliente" style="color: #333;"></span>
                </div>
            </div>

            <h4
                style="margin: 0 0 15px; font-size: 16px; color: #212529; text-transform: uppercase; border-left: 5px solid #ffc107; padding-left: 10px; font-weight: 700;">
                Resumen del Pedido
            </h4>

            <div id="t-items" style="min-height: 50px;">
            </div>

            <div style="margin: 25px 0; border-top: 2px dashed #dee2e6;"></div>

            <div style="background: #f8f9fa; padding: 25px; border-radius: 15px; border: 1px solid #eee;">

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <span style="font-size: 14px; color: #666;">Método de Pago:</span>
                    <span id="t-pago"
                        style="font-weight: 700; color: #fff; background: #343a40; padding: 5px 12px; border-radius: 6px; font-size: 11px; text-transform: uppercase;"></span>
                </div>

                <div style="margin:15px 0;border-top:1px dashed #dee2e6;"></div>

                <div style="display:flex;justify-content:space-between;font-size:15px;margin-bottom:6px;">
                    <span>Subtotal</span>
                    <span>$<span id="t-subtotal"></span></span>
                </div>

                <div style="display:flex;justify-content:space-between;font-size:15px;margin-bottom:10px;">
                    <span>Delivery</span>
                    <span>$<span id="t-delivery"></span></span>
                </div>

                <div style="margin:10px 0;border-top:1px dashed #dee2e6;"></div>

                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:20px;font-weight:700;color:#212529;">TOTAL</span>
                    <span style="font-size:30px;font-weight:900;color:#198754;">
                        $<span id="t-total"></span>
                    </span>
                </div>

            </div>
        </div>

        <div
            style="text-align: center; padding: 20px; background: #fff; font-size: 12px; color: #adb5bd; border-top: 1px solid #f1f1f1;">
            <div style="margin-bottom: 5px; letter-spacing: 1px;">¡GRACIAS POR TU COMPRA!</div>
            <div class="desarrollo">Desarrollado por JuanDev</div>
        </div>
    </div>






    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/Sistema_mugiwara/public/js/pedidos/funciones_de_pedidos.js"></script>
    <script src="/Sistema_mugiwara/public/js/redireccion.js"></script>
    <script src="/Sistema_mugiwara/public/js/pedidos/funciones_de_pedidos_2.js"></script>
</body>


</html>