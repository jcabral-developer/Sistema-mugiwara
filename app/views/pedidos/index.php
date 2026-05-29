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
    <style>
        /* Estilos Piratas para el Modal de Ingredientes Especiales */
        .modal-pirata .modal-content {
            background: #f4eccf;
            /* Color pergamino idéntico a tus cards */
            border: 4px solid #5c3a21;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
        }

        .modal-pirata .modal-header {
            background: #1a1a1a;
            border-bottom: 3px solid #ffc107;
        }

        .item-ingrediente {
            background: rgba(255, 255, 255, 0.6);
            border: 2px solid #5c3a21;
            border-radius: 8px;
            padding: 10px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .item-ingrediente:hover {
            background: #ffc107;
            color: #000;
        }

        .item-ingrediente input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        /* Subtabla para los extras desplegables */
        .fila-extras {
            background: rgba(0, 0, 0, 0.05);
            font-size: 0.85rem;
        }

        .badge-extra {
            background-color: #5c3a21;
            color: #ffc107;
            font-family: 'Montserrat', sans-serif;
        }

        .btn-desplegar {
            background: none;
            border: none;
            font-size: 0.8rem;
            color: #5c3a21;
            transition: transform 0.2s;
        }

        .btn-desplegar.abierto {
            transform: rotate(180deg);
        }
    </style>
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

            <div onclick="cambiar('promos')">🔖 Promos</div>

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
                        <button class="btn btn-sm btn-dark font-bangers" onclick="filtrarCategoria('todos')">TODOS</button>
                        <button class="btn btn-sm btn-outline-dark font-bangers" onclick="filtrarCategoria('pizzas')">PIZZAS</button>
                        <button class="btn btn-sm btn-outline-dark font-bangers" onclick="filtrarCategoria('sandwich')">SÁNDWICH</button>
                        <button class="btn btn-sm btn-outline-dark font-bangers" onclick="filtrarCategoria('bebidas')">BEBIDAS</button>
                    </div>

                    <div class="menu-grid" id="contenedorPlatos">
                        <?php foreach ($platos as $p): ?>
                            <?php
                            $fotoPlato = !empty($p['imagen']) ? $p['imagen'] : 'default.png';
                            $carpeta = !empty($p['esPromo']) ? 'promos' : 'imagenes_de_comidas';
                            $rutaCompleta = "/Sistema_mugiwara/public/img/$carpeta/" . $fotoPlato;
                            $categoria = strtolower($p['categoria'] ?? 'otros');
                            ?>

                            <div class="card-plato" data-categoria="<?php echo $categoria; ?>"
                                data-nombre="<?php echo strtolower($p['descripcion']); ?>"
                                onclick="agregarPlato('<?php echo $p['id']; ?>', '<?php echo addslashes($p['descripcion']); ?>', <?php echo $p['precio_venta']; ?>)">

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
                        <button class="btn btn-sm btn-warning font-bangers px-3 btnFiltro"
                            onclick="cargarVentas('HOY')">📅 HOY</button>
                        <button class="btn btn-sm btn-outline-light font-bangers px-3 btnFiltro"
                            onclick="cargarVentas('SEMANA')">🌎 SEMANA</button>
                        <button class="btn btn-sm btn-outline-light font-bangers px-3 btnFiltro"
                            onclick="cargarVentas('MES')">🌎 MES</button>
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
<!-- 
    MODAL PARA INGREDIENTES -->
    <div class="modal fade modal-pirata" id="modalIngredientesEspeciales" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <h5 class="modal-title font-bangers fs-3 text-warning">🧪 CONFIGURAR PIZZA / EXTRAS</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-0">
                    <ul class="nav nav-tabs nav-justified bg-dark" id="modalTabs" role="tablist" style="border-bottom: 2px solid #ffc107;">
                        <li class="nav-item" role="presentation">
                            <button class="nav-content-btn active text-warning fw-bold py-3 w-100 border-0 bg-transparent"
                                id="tab-extras-tab" data-bs-toggle="tab" data-bs-target="#tab-extras"
                                type="button" role="tab" aria-controls="tab-extras" aria-selected="true" onclick="setModoModal('entera')">
                                ➕ AGREGAR EXTRAS
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-content-btn text-white fw-bold py-3 w-100 border-0 bg-transparent"
                                id="tab-mitad-tab" data-bs-toggle="tab" data-bs-target="#tab-mitad"
                                type="button" role="tab" aria-controls="tab-mitad" aria-selected="false" onclick="setModoModal('mitad')">
                                🍕 PIZZA MITAD Y MITAD
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content p-3" id="modalTabsContent">

                        <div class="tab-pane fade show active" id="tab-extras" role="tabpanel" aria-labelledby="tab-extras-tab">
                            <p class="fw-bold text-dark text-center mb-3">Selecciona los ingredientes especiales para este plato:</p>
                            <div id="contenedorIngredientesCheck" class="d-flex flex-column gap-2" style="max-height: 300px; overflow-y: auto;">
                                <?php if (!empty($ingredientesEspeciales)): ?>

                                    <?php foreach ($ingredientesEspeciales as $ing): ?>

                                        <div class="item-ingrediente d-flex justify-content-between align-items-center"
                                            onclick="toggleCheck(this)">

                                            <div class="d-flex align-items-center gap-3">

                                              <input type="checkbox"
    class="form-check-input border-dark check-especial"
    value="<?= $ing['insumo_id'] ?>"
    data-nombre="<?= htmlspecialchars($ing['insumo']) ?>"
    data-precio="<?= $ing['precio_extra'] ?>"
    data-cantidad="<?= $ing['cantidad'] ?>"
    data-unidad="<?= $ing['unidad'] ?>"
    onclick="event.stopPropagation();">

                                                <span class="fw-bold text-uppercase text-dark">
                                                    <?= htmlspecialchars($ing['insumo']) ?>
                                                </span>

                                            </div>

                                            <span class="badge bg-dark text-warning rounded-pill">
                                                <?= $ing['cantidad'] ?> <?= $ing['unidad'] ?>
                                            </span>

                                        </div>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <p>No hay ingredientes especiales registrados.</p>

                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-mitad" role="tabpanel" aria-labelledby="tab-mitad-tab">
                            <p class="fw-bold text-dark text-center mb-3">Armá la combinación combinando dos sabores de pizza:</p>

                            <div class="row g-3">

                                <!-- MITAD IZQUIERDA -->
                                <div class="col-md-6 style-mitad-izq">
                                    <label class="fw-bold text-dark small mb-1">
                                        👈 PRIMERA MITAD:
                                    </label>

                                    <select id="selectSaborIzq"
                                        class="form-select border-dark fw-bold text-uppercase">

                                        <!-- SIN EXTRA -->
                                        <option value="" data-precio="0">
                                            🍕 Normal (Sin ingrediente especial)
                                        </option>

                                        <?php if (!empty($ingredientesEspeciales)): ?>

                                            <?php foreach ($ingredientesEspeciales as $ing): ?>

                                                <<option
    value="<?= $ing['insumo_id'] ?>"
    data-nombre="<?= htmlspecialchars($ing['insumo']) ?>"
    data-precio="<?= $ing['precio_extra'] ?>"
    data-cantidad="<?= $ing['cantidad'] ?>"
    data-unidad="<?= $ing['unidad'] ?>">

    <?= ucfirst(htmlspecialchars($ing['insumo'])) ?>

</option>
                                            <?php endforeach; ?>

                                        <?php endif; ?>

                                    </select>
                                </div>

                                <!-- MITAD DERECHA -->
                                <div class="col-md-6">
                                    <label class="fw-bold text-dark small mb-1">
                                        👉 SEGUNDA MITAD:
                                    </label>

                                    <select id="selectSaborDer"
                                        class="form-select border-dark fw-bold text-uppercase">

                                        <!-- SIN EXTRA -->
                                        <option value="" data-precio="0">
                                            🍕 Normal (Sin ingrediente especial)
                                        </option>

                                        <?php if (!empty($ingredientesEspeciales)): ?>

                                            <?php foreach ($ingredientesEspeciales as $ing): ?>

                                         <option
    value="<?= $ing['insumo_id'] ?>"
    data-nombre="<?= htmlspecialchars($ing['insumo']) ?>"
    data-precio="<?= $ing['precio_extra'] ?>"
    data-cantidad="<?= $ing['cantidad'] ?>"
    data-unidad="<?= $ing['unidad'] ?>">

    <?= ucfirst(htmlspecialchars($ing['insumo'])) ?>

</option>
                    <?php endforeach; ?>

                                        <?php endif; ?>

                                    </select>
                                </div>

                            </div>

                            <div class="alert alert-secondary mt-3 mb-0 text-center py-2 border-dark">
                                <span class="fw-bold text-dark" style="font-size: 0.9rem;">El precio final será el promedio de ambas mitades.</span>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer border-0 bg-dark d-flex gap-2">
                    <button type="button" class="btn btn-secondary font-bangers px-4" data-bs-dismiss="modal">CANCELAR</button>
                    <button type="button" class="btn btn-warning font-bangers px-4 text-dark" onclick="confirmarSeleccionModal()">🍖 AGREGAR AL PEDIDO</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

    <!-- CODIGO PARA LA GENERACION DE IMAGEN A WHATSAPP -->

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
        </div>
    </div>







    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/Sistema_mugiwara/public/js/pedidos/funciones_de_pedidos.js"></script>
    <script src="/Sistema_mugiwara/public/js/redireccion.js"></script>
    <script src="/Sistema_mugiwara/public/js/pedidos/funciones_de_pedidos_2.js"></script>

    <!-- <script>
        // Objeto temporal para guardar los datos del plato especial seleccionado
        let platoEspecialTemporal = null;
        let modalEspecial = null;

        document.addEventListener("DOMContentLoaded", function() {
            modalEspecial = new bootstrap.Modal(document.getElementById('modalIngredientesEspeciales'));
        });

        function evaluarAgregarPlato(id, descripcion, precio) {
            // Si la descripción tiene la palabra 'especial', abrimos el modal
            if (descripcion.toLowerCase().includes('especial')) {
                platoEspecialTemporal = { 
                    id, descripcion, precio 
                };
                
                // Limpiamos los checkbox del modal antes de abrirlo
                document.querySelectorAll('.check-especial').forEach(chk => chk.checked = false);
                
                modalEspecial.show();
            } else {
                // Si es un plato común, llamamos directamente a tu función nativa de funciones_de_pedidos.js
                if (typeof agregarPlato === "function") {
                    agregarPlato(id, descripcion, precio);
                }
            }
        }

        function toggleCheck(elemento) {
            const checkbox = elemento.querySelector('.check-especial');
            checkbox.checked = !checkbox.checked;
        }

        function confirmarPlatoEspecial() {
            if (!platoEspecialTemporal) return;

            // Recopilamos los ingredientes que tildó el usuario
            let ingredientesElegidos = [];
            document.querySelectorAll('.check-especial:checked').forEach(chk => {
                ingredientesElegidos.push({
                    id: chk.value,
                    nombre: chk.getAttribute('data-nombre'),
                    gramos: chk.getAttribute('data-gramos')
                });
            });

            // Cerramos el modal
            modalEspecial.hide();

            // Renderizamos la fila en la tabla del pedido actual
            inyectarFilaPedidoEspecial(platoEspecialTemporal, ingredientesElegidos);
            
            // Limpiamos temporal
            platoEspecialTemporal = null;
        }

        function inyectarFilaPedidoEspecial(plato, extras) {
            const tbody = document.getElementById('lista-pedido');
            const randomId = 'extra_' + Math.floor(Math.random() * 100000);
            
            // Generamos la fila principal del producto
            let filaPrincipal = document.createElement('tr');
            filaPrincipal.innerHTML = `
                <td class="fw-bold">
                    ${extras.length > 0 ? `<button class="btn-desplegar" onclick="toggleSubfila('${randomId}', this)">▼</button>` : ''}
                    🍕 ${plato.descripcion.toUpperCase()}
                </td>
                <td>1</td>
                <td>$${plato.precio.toLocaleString('es-AR')}</td>
                <td class="text-end">
                    <button class="btn btn-sm btn-danger py-0 px-1" onclick="this.closest('tr').nextElementSibling?.remove(); this.closest('tr').remove();">❌</button>
                </td>
            `;
            tbody.appendChild(filaPrincipal);

            // Si seleccionó extras, creamos la fila oculta colapsable justo debajo
            if (extras.length > 0) {
                let filaSub = document.createElement('tr');
                filaSub.id = randomId;
                filaSub.className = "fila-extras";
                filaSub.style.display = "none"; // Oculta por defecto
                
                let listaExtrasHTML = extras.map(e => `
                    <span class="badge badge-extra mb-1 d-inline-block p-2 me-1">
                        🧪 ${e.nombre} (${e.gramos})
                    </span>
                `).join('');

                filaSub.innerHTML = `
                    <td colspan="4" class="ps-4 py-2 border-start border-3 border-warning">
                        <div class="small fw-bold text-muted mb-1">INGREDIENTES ADICIONALES:</div>
                        ${listaExtrasHTML}
                    </td>
                `;
                tbody.appendChild(filaSub);
            }

            // Actualizar total (Aquí deberías llamar a la función que recalcula el total en tu archivo js de pedidos)
            actualizarTotalSimulado(plato.precio);
        }

        function toggleSubfila(id, boton) {
            const subfila = document.getElementById(id);
            if (subfila.style.display === "none") {
                subfila.style.display = "table-row";
                boton.classList.add('abierto');
            } else {
                subfila.style.display = "none";
                boton.classList.remove('abierto');
            }
        }

        function actualizarTotalSimulado(precio) {
            const totalMonto = document.getElementById('total-monto');
            let actual = parseInt(totalMonto.innerText.replace('$', '').replace('.', '')) || 0;
            actual += precio;
            totalMonto.innerText = "$" + actual.toLocaleString('es-AR');
            
            const totalModal = document.getElementById('total-modal');
            if(totalModal) totalModal.innerText = "$" + actual.toLocaleString('es-AR');
        }
    </script> -->
</body>


</html>