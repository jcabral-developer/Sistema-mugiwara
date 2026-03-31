<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Configuración - Mugiwara</title>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- fin bootstrap -->
    <link rel="stylesheet" href="/Sistema_mugiwara/public/css/cssConfig.css">
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


    <div class="main">
        <div class="card-wide">
            <h1 class="titulo-principal">⚙️ CONFIGURACIONES</h1>
            <!-- seccion de mensajes de alerta por validaciones -->
            <?php if (!empty($_SESSION['errores'])): ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3" role="alert">
                    <strong>⚠️ Atención</strong>
                    <ul class="mb-0 mt-2">
                        <?php foreach ($_SESSION['errores'] as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['errores']); ?>
            <?php endif; ?>


            <?php if (!empty($_SESSION['success'])): ?>
                <div class="alert alert-success p-4" role="alert">
                    <strong>✅ Éxito</strong><br>
                    <?= $_SESSION['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>



            <!-- ********************************************* -->
            <form action="<?= BASE_URL ?>/index.php?route=config/rendimiento" method="POST">

                <div class="grid-main">
                    <div class="form-receta">
                        <h2 id="h2-titulo">⚙️ DEFINIR RENDIMIENTO</h2>
                        <div class="campo-row">
                            <div class="campo">
                                <label>Producto</label>
                                <select name="producto_id" required>
                                    <option value="">Seleccione un producto</option>
                                    <?php foreach ($productos as $producto): ?>
                                        <option value="<?= $producto['id'] ?>">
                                            <?= ucfirst(htmlspecialchars($producto['descripcion'])) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                            <div class="campo">
                                <label>Insumo</label>
                                <select name="insumo_id" required>
                                    <option value="">Seleccione un insumo</option>
                                    <?php foreach ($insumos as $insumo): ?>
                                        <option value="<?= $insumo['id'] ?>">
                                            <?= ucfirst(htmlspecialchars($insumo['descripcion'])) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                        </div>

                        <div class="campo-row">
                            <div class="campo">
                                <label>Cantidad usada del insumo</label>
                                <input type="number" step="0.01" name="cantidad" placeholder="Ej: 250" required>
                            </div>
                            <div class="campo">
                                <label>Unidad</label>
                                <select name="unidad" required>
                                    <option value="gr">Gramos</option>
                                    <option value="kg">kilos</option>
                                    <option value="un">unidad</option>

                                    <option value="ml">Mililitros</option>
                                </select>
                            </div>
                        </div>

                        <div class="campo">
                            <label>CUANTO RINDE ?</label>
                            <input type="number" name="rendimiento" placeholder="Ej: 250" required>
                        </div>

                        <button type="submit" value="rendimiento" value="rendimiento" class="btn-pro"
                            style="background: var(--wood); color: var(--gold);">💾 GUARDAR REGLA DE
                            PRODUCCIÓN</button>
                    </div>
            </form>
            <div class="section">
                <h2>🍕 NUEVO PRODUCTO/ INSUMO</h2>
                <div class="box">
                    <form action="<?= BASE_URL ?>/index.php?route=config/plato" method="POST">
                        <div class="campo">
                            <label>Nombre del plato / producto</label>
                            <input type="text" placeholder="Ej: Pizza Muzzarella" name="producto" required>
                        </div>
                        <button class="btn-pro" name="plato" value="value">➕ REGISTRAR PLATO</button>
                    </form>
                    <br>
                    <br>
                    <hr>
                    <br>
                    <form action="<?= BASE_URL ?>/index.php?route=config/insumo" method="POST">
                        <div class="campo">
                            <label>Nombre del Insumo</label>
                            <input type="text" placeholder="Ej: Harina" name="insumo" required>
                            <label>unidad de medida</label>
                            <select name="unidad_medida" id="unidad_medida">
                                <option value="un">Unidad</option>
                                <option value="gr">Gramos(Kilos)</option>

                                <option value="ml">Mililitros</option>
                            </select>
                        </div>

                        <button type="submit" name="enviar" value="enviar" class="btn-pro">➕ REGISTRAR
                            INSUMO</button>
                    </form>
                </div>
            </div>
        </div>
<div class="section mt-5">
    <div class="box container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">📜 RENDIMIENTO Y COSTOS POR PLATO</h3>
            <div class="buscador-container w-50">
                <div class="grupo-busqueda">
                    <span class="icono-busqueda">🔍</span>
                    <input type="text" id="inputBusqueda" placeholder="Buscar plato o insumo..." onkeyup="filtrarTarjetas()">
                </div>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="contenedorTarjetas">
            <?php if (!empty($reglas)): ?>
                <?php foreach ($reglas as $regla): 
                    // Simulación de costo unitario si no viene de la BD
                    // Supongamos que $regla['precio_insumo_unitario'] es lo que cuesta 1 gr/ml/un
                    $precio_unitario = $regla['costo_insumo'] ?? 0; 
                    $costo_total_rendimiento = $precio_unitario * $regla['cantidad_usada'];
                ?>
                    <div class="col card-regla-item">
                        <div class="card h-100 shadow-sm border-2 border-dark" style="border-radius: 15px; overflow: hidden;">
                            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                                <span class="fw-bold">🍽️ <?= htmlspecialchars(strtoupper($regla['plato'])) ?></span>
                                <form action="<?= BASE_URL ?>/index.php?route=config/eliminarRegla" method="POST" class="m-0">
                                    <input type="hidden" name="regla" value="<?= (int) $regla['id'] ?>">
                                    <button class="btn btn-sm btn-outline-danger border-0" type="submit" onclick="return confirmarEliminacion();">🗑️</button>
                                </form>
                            </div>
                            <div class="card-body bg-light">
                                <div class="mb-3">
                                    <small class="text-muted d-block">Insumo utilizado:</small>
                                    <span class="fw-bold text-primary">📦 <?= htmlspecialchars($regla['insumo']) ?></span>
                                </div>

                                <div class="row text-center mb-3">
                                    <div class="col-6 border-end">
                                        <small class="text-muted d-block">Cantidad</small>
                                        <span class="badge bg-secondary fs-6"><?= $regla['cantidad_usada'] ?> <?= $regla['unidad'] ?? 'un' ?></span>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Rinde</small>
                                        <span class="badge bg-success fs-6"><?= (int)$regla['rendimiento'] ?> Platos</span>
                                    </div>
                                </div>

                                <div class="p-2 rounded-3" style="background: #e9ecef; border: 1px dashed #6c757d;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="fw-bold">Costo por Rendimiento:</small>
                                        <span class="text-dark fw-bold">$<?= number_format($costo_total_rendimiento, 2, ',', '.') ?></span>
                                    </div>
                                    <hr class="my-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted small">Costo Insumo (Unit.):</small>
                                        <span class="small">$<?= number_format($precio_unitario, 2, ',', '.') ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0 text-center">
                                <small class="text-muted">ID Regla: #<?= $regla['id'] ?></small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 w-100 text-center py-5">
                    <p class="opacity-50">⚓ No hay reglas de producción. ¡Añade una arriba!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
    </div>
    </div>
    <script src="/Sistema_mugiwara/public/js/redireccion.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/Sistema_mugiwara/public/js/config/buscadorConfig.js"></script>
    <script src="/Sistema_mugiwara/public/js/alertas.js"></script>
    <script>

        function filtrarTarjetas() {
    let input = document.getElementById('inputBusqueda').value.toLowerCase();
    let tarjetas = document.getElementsByClassName('card-regla-item');

    for (let i = 0; i < tarjetas.length; i++) {
        let contenido = tarjetas[i].innerText.toLowerCase();
        if (contenido.includes(input)) {
            tarjetas[i].style.display = "";
        } else {
            tarjetas[i].style.display = "none";
        }
    }
}
    </script>
</body>

</html>