<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Configuración - Mugiwara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Sistema_mugiwara/public/css/cssConfig.css">
    <link rel="icon" type="image/x-icon"
        href="/Sistema_mugiwara/public/img/Gemini_Generated_Image_b3vr0wb3vr0wb3vr-removebg-preview.png" />
    <style>
        /* Estilos personalizados para adaptar las pestañas de Bootstrap al estilo Mugiwara */
        .nav-tabs-pirata {
            border-bottom: 3px solid var(--wood, #8B4513);
            margin-bottom: 25px;
            gap: 5px;
        }
        .nav-tabs-pirata .nav-link {
            font-family: var(--font-pirata, 'Impact', sans-serif);
            font-size: 1.2rem;
            color: #fff;
            background: rgba(0, 0, 0, 0.4);
            border: 2px solid var(--wood, #8B4513);
            border-bottom: none;
            border-radius: 8px 8px 0 0;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }
        .nav-tabs-pirata .nav-link:hover {
            color: var(--gold, #FFD700);
            background: rgba(0, 0, 0, 0.6);
            border-color: var(--gold, #FFD700);
        }
        .nav-tabs-pirata .nav-link.active {
            color: #000;
            background: var(--gold, #FFD700);
            border-color: var(--gold, #FFD700);
            font-weight: bold;
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
            <div onclick="cambiar('stock')">🍖 Stock <span class="badge"><?php echo $bajoStock ? 'Bajo' : ''; ?></span></div>
            <div onclick="cambiar('precios')">🍳 Precios</div>
            <div onclick="cambiar('caja')">💰 Ganancias</div>
            <div onclick="cambiar('reportes')">📜 Reportes</div>
            <div onclick="cambiar('config')">🛠️ Config</div>
        </div>
    </div>

    <div class="main">
        <div class="card-wide">
            <h1 class="titulo-principal">⚙️ CONFIGURACIONES</h1>

            <ul class="nav nav-tabs nav-tabs-pirata" id="configTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="gestion-tab" data-bs-toggle="tab" data-bs-target="#gestion-pane" type="button" role="tab" aria-controls="gestion-pane" aria-selected="true">
                        🛡️ PANEL DE GESTIÓN
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="consultas-tab" data-bs-toggle="tab" data-bs-target="#consultas-pane" type="button" role="tab" aria-controls="consultas-pane" aria-selected="false">
                        📜 TABLA DE CONSULTAS
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="configTabsContent">
                
                <div class="tab-pane fade show active" id="gestion-pane" role="tabpanel" aria-labelledby="gestion-tab" tabindex="0">
                    <div class="grid-main">
                        <div class="form-receta">
                            <form action="<?= BASE_URL ?>/index.php?route=config/rendimiento" method="POST">
                                <h2 id="h2-titulo">⚙️ DEFINIR RENDIMIENTO</h2>
                                <div class="campo-row">
                                    <div class="campo">
                                        <label>Platos</label>
                                        <select name="producto_id" required>
                                            <option value="">Seleccione un plato</option>
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
                                            <option value="kg">Kilos</option>
                                            <option value="un">Unidad</option>
                                            <option value="ml">Mililitros</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="campo">
                                    <label>¿CUANTO RINDE?</label>
                                    <input type="number" name="rendimiento" placeholder="Ej: 250" required>
                                </div>

                                <button type="submit" value="rendimiento" class="btn-pro"
                                    style="background: var(--wood); color: var(--gold); width: 100%;">💾 GUARDAR REGLA DE PRODUCCIÓN</button>
                            </form>
                        </div>

                        <div class="section">
                            <h2>🍕 ALTAS DEL SISTEMA</h2>
                            <div class="box">
                                <form action="<?= BASE_URL ?>/index.php?route=config/plato" method="POST">
                                    <div class="campo">
                                        <label>Nombre del plato / producto</label>
                                        <input type="text" placeholder="Ej: Pizza Muzzarella" name="producto" required>
                                    </div>
                                    <button class="btn-pro" name="plato" value="value" style="width: 100%;">➕ REGISTRAR PLATO</button>
                                </form>

                                <br><hr><br>

                                <form action="<?= BASE_URL ?>/index.php?route=config/insumo" method="POST">
                                    <div class="campo">
                                        <label>Nombre del Insumo</label>
                                        <input type="text" placeholder="Ej: Harina" name="insumo" required>
                                        <label class="mt-2">Unidad de medida</label>
                                        <select name="unidad_medida" id="unidad_medida">
                                            <option value="un">Unidad</option>
                                            <option value="gr">Gramos (Kilos)</option>
                                            <option value="ml">Mililitros</option>
                                        </select>
                                    </div>
                                    <button type="submit" name="enviar" value="enviar" class="btn-pro" style="width: 100%;">➕ REGISTRAR INSUMO</button>
                                </form>

                                <br><hr><br>

                                <form action="<?= BASE_URL ?>/index.php?route=config/ingredienteEspecial" method="POST">
                                    <h3 style="font-family: var(--font-pirata, 'Impact', sans-serif); color: var(--gold); font-size: 1.4rem; margin-bottom: 15px;">
                                        🧪 INGREDIENTE ESPECIAL
                                    </h3>
                                    
                                    <div class="campo">
                                        <label>Seleccionar Plato Destino</label>
                                        <select name="especial_producto_id" required>
                                            <option value="">¿A qué plato pertenece?</option>
                                            <?php foreach ($productos as $producto): ?>
                                                <option value="<?= $producto['id'] ?>">
                                                    <?= ucfirst(htmlspecialchars($producto['descripcion'])) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="campo mt-2">
                                        <label>Insumo Especial</label>
                                        <select name="especial_insumo_id" required>
                                            <option value="">Seleccione el insumo</option>
                                            <?php foreach ($insumos as $insumo): ?>
                                                <option value="<?= $insumo['id'] ?>">
                                                    <?= ucfirst(htmlspecialchars($insumo['descripcion'])) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="campo-row mt-2">
                                        <div class="campo">
                                            <label>Cantidad a usar</label>
                                            <input type="number" step="0.01" name="especial_cantidad" placeholder="Ej: 50" required>
                                        </div>
                                        <div class="campo">
                                            <label>Unidad</label>
                                            <select name="especial_unidad" required>
                                                <option value="gr">Gramos</option>
                                                <option value="kg">Kilos</option>
                                                <option value="ml">Mililitros</option>
                                                <option value="un">Unidad</option>
                                            </select>
                                        </div>
                                    </div>

                                    <button type="submit" name="enviar_especial" class="btn-pro mt-3" style="background: var(--gold); color: #000; width: 100%;">
                                        ✨ AÑADIR INGREDIENTE ESPECIAL
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div> </div>

                <div class="tab-pane fade" id="consultas-pane" role="tabpanel" aria-labelledby="consultas-tab" tabindex="0">
                    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
                        <h2 class="mb-0" style="color: #503c3c; font-family: var(--font-pirata, 'Impact', sans-serif);">📜 RENDIMIENTO Y COSTOS POR PLATO</h2>
                        <div class="buscador-container w-50">
                            <div class="grupo-busqueda">
                                <span class="icono-busqueda">🔍</span>
                                <input type="text" id="inputBusqueda" placeholder="Buscar plato o insumo..." onkeyup="filtrarInsumos()">
                            </div>
                        </div>
                    </div>

                    <div class="tabla-container">
                        <table class="tabla-pro" id="tablaReglas">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>🍽️ Plato</th>
                                    <th>📦 Insumo</th>
                                    <th>Cantidad</th>
                                    <th>Rinde</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($reglas)): ?>
                                    <?php foreach ($reglas as $regla): ?>
                                        <tr>
                                            <td class="text-muted">#<?= $regla['id'] ?></td>
                                            <td class="fw-bold text-uppercase"><?= htmlspecialchars($regla['plato']) ?></td>
                                            <td class="text-primary"><?= htmlspecialchars(ucfirst($regla['insumo'])) ?></td>
                                            <td><span class="tag-rinde"><?= $regla['cantidad_usada'] ?></span></td>
                                            <td class="fw-bold"><?= (int) $regla['rendimiento'] ?> Platos</td>
                                            <td>
                                                <form action="<?= BASE_URL ?>/index.php?route=config/eliminarRegla" method="POST" class="m-0 form-eliminar">
                                                    <input type="hidden" name="regla" value="<?= (int) $regla['id'] ?>">
                                                    <button class="btn-del" type="button" onclick="confirmarEliminacionSweet(this)">🗑️</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4 opacity-50">
                                            ⚓ No hay reglas de producción. ¡Mira el panel de gestión para añadir una!
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div> </div> </div> <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- MANEJO DE ERRORES ---
        <?php if (!empty($_SESSION['errores'])): 
            $msjError = implode("<br>", $_SESSION['errores']); ?>
            Swal.fire({
                title: '⚠️ Atención',
                html: '<?= $msjError ?>',
                icon: 'error',
                confirmButtonColor: '#3085d6'
            });
            <?php unset($_SESSION['errores']); ?>
        <?php endif; ?>

        // --- MANEJO DE ÉXITO ---
        <?php if (!empty($_SESSION['success'])): ?>
            Swal.fire({
                title: '✅ ¡Éxito!',
                text: '<?= $_SESSION['success'] ?>',
                icon: 'success',
                timer: 3000,
                showConfirmButton: false
            });
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
    });
    </script>
</body>
</html>
