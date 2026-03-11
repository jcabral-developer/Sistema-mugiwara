<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock - Mugiwara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Sistema_mugiwara/public/css/cssDeModalStock.css">
    <link rel="stylesheet" href="/Sistema_mugiwara/public/css/cssStock.css">
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

            <div onclick="cambiar('stock')">🍖 Stock <span  class="badge"><?php echo $bajoStock ? 'Bajo' : '' ;?></span></div>

             <div onclick="cambiar('precios')">🍳 Precios</div>

            <div onclick="cambiar('caja')">💰 Ganancias</div>

            <div onclick="cambiar('reportes')">📜 Reportes</div>

            <div onclick="cambiar('config')">🛠️ Config</div>


        </div>

    </div>


    <div class="container mt-4">


        <ul class="nav nav-tabs" id="stockTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="resumen-tab" data-bs-toggle="tab" data-bs-target="#resumen"
                    type="button">📊 RESUMEN</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="listado-tab" data-bs-toggle="tab" data-bs-target="#listado"
                    type="button">📜 STOCK</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="compras-tab" data-bs-toggle="tab" data-bs-target="#compras"
                    type="button">💰 COMPRAS</button>
            </li>
        </ul>

        <div class="tab-content" id="stockTabsContent">

         <div class="tab-pane fade show active" id="resumen" role="tabpanel">
    <div class="row g-4 container-fluid px-0">
        
        <div class="col-lg-6">
            <div class="card card-pergamino h-100">
                <div class="card-header">
                    <h3 class="font-bangers m-0 d-flex align-items-center justify-content-center text-center">
                        <span class="fs-2 me-3">☠️</span> STOCK CRÍTICO <span class="fs-2 ms-3">☠️</span>
                    </h3>
                </div>
                <div class="card-body p-4">
                    <div class="lista-critica-container" id="listaCriticaJS">
                        <?php if (!empty($insumosCriticos)): ?>
                            <?php foreach ($insumosCriticos as $critico): ?>
                                <div class="d-flex align-items-center p-3 item-critico rounded shadow-sm">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1 font-bangers text-dark text-uppercase"><?= htmlspecialchars($critico['descripcion']) ?></h5>
                                        <small class="text-muted fw-bold">Stock Mínimo establecido: <?= htmlspecialchars($critico['stock_minimo_formateado']) ?> <?= $critico['unidad_medida'] === 'un' ? $critico['unidad_medida'] : '' ; ?></small>
                                    </div>
                                    <div class="text-end">
                                        <span class=" badge-wanted fs-5 px-3 rounded-pill">
                                            <?= $critico['stock_formateado'] ?> <?= $critico['unidad_medida'] === 'un' ? $critico['unidad_medida'] : '' ;?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-5 text-dark">
                                <span class="fs-1">⚓</span>
                                <p class="font-bangers fs-3 mt-2 text-success">¡No existen insumos en estado crítico!</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-pergamino h-100">
                <div class="card-header bg-danger text-white">
                    <h3 class="font-bangers m-0 d-flex align-items-center justify-content-center text-center">
                        <span class="fs-2 me-3">💰</span> ÚLTIMA COMPRA <span class="fs-2 ms-3">💰</span>
                    </h3>
                </div>
                <div class="card-body p-4 text-dark">
                    <div class="ticket-pirata rounded shadow-sm">
                        <div class="text-center mb-3">
                            <span class="font-bangers text-danger fs-5 border border-danger p-1 rounded"><?php echo !empty($ultimaCompra) ? $ultimaCompra[0]['fecha'] : ''; ?></span>
                        </div>

                       
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless align-middle text-dark">
                            
                                <thead class="border-bottom border-dark">
                                    <tr class="font-bangers text-muted small">
                                        <th>INSUMOS</th>
                                        <th class="text-center">CANT.</th>
                                        <th class="text-end">PRECIO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                       
                              <?php if (!empty($ultimaCompra)): ?>
    <?php foreach ($ultimaCompra as $compra): ?>   
        <tr class="fw-bold">
            <td> ✦ <?php echo $compra['descripcion']; ?></td>
            <td class="text-center"><?php echo $compra['cantidad'] . ' ' . $compra['unidad_medida']; ?></td>
            <td class="text-end"><?php echo $compra['precio_unitario']; ?></td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>
                                       
                                </tbody>
                                <tfoot>
                                    <tr class="border-top border-dark fs-5">
                                        <td colspan="2" class="pt-2 font-bangers text-danger"> TOTAL: </td>
                                        <td class="pt-2 text-end font-bangers text-danger">$ <?php echo !empty($ultimaCompra) ? $ultimaCompra[0]['total'] : 0; ?></td>
                                    </tr>
                                </tfoot>
                      
                            </table>
                        </div>

            <div class="bg-paper p-3 rounded-3 mt-3 border border-dark">

    <h6 class="font-bangers text-wood fs-5 mb-3 text-center">
        <span class="me-1">📊</span> RENDIMIENTO POR INSUMO COMPRADO:
    </h6>

    <div class="d-grid gap-2">

    <?php if (!empty($ultimaCompra)): ?>

        <?php foreach ($ultimaCompra as $index => $insumo): ?>

            <?php if (!empty($insumo['rendimientos'])): ?>

                <?php $collapseId = "rendimiento_" . $index; ?>

                <div class="item-rendimiento-pirata">

                    <button class="btn btn-wood-pirata w-100 font-bangers d-flex justify-content-between align-items-center"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#<?php echo $collapseId; ?>">

                        <span>🍖 <?php echo strtoupper($insumo['descripcion']); ?></span>
                        <span class="fs-6">VER RENDIMIENTO ▼</span>

                    </button>

                    <div class="collapse mt-2" id="<?php echo $collapseId; ?>">

                        <div class="p-2 border border-dark bg-white rounded shadow-sm">

                            <ul class="list-unstyled m-0">

                                <?php foreach ($insumo['rendimientos'] as $r): ?>

                                    <li class="d-flex justify-content-between border-bottom border-dashed py-1">

                                        <span>🍽 <?php echo $r['plato']; ?>:</span>

                                        <strong class="text-danger">
                                            Rinde <?php echo $r['cantidad']; ?> un
                                        </strong>

                                    </li>

                                <?php endforeach; ?>

                            </ul>

                        </div>

                    </div>

                </div>

            <?php endif; ?>

        <?php endforeach; ?>

    <?php endif; ?>

    </div>

</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

            <div class="tab-pane fade" id="listado" role="tabpanel">
                <div class="seccion-bodega">
                    <!-- seccion de mensajes de alerta por validaciones -->
                  
                    <!--  -->
                    <div class="seccion-header">
                        <h2>📜 LISTADO DE INSUMOS</h2>
                    </div>

                    <div class="buscador-container">
                        <div class="grupo-busqueda">
                            <span class="icono-busqueda">🔍</span>
                            <input type="text" id="inputBusqueda" placeholder="Buscar insumo..."
                                onkeyup="filtrarInsumos()">
                            <button type="button" class="btn-limpiar" onclick="limpiarBusqueda()">🧹 LIMPIAR</button>
                        </div>
                    </div>

                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>📦 INSUMO</th>
                                <th>📊 STOCK ACTUAL</th>
                                <th>⚠️ ESTADO</th>
                                <th>📈 RENDIMIENTO</th>
                                <th class="text-center">⚙️ ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody id="tablaCuerpo">
                            <?php if (!empty($stock)): ?>
                                <?php foreach ($stock as $stocks): ?>
                                    <tr>

                                        <td class="text-uppercase fw-bold"><?= htmlspecialchars($stocks['descripcion']) ?>
                                        </td>
                                        <td>
                                            <span class="fs-5"><?php
                                            if ($stocks['unidad_medida'] == 'un') {
                                                echo $stocks['stock_formateado'] . ' Unidades';
                                            } else {
                                                echo $stocks['stock_formateado'];
                                            }
                                            ?></span>
                                        </td>
                                        <td>
                                            <?php if ($stocks['stock'] <= 0): ?>
                                                <b style="color: #dc3545">🔴 AGOTADO </b>
                                            <?php elseif ($stocks['stock'] <= $stocks['stock_minimo']): ?>
                                                <b style="color: #ffc107">🟠 BAJO</b>
                                            <?php else: ?>
                                                <b style="color: var(--op-green)">🟢 OK</b>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <button
                                                class="btn btn-outline-secondary btn-sm dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#rinde-<?= $stocks['id'] ?>" aria-expanded="false">
                                                Ver platos
                                            </button>
                                        </td>

                                        <td class="text-center">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button class="btn btn-primary btn-sm shadow-sm fw-bold"
                                                    onclick="editarInsumo(<?= $stocks['id'] ?>, '<?= htmlspecialchars($stocks['descripcion']) ?>', <?= $stocks['stock'] ?>, '<?= htmlspecialchars($stocks['unidad_medida']) ?>' )">
                                                    📦 Editar Stock
                                                </button>

                                                <button class="btn btn-outline-warning btn-sm shadow-sm fw-bold"
                                                    onclick="editarLimite(<?= $stocks['id'] ?>, '<?= htmlspecialchars($stocks['descripcion']) ?>', <?= $stocks['stock_minimo'] ?? 0 ?>, '<?= htmlspecialchars($stocks['unidad_medida']) ?>')">
                                                    ⚠️ Editar Mínimo
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr class="collapse" id="rinde-<?= $stocks['id'] ?>">
                                        <td colspan="5" class="bg-light p-0">
                                            <div
                                                class="p-3 border-start border-end border-bottom mx-2 mb-2 bg-white shadow-sm rounded-bottom">
                                                <h6 class="fw-bold text-muted small mb-2">PLATOS QUE UTILIZAN ESTE INSUMO:</h6>

                                                <?php if (!empty($stocks['rendimientos'])): ?>
                                                    <ul class="list-group list-group-flush">
                                                        <?php foreach ($stocks['rendimientos'] as $r): ?>
                                                            <li class="list-group-item d-flex align-items-center gap-3 bg-transparent">

                                                                <span
                                                                    class="fw-bold text-dark"><?= htmlspecialchars($r['plato']) ?></span>

                                                                <span class="badge bg-primary rounded-pill px-3">
                                                                    Rinde: <?= $r['cantidad'] ?> UN | Sobra: <?= $r['sobrante'] ?? 0 ?>
                                                                    g
                                                                </span>

                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                <?php else: ?>
                                                    <div class="text-muted fst-italic">
                                                        Este insumo no genera producción directa
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div style="font-size: 3rem; opacity: 0.6;">📦</div>
                                        <p class="text-muted mt-2">No hay insumos cargados en la bodega.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="compras" role="tabpanel">
                <div class="seccion-bodega">
                    <!-- seccion de mensajes de alerta por validaciones -->
                    <div id="mensajes"></div>
                    <!--  -->
                    <div class="seccion-header">
                        <h2>📜 HISTORIAL DE COMPRAS</h2>
                    </div>

                    <div class="buscador-container">
                        <div class="grupo-busqueda">
                            <span class="icono-busqueda">🔍</span>
                            <input type="date" id="fechaDesde" oninput="filtrarPorRango()">
                            <input type="date" id="fechaHasta" oninput="filtrarPorRango()">

                            <button type="button" class="btn-limpiar" onclick="limpiarBusquedaCompras()">🧹 LIMPIAR</button>
                        </div>
                    </div>
                    <button class="btn-compra-mini w-100" id="btnAbrir">🍖 REGISTRAR NUEVA COMPRA</button>
                    <br>
                    <br>

                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th># ID</th>
                                <th>📅 FECHA</th>
                                <th>🧑 USUARIO</th>
                                <th>💰 TOTAL</th>
                                <th class="text-center">⚙️ ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody id="tablaCuerpoCompras">
                            <?php if (!empty($compras)): ?>
                                <?php foreach ($compras as $compra): ?>

                                    <tr id="fila-<?= $compra['id'] ?>"
                                        data-fecha="<?= date('Y-m-d', strtotime(datetime: $compra['fecha'])) ?>">
                                        <td><strong>#<?= htmlspecialchars($compra['id']) ?></strong></td>
                                        <td><?= date('d/m/Y', strtotime($compra['fecha'])) ?></td>
                                        <td><span
                                                class="badge bg-info text-dark"><?= htmlspecialchars($compra['comprador']) ?></span>
                                        </td>
                                        <td class="fw-bold text-success">$ <?= htmlspecialchars($compra['total']) ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-warning btn-sm shadow-sm"
                                                onclick="editarCompra(<?= $compra['id'] ?>)">
                                                👁️ ver detalle
                                            </button>

                                            <button type="button" class="btn btn-danger btn-sm shadow-sm"
                                                onclick="eliminarCompra(<?= $compra['id'] ?>)">🗑️Borrar</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div style="font-size: 3rem; opacity: 0.6;">📦</div>
                                        <p class="text-muted mt-2">No se han registrado compras aún.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <div class="modal-overlay" id="miModal">
        <div class="modal-pergamino">
            <div class="modal-header">
                <h2>🧾 REGISTRAR COMPRA</h2>
            </div>
            <div class="grid-cabecera mb-3">
                <div class="campo-grupo">
                    <label>📅 FECHA</label>
                    <input type="date" id="compraFecha" value="<?= date('Y-m-d') ?>" class="form-control-mugiwara">
                </div>
                <div class="campo-grupo">
                    <label>🧑 COMPRADOR</label>
                    <select id="compraComprador" class="form-control-mugiwara">
                        <option value="Victoria">Victoria</option>
                        <option value="Matias">Matias</option>
                    </select>
                </div>
            </div>

            <hr>
            <div class="seccion-insumos bg-light p-3 rounded-3 mb-3">
                <h5 class="text-dark fw-bold mb-3">➕ AGREGAR ÍTEM</h5>
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="small fw-bold">INSUMO</label>
                        <select id="itemInsumo" class="form-select">
                            <option value="">Seleccione...</option>
                            <?php foreach ($insumos as $insumo): ?>
                                <option value="<?= $insumo['id'] ?>"><?= htmlspecialchars($insumo['descripcion']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="small fw-bold">CANT.</label>
                        <input type="number" id="itemCantidad" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="small fw-bold">UNIDAD</label>
                        <select id="itemUnidad" class="form-select">
                            <option value="kg">kg</option>
                            <option value="gr">gr</option>
                            <option value="un">un</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="small fw-bold">PRECIO ($)</label>
                        <input type="number" id="itemPrecio" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <button type="button" onclick="agregarItem()"
                            class="btn btn-warning w-100 fw-bold">AÑADIR</button>
                    </div>
                </div>
            </div>
            <div class="ticket-container">
                <table class="table table-sm" id="tablaDetalle">
                    <thead class="table-dark">
                        <tr>
                            <th>Insumo</th>
                            <th>Cant.</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="cuerpoDetalle"></tbody>
                    <tfoot>
                        <tr class="table-warning font-weight-bold">
                            <td colspan="2" class="text-end">TOTAL:</td>
                            <td colspan="2"><strong id="totalCompra">$ 0.00</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="footer-acciones">
                <button id="btnCerrar" class="btn-cancelar">DESCARTAR</button>
                <button class="btn-registrar" onclick="guardarTodaLaCompra()">⚓ FINALIZAR COMPRA</button>
            </div>
        </div>
    </div>


    <div class="modal-overlay" id="modalDetalleCompra" style="display: none;">
        <div class="modal-pergamino modal-lg">
            <div class="modal-header border-bottom border-dark pb-2 mb-3">
                <h2 class="text-uppercase">📜 DETALLE DE COMPRA</h2>
                <button onclick="cerrarDetalle()" class="btn-close-custom">✖</button>
            </div>

            <div class="remito-body p-3">
                <div class="row mb-4">
                    <div class="col-6">
                        <p class="mb-1 text-dark"><strong>N° REMITO:</strong> <span id="det-id">#000</span></p>
                        <p class="mb-1 text-dark"><strong>FECHA:</strong> <span id="det-fecha">--/--/----</span></p>
                    </div>
                    <div class="col-6 text-end">
                        <p class="mb-1 text-dark"><strong>RESPONSABLE:</strong> <span id="det-comprador"
                                class="text-uppercase">---</span></p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered border-dark" style="width: 100%; margin-bottom: 0;">
                        <thead class="table-secondary">
                            <tr>
                                <th style="width: 40%;">INSUMO</th>
                                <th class="text-center" style="width: 15%;">CANT.</th>
                                <th class="text-center" style="width: 15%;">U.M.</th>
                                <th class="text-end" style="width: 30%;">PRECIO</th>
                            </tr>
                        </thead>
                        <tbody id="det-items">
                        </tbody>
                        <tfoot>
                            <tr class="fs-5 fw-bold" style="background: #f8f9fa;">
                                <td colspan="3" class="text-end text-uppercase">TOTAL DEL REMITO:</td>
                                <td class="text-end text-success" id="det-total" style="border-left: none;">$ 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="footer-acciones mt-4">
                <button onclick="window.print()" class="btn-cancelar" style="background: #6c757d;">🖨️ IMPRIMIR</button>
                <button onclick="cerrarDetalle()" class="btn-registrar">⚓ CERRAR</button>
            </div>
        </div>
    </div>


    <!-- modal de ajuste de stock -->
    <div class="modal-overlay" id="modalAjusteInsumo" style="display: none;">
        <div class="modal-pergamino">

            <div class="modal-header">
                <h2 id="ajusteTitulo">⚙️ CONFIGURAR INSUMO</h2>
            </div>

            <form id="formAjusteStock">

                <input type="hidden" id="ajusteId">

                <!-- STOCK -->
                <div class="campo-grupo mb-3">
                    <label>📦 STOCK ACTUAL</label>

                    <div style="display:flex; gap:10px;">
                        <label>KILOS: </label>

                        <input type="number" step="1" min="0" id="stock_kg" class="form-control-mugiwara"
                            placeholder="Kg">
                        <label>GRAMOS: </label>
                        <input type="number" step="1" min="0" max="999" id="stock_gr" class="form-control-mugiwara"
                            placeholder="Gr">
                    </div>


                    <input type="hidden" id="ajusteStock" name="stock">

                    <small class="text-muted">
                        Ejemplos:<br>
                        1 kg 300 gr → colocar 1 en Kg y 300 en Gr<br>
                        500 gr → dejar Kg vacío y colocar 500 en Gr
                    </small>
                </div>

                <!-- UNIDAD -->
                <div class="campo-grupo mb-3">
                    <label>📏 UNIDAD DE MEDIDA</label>

                    <select id="unidad" name="unidad" class="form-control-mugiwara" required>
                     <option value="" disabled selected>Seleccione una unidad</option>
                        <option value="gr">Gramos</option>
                        <option value="kg">Kilos</option>
                        <option value="un">Unidad</option>
                    </select>

                    <small class="text-muted">
                        Seleccionar la unidad de medida, ejemplos:<br>
                        Si es igual o supera 1 kg seleccionar kilos<br>
                        Si es menor a 1 kg seleccionar gramos<br>
                        Si se trata de unidades seleccionar unidad
                    </small>
                </div>



                <!-- BOTONES -->
                <div class="footer-acciones">
                    <button type="button" onclick="cerrarAjuste()" class="btn-cancelar">
                        CANCELAR
                    </button>

                    <button type="submit" class="btn-registrar">
                        ⚓ GUARDAR CAMBIOS
                    </button>
                </div>

            </form>

        </div>
    </div>


    <div class="modal-overlay" id="modalAjusteLimite" style="display: none;">
        <div class="modal-pergamino">

            <div class="modal-header">
                <h2 id="ajusteTitulo2">⚙️ CONFIGURAR lÍMITE</h2>
            </div>

            <form id="formAjusteLimite">

                <input type="hidden" id="ajusteId">


                <!-- LIMITE CRITICO -->
                <div class="campo-grupo mb-3">

                    <label>⚠️ LÍMITE ACTUAL</label>
                    <div style="display:flex; gap:10px;">


                        <label>KILOS: </label>
                        <input type="number" step="1" min="0" id="limite_kg" class="form-control-mugiwara"
                            placeholder="Kg">

                        <label>GRAMOS: </label>
                        <!-- <input type="text" id="prueba"> -->
                        <input type="number" step="1" min="0" max="999" id="limite_gr" class="form-control-mugiwara"
                            placeholder="Gr">
                    </div>


                    <input type="hidden" id="ajusteMinimo" name="minimo">

                    <small class="text-muted">
                        Cuando el stock baja de este valor aparecerá en rojo.
                        Si se trata de unidades llenar unicamente el primer campo con la cantidad.
                    </small>
                </div>

                <!-- UNIDAD LIMITE -->
                <div class="campo-grupo mb-3">
                    <label>📏 UNIDAD DEL LÍMITE</label>

                    <select id="unidad_limite" name="unidad_limite" class="form-control-mugiwara" required>
                        <option value="" disabled selected>Seleccione una unidad</option>
                        <option value="gr">Gramos</option>
                        <option value="kg">Kilos</option>
                        <option value="un">Unidad</option>
                    </select>
                    <small class="text-muted">
                        Seleccionar la unidad de medida, ejemplos:<br>
                        Si es igual o supera 1 kg seleccionar kilos<br>
                        Si es menor a 1 kg seleccionar gramos
                        Si se trata de unidades seleccionar unidad
                    </small>

                </div>



                <!-- BOTONES -->
                <div class="footer-acciones">
                    <button type="button" onclick="cerrarAjusteLimite()" class="btn-cancelar">
                        CANCELAR
                    </button>

                    <button type="submit" class="btn-registrar">
                        ⚓ GUARDAR CAMBIOS
                    </button>
                </div>

            </form>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script> const BASE_URL = "<?= BASE_URL ?>";</script>
    <script src="/Sistema_mugiwara/public/js/redireccion.js"></script>
    <script src="/Sistema_mugiwara/public/js/stock/abrirModal.js"></script>
    <script src="/Sistema_mugiwara/public/js/stock/agregarItemModal.js"></script>
    <script src="/Sistema_mugiwara/public/js/stock/detalleCompra.js"></script>
    <script src="/Sistema_mugiwara/public/js/stock/eliminar.js"></script>
    <script src="/Sistema_mugiwara/public/js/stock/ajustarStock.js"></script>
</body>

</html>

