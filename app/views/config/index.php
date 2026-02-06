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
                alt="Logo Mugiwara" class="logo-img">
            <a href="index.php?route=" class="logo"> MUGIWARA</a>
        </div>
        <div class="menu">

            <div onclick="cambiar('pedidos')">⚔️ Pedidos</div>

            <div onclick="cambiar('stock')">🍖 Stock <span class="badge">Bajo</span></div>

            <div onclick="cambiar('produccion')">🍳 Producción</div>

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
                                            <?= htmlspecialchars($producto['descripcion']) ?>
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
                                            <?= htmlspecialchars($insumo['descripcion']) ?>
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
                                    <option value="Gramos">Gramos</option>
                                    <option value="kilos">kilos</option>
                                    <option value="1/2 tomate">1/2 tomate</option>
                                    <option value="1 tomate">1 tomate</option>
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
                        </div>

                        <button type="submit" name="enviar" value="enviar" class="btn-pro">➕ REGISTRAR
                            INSUMO</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="section mt-5">
    <div class="box tabla-container">
        <h3>📜 REGLAS DE PRODUCCIÓN ACTIVAS</h3>
         <div class="buscador-container">
            <div class="grupo-busqueda">
                <span class="icono-busqueda">🔍</span>
                <input type="text" id="inputBusqueda" placeholder="Buscar insumo en la bodega..." onkeyup="filtrarInsumos()">
            </div>
        </div>
        <div class="table-responsive">
            <table class="tabla-pro">
                <thead>
                    <tr>
                        <th>Insumo (Cantidad)</th>
                        <th>Producto Final</th>
                        <th>Rendimiento</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($reglas)): ?>
                        <?php foreach ($reglas as $regla): ?>
                            <tr>
                                <td>
                                    <?= htmlspecialchars($regla['insumo']) ?>
                                    <small>(<?= $regla['cantidad_usada'] . ' ' . htmlspecialchars($regla['unidad']) ?>)</small>
                                </td>
                                <td><?= htmlspecialchars($regla['plato']) ?></td>
                                <td>
                                    <span class="tag-rinde">
                                        <?= (int) $regla['rendimiento'] ?> Unidades
                                    </span>
                                </td>
                                <td>
                                    <form action="<?= BASE_URL ?>/index.php?route=config/eliminarRegla" method="POST" style="display:inline;">
                                        <input type="hidden" name="regla" value="<?= (int) $regla['id'] ?>">
                                        <button class="btn-del" type="submit" name="eliminar" value="eliminar"   onclick="return confirmarEliminacion();">🗑️</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align:center; opacity:0.6;">
                                No hay reglas de producción registradas
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
    </div>
    </div>
    <script src="/Sistema_mugiwara/public/js/redireccion.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/Sistema_mugiwara/public/js/config/buscadorConfig.js"></script>
    <script src="/Sistema_mugiwara/public/js/alertas.js"></script>
</body>

</html>