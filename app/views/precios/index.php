<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Precios Comidas - Mugiwara</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Bangers&display=swap" rel="stylesheet">
    <!-- Font Awesome (Corregido) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Tu CSS Personalizado -->
    <link rel="stylesheet" href="/Sistema_mugiwara/public/css/cssPrecios.css">
    <!-- Favicon (Corregido) -->
    <link rel="icon" type="image/x-icon" href="/Sistema_mugiwara/public/img/Gemini_Generated_Image_b3vr0wb3vr0wb3vr-removebg-preview.png" />
</head>

<body>

    <!-- BARRA SUPERIOR -->
    <div class="topbar">
        <div class="logo-wrapper">
            <img src="/Sistema_mugiwara/public/img/Gemini_Generated_Image_b3vr0wb3vr0wb3vr-removebg-preview.png"
                alt="Logo Mugiwara" class="logo-img logo-animado">
            <a href="index.php?route=" class="logo">MUGIWARA</a>
        </div>
        <div class="menu">
            <div onclick="cambiar('pedidos')">⚔️ Pedidos</div>
            <div onclick="cambiar('stock')">
                🍖 Stock <span class="badge"><?php echo $bajoStock ? 'Bajo' : ''; ?></span>
            </div>
            <div onclick="cambiar('precios')">🍳 Precios</div>
            <div onclick="cambiar('caja')">💰 Ganancias</div>
            <div onclick="cambiar('reportes')">📜 Reportes</div>
            <div onclick="cambiar('config')">🛠️ Config</div>
        </div>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="container-mugiwara">
        <div class="row mb-4 align-items-center">
            <div class="col-md-7">
                <h1 class="font-bangers text-white" style="font-size: 3.5rem; text-shadow: 4px 4px 0px #000;">
                    🍱 LISTA DE PRECIOS (COMIDAS)
                </h1>
                <p class="fw-bold text-dark bg-warning d-inline-block px-2 rounded">
                    Ajusta los precios de las comidas según los costos de los insumos.
                </p>
            </div>
            <div class="col-md-5 text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <input type="text" id="buscadorPlatos" class="form-control border-3 border-dark w-75"
                        placeholder="🔍 Buscar plato...">
                </div>
            </div>
        </div>

        <!-- TABLA ESTILO PERGAMINO -->
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
                                    <span class="fw-bold fs-5 text-dark"><?php echo ucfirst($p['descripcion']); ?></span>
                                </td>
                                <td class="text-costo">
                                    <div class="d-flex flex-column align-items-start">
                                        <span class=" fs-6">$<?php echo $p['costo_receta']; ?></span>
                                        
                                        <?php 
                                        $variacion = $p['variacion'];
                                        $diferencia = round($p['diferencia'], 2);
                                        ?>

                                        <?php if ($variacion == 'subio'): ?>
                                            <small class="badge-variacion subio" title="El costo aumentó desde la ultima compra">
                                                <i class="fas fa-arrow-up"></i> $<?php echo $diferencia; ?>
                                            </small>
                                        <?php elseif ($variacion == 'bajo'): ?>
                                            <small class="badge-variacion bajo" title="El costo bajó desde la ultima compra">
                                                <i class="fas fa-arrow-down"></i> $<?php echo $diferencia; ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="text-margen fw-bold">
                                    <?php echo $p['margen']; ?>%
                                </td>
                                <td class="text-precio">
                                    <strong>$<?php echo $p['precio_venta']; ?></strong>
                                </td>
                                <td class="text-ganancia fw-bold">
                                    $<?php echo $p['ganancia']; ?>
                                </td>
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

    <!-- MODAL DE CONFIGURACIÓN (Estilo Pergamino) -->
    <div class="modal fade" id="modalEditor" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content modal-content-pergamino p-0">
                <div class="modal-header border-bottom border-dark">
                    <h5 class="modal-title font-bangers fs-3 text-dark">⚙️ CONFIGURAR PLATO</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- COLUMNA RECETA -->
                        <div class="col-md-8">
                            <div class="card p-3 border-dark shadow-sm h-100">
                                <h6 class="font-bangers text-primary fs-4">🔪 COSTO DE PRODUCCIÓN</h6>
                                <div id="descripcion" class="fs-3 fw-bold text-dark border-bottom mb-3 pb-1"
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
                                    <tbody id="tablaIngredientes" class="fw-bold text-dark">
                                        <!-- Se llena con JS -->
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-secondary fw-bold text-dark">
                                            <td colspan="2" class="text-end">COSTO TOTAL:</td>
                                            <td id="costo_total" class="text-danger fs-5">$0</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- COLUMNA MARGEN -->
                        <div class="col-md-4">
                            <div class="card p-3 border-dark shadow-sm bg-white h-100">
                                <h6 class="font-bangers text-danger fs-4 mb-3">📈 MARGEN</h6>
                                <div class="mb-4">
                                    <label class="small fw-bold text-muted">PORCENTAJE DE MARGEN (%)</label>
                                    <input type="number" id="inputMargen"
                                        class="form-control form-control-lg border-dark fw-bold text-center" 
                                        value="100" oninput="calcularPrecio()">
                                </div>
                                <hr class="border-dark">
                                <div class="text-center p-2">
                                    <span class="small fw-bold text-muted">PRECIO FINAL</span>
                                    <h2 class="text-primary fw-bold mb-3 border-bottom pb-2" id="precioSugerido">$0</h2>
                                    <div class="mt-2 bg-light p-3 border border-dark rounded">
                                        <span class="small fw-bold text-muted">GANANCIA ESTIMADA</span>
                                        <h4 class="text-ganancia m-0" id="gananciaVenta">$0</h4>
                                    </div>
                                </div>
                                <div class="alert alert-warning border-dark mt-3 p-2 small" id="alertaMargen" style="display:none;">
                                    <strong>⚠ CUIDADO:</strong> Margen muy bajo.
                                </div>
                                <div class="d-grid gap-2 mt-auto pt-4">
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/Sistema_mugiwara/public/js/redireccion.js"></script>
    <script src="/Sistema_mugiwara/public/js/precios/procesosVarios.js"></script>
    <script src="/Sistema_mugiwara/public/js/precios/subirImagen.js"></script>

</body>
</html>