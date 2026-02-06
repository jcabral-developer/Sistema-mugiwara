<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock - Mugiwara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Sistema_mugiwara/public/css/cssDeModalStock.css">
    <link rel="stylesheet" href="/Sistema_mugiwara/public/css/cssStock.css">
     <link rel="icon" type="image/x-icon" href="/Sistema_mugiwara/public/img/Gemini_Generated_Image_b3vr0wb3vr0wb3vr-removebg-preview.png" />
    <style>
        
    </style>
</head>
<body>

<div class="topbar">
 <div class="logo-wrapper">
    <img src="/Sistema_mugiwara/public/img/Gemini_Generated_Image_b3vr0wb3vr0wb3vr-removebg-preview.png" alt="Logo Mugiwara" class="logo-img">
    <a href="index.html" class="logo">MUGIWARA</a>
</div>
    <div class="menu">
        <div >⚔️ Pedidos</div>
        <div onclick="cambiar('stock')">🍖 Stock <span class="badge">Bajo</span></div>
        <div>🍳 Producción</div>
        <div>💰 Caja</div>
        <div>📜 Reportes</div>
        <div onclick="cambiar('config')">🛠️ Config</div>
    </div>
</div>

<div class="container">
    
    <a href="index.html" class="btn-volver">🔙 VOLVER AL INICIO</a>

    <div class="resumen-alertas">
        <div class="alerta-card critico">
            <h3>🚨 STOCK CRÍTICO</h3>
            <ul class="lista-critica">
                <li>Milanesas de carne: 15un</li>
                <li>Harina: 15un</li>
                <li>Levadura: 30gr</li>
                <li>Jamón: 15un</li>
                <li>Queso: 10kg</li>
            </ul>
        </div>

        <div class="alerta-card info-compra">
            
            <h3>💰 ÚLTIMA COMPRA</h3>
            <div class="detalle-compra">
                <p><strong>Insumo:</strong> Queso Muzzarella</p>
                <p><strong>Costo:</strong> $45,000</p>
                <hr>
                <p><strong>📊 Rendimiento Estimado:</strong></p>
                <ul class="lista-rinde">
                    <li>🍕 Pizzas: 30 unidades</li>
                    <li>🥪 Sanguches: 0 unidades</li>
                    <li>🥧 Tartas: 10 unidades</li>
                </ul>
            </div>
            <div class="acciones-compra">
                <button class="btn-consultar">📜 CONSULTAR COMPRAS</button>
                <button class="btn-compra-mini" id="btnAbrir">🍖 REGISTRAR COMPRA</button>
            </div>
        </div>
    </div>

    <div class="seccion-bodega">
        <div class="seccion-header">
            <h2>📜 LISTADO DE INSUMOS</h2>
        </div>

        <div class="buscador-container">
            <div class="grupo-busqueda">
                <span class="icono-busqueda">🔍</span>
                <input type="text" id="inputBusqueda" placeholder="Buscar insumo en la bodega..." onkeyup="filtrarInsumos()">
                <button class="btn-limpiar" onclick="limpiarBusqueda()">🧹 LIMPIAR</button>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>INSUMOS</th>
                    <th>CANTIDAD</th>
                    <th>ESTADO</th>
                </tr>
            </thead>
            <tbody id="tablaCuerpo">
                <tr>
                    <td>Queso Muzzarella</td>
                    <td>3 kg</td>
                    <td><b style="color:var(--red)">⚠️ BAJO</b></td>
                </tr>
                <tr>
                    <td>Papas</td>
                    <td>10 kg</td>
                    <td><b style="color:var(--op-green)">🟢 OK</b></td>
                </tr>
            </tbody>
        </table>
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
                <label>🧑 COMPRADOR / PROVEEDOR</label>
                <select id="compraComprador" class="form-control-mugiwara">
                    <option value="">Seleccione responsable...</option>
                    <option value="Victoria">Victoria</option>
                    <option value="Matias">Matias</option>
                </select>
            </div>
        </div>

        <hr>

        <div class="seccion-insumos bg-light p-3 rounded-3 shadow-sm mb-3">
            <h5 class="text-dark fw-bold mb-3">➕ AGREGAR ÍTEM</h5>
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="small fw-bold">INSUMO</label>
                    
                     <select id="itemInsumo" name="insumo_id" class="form-select" required>
                                    <option value="">Seleccione un insumo</option>
                                    <?php foreach ($insumos as $insumo): ?>
                                        <option value="<?= $insumo['id'] ?>">
                                            <?= htmlspecialchars($insumo['descripcion']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold">CANT.</label>
                    <input type="number" id="itemCantidad" class="form-control" placeholder="0">
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold">UNIDAD</label>
                    <select id="itemUnidad" class="form-select">
                        <option value="kg">Kilos (kg)</option>
                        <option value="gr">Gramos (gr)</option>
                        <option value="un">Unidades</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold">PRECIO ($)</label>
                    <input type="number" id="itemPrecio" class="form-control" placeholder="0.00">
                </div>
                <div class="col-md-2">
                    <button type="button" onclick="agregarItem()" class="btn btn-warning w-100 fw-bold">AÑADIR</button>
                </div>
            </div>
        </div>

        <div class="ticket-container">
            <table class="table table-sm table-hover align-middle" id="tablaDetalle">
                <thead class="table-dark">
                    <tr>
                        <th>Insumo</th>
                        <th>Cant.</th>
                        <th>Subtotal</th>
                        <th width="50px"></th>
                    </tr>
                </thead>
                <tbody id="cuerpoDetalle">
                    </tbody>
                <tfoot>
                    <tr class="table-warning font-weight-bold">
                        <td colspan="2" class="text-end"><strong>TOTAL DE LA COMPRA:</strong></td>
                        <td colspan="2"><strong id="totalCompra">$ 0.00</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="footer-acciones">
            <button id="btnCerrar" class="btn-cancelar">DESCARTAR</button>
             <button id="btnCerrar" class="btn-cancelar">REGISTRAR INSUMO</button>
            <button class="btn-registrar" onclick="guardarTodaLaCompra()">⚓ FINALIZAR COMPRA</button>
        </div>
    </div>
</div>
<script src="/Sistema_mugiwara/public/js/redireccion.js"></script>
<script src="/Sistema_mugiwara/public/js/stock/abrirModal.js"></script>
<script src="/Sistema_mugiwara/public/js/stock/agregarItemModal.js"></script>
</body>
</html>