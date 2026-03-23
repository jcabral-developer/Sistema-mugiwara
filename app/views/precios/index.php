<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Precios Comidas - Mugiwara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bangers&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/Sistema_mugiwara/public/css/cssPrecios.css">
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

    <div class="container-mugiwara">
        <div class="row mb-4 align-items-center">
            <div class="col-md-7">
                <h1 class="font-bangers text-white" style="font-size: 3.5rem; text-shadow: 4px 4px 0px #000;">🍱 LISTA
                    DE PRECIOS(COMIDAS)</h1>
                <p class="fw-bold" style="color: black;">Ajusta los precios de las comidas según los costos de los
                    insumos.</p>
            </div>
            <div class="col-md-5 text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <input type="text" id="buscadorPlatos" class="form-control border-3 border-dark w-50"
                        placeholder="Buscar plato ...">

                </div>
            </div>
        </div>

        <div class="card-pergamino">
            <div class="table-responsive">
                <table class="table-pirata align-middle">
                    <thead>
                        <tr>
                            <th>NOMBRE DEL PLATO</th>
                            <th>COSTO (RECETA)</th>
                            <th>MARGEN %</th>
                            <th>PRECIO VENTA</th>
                            <th>GANANCIA</th>
                            <!-- <th>INFO</th> -->
                            <th class="text-center">GESTIÓN</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($infoPlatos as $p): ?>

                            <tr>
                                <td class="d-flex align-items-center">
                                    <div class="position-relative me-3 group-foto">
                                        <img src="/Sistema_mugiwara/public/img/imagenes_de_comidas/<?php echo !empty($p['imagen']) ? $p['imagen'] : 'default.png'; ?>"
                                            id="img-<?php echo $p['id']; ?>"
                                            class="rounded-circle border border-dark shadow-sm"
                                            style="width: 70px; height: 70px; object-fit: cover; cursor: pointer;"
                                            onclick="document.getElementById('input-file-<?php echo $p['id']; ?>').click()">

                                        <input type="file" id="input-file-<?php echo $p['id']; ?>" style="display: none;"
                                            accept="image/*" onchange="subirImagen(this, <?php echo $p['id']; ?>)">
                                        <div class="badge-edit">📸</div>
                                    </div>
                                    <span class="fw-bold fs-5"><?php echo ucfirst($p['descripcion']); ?></span>
                                </td>
                                <td class="text-costo">
                                    $<?php echo $p['costo_receta']; ?>
                                </td>

                                <td class="text-margen">
                                    <?php echo $p['margen']; ?>%
                                </td>

                                <td class="text-precio">
                                    <strong>$<?php echo $p['precio_venta']; ?></strong>
                                </td>

                                <td class="text-ganancia">
                                    $<?php echo $p['ganancia']; ?>
                                </td>

                                <!-- <td>
                                    <span class="badge bg-success border border-dark"><? php// echo $p['reporte']; ?></span>
                                </td> -->

                                <td class="text-center">
                                    <button class="btn-config" data-bs-toggle="modal" data-bs-target="#modalEditor"
                                        data-id="<?php echo $p['id']; ?>">
                                        ⚙️ AJUSTAR
                                    </button>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditor" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content card-mugiwara p-0 border-0">

                <div class="modal-header border-bottom border-dark bg-white">
                    <h5 class="modal-title font-bangers fs-3">⚙️ CONFIGURAR PLATO</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body bg-light">
                    <div class="row">

                        <!-- COLUMNA RECETA -->
                        <div class="col-md-8">

                            <div class="card p-3 border-dark shadow-sm">
                                <h6 class="font-bangers text-primary">🔪 COSTO DE PRODUCCIÓN (RECETA)</h6>
                                <div id="descripcion" class="fs-4 fw-bold text-dark border-bottom mb-2 pb-1"
                                    style="font-family: 'Bangers', cursive; letter-spacing: 1px;">
                                </div>
                                <table class="table table-sm table-hover mt-2">

                                    <thead class="table-dark">
                                        <tr>
                                            <th>Insumo</th>
                                            <th>Cantidad</th>
                                            <th>Parcial</th>
                                        </tr>
                                    </thead>

                                    <!-- SE LLENA CON JS -->
                                    <tbody id="tablaIngredientes" class="fw-bold">
                                    </tbody>

                                    <tfoot>
                                        <tr class="table-secondary fw-bold">
                                            <td colspan="2" class="text-end">COSTO TOTAL:</td>
                                            <td id="costo_total" class="text-danger">$0</td>
                                        </tr>
                                    </tfoot>

                                </table>

                            </div>

                        </div>

                        <!-- COLUMNA MARGEN -->
                        <div class="col-md-4">

                            <div class="card-mugiwara editor-panel h-100 bg-white">

                                <h6 class="font-bangers text-danger mb-3">📈 MARGEN DE GANANCIA</h6>

                                <div class="mb-4">
                                    <label class="small fw-bold">PORCENTAJE DE MARGEN (%)</label>

                                    <input type="number" id="inputMargen"
                                        class="form-control form-control-lg border-dark fw-bold" value="100"
                                        oninput="calcularPrecio()">

                                    <small class="text-muted">
                                        Porcentaje aplicado sobre el costo.
                                    </small>
                                </div>

                                <hr class="border-dark">

                                <div class="text-center p-3">

                                    <span class="small fw-bold text-muted">
                                        PRECIO FINAL DE LA COMIDA
                                    </span>

                                    <h2 class=" text-primary fw-bold text-center mb-3 border-bottom pb-2"
                                        id="precioSugerido">
                                    </h2>

                                    <div class="mt-3 bg-light p-2 border border-dark">

                                        <span class="small fw-bold text-muted">
                                            GANANCIA
                                        </span>

                                        <h4 class="text-ganancia " id="gananciaVenta">
                                            $0
                                        </h4>

                                    </div>

                                </div>

                                <div class="alert alert-warning border-dark mt-3 p-2 small" id="alertaMargen"
                                    style="display:none;">

                                    <strong>⚠ CUIDADO:</strong>
                                    Margen muy bajo para ganancia.

                                </div>

                                <div class="d-grid gap-2 mt-4">

                                    <button class="btn btn-success btn-mugiwara fs-5" id="btnGuardarPrecio">
                                        💾 GUARDAR CAMBIOS
                                    </button>

                                    <button class="btn btn-outline-danger fw-bold border-2" data-bs-dismiss="modal">
                                        CANCELAR
                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/Sistema_mugiwara/public/js/redireccion.js"></script>
    <script src="/Sistema_mugiwara/public/js/precios/procesosVarios.js"></script>
    <script src="/Sistema_mugiwara/public/js/precios/subirImagen.js"></script>

</body>

</html>