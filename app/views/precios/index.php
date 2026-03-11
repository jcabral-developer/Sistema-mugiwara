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

            <div onclick="cambiar('stock')">🍖 Stock <span  class="badge"><?php echo $bajoStock ? 'Bajo' : '' ;?></span></div>


             <div onclick="cambiar('precios')">🍳 Precios</div>

            <div onclick="cambiar('caja')">💰 Ganancias</div>

            <div onclick="cambiar('reportes')">📜 Reportes</div>

            <div onclick="cambiar('config')">🛠️ Config</div>


        </div>

    </div>

    <div class="container-mugiwara">
        <div class="row mb-4 align-items-center">
            <div class="col-md-7">
                <h1 class="font-bangers text-white" style="font-size: 3.5rem; text-shadow: 4px 4px 0px #000;">🍱 LISTA DE RECOMPENSAS (PRECIOS)</h1>
                <p class="fw-bold" style="color: var(--gold);">Ajusta los Berries necesarios para cada plato según el costo de los insumos.</p>
            </div>
            <div class="col-md-5 text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <input type="text" class="form-control border-3 border-dark w-50" placeholder="Buscar plato (Haki)...">
                    <button class="btn-mugiwara">➕ NUEVO PLATO</button>
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
                            <th>ESTADO</th>
                            <th class="text-center">GESTIÓN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Pizza Muzzarella</td>
                            <td class="text-costo">$1.385</td>
                            <td class="text-margen">120%</td>
                            <td class="text-precio"><strong>$3.047</strong></td>
                            <td class="text-ganancia">$1.662</td>
                            <td><span class="badge bg-success border border-dark">ACTIVO</span></td>
                            <td class="text-center">
                                <button class="btn-config" data-bs-toggle="modal" data-bs-target="#modalEditor">⚙️ AJUSTAR</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Sánguche Mila <span class="text-danger">⚠</span></td>
                            <td class="text-costo">$1.200</td>
                            <td class="text-margen text-danger">25%</td>
                            <td class="text-precio"><strong>$1.500</strong></td>
                            <td class="text-ganancia text-danger">$300</td>
                            <td><span class="badge bg-warning text-dark border border-dark">MARGEN BAJO</span></td>
                            <td class="text-center">
                                <button class="btn-config">⚙️ AJUSTAR</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditor" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content modal-content-pergamino">
                <div class="modal-header border-bottom border-dark">
                    <h5 class="modal-title font-bangers fs-2">⚙️ CONFIGURACIÓN DE BERRIES</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card p-3 border-dark mb-3 bg-white shadow-sm">
                                <h6 class="font-bangers text-primary fs-4">🔪 RECETA Y COSTOS</h6>
                                <table class="table table-sm mt-2 align-middle">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Insumo</th>
                                            <th>Cantidad</th>
                                            <th>Costo Unit.</th>
                                            <th>Parcial</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-bold">
                                        <tr><td>Harina</td><td>300g</td><td>$1.20</td><td>$360</td></tr>
                                        <tr><td>Muzzarella</td><td>200g</td><td>$4.00</td><td>$800</td></tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-secondary fw-bold">
                                            <td colspan="3" class="text-end">COSTO TOTAL:</td>
                                            <td class="text-danger">$1.385</td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <button class="btn btn-sm btn-dark font-bangers">🔄 RECALCULAR DESDE STOCK</button>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 border border-dark rounded bg-white shadow-sm">
                                <h6 class="font-bangers text-danger fs-4">📈 MARGEN</h6>
                                <div class="mb-3">
                                    <label class="small fw-bold">PORCENTAJE (%)</label>
                                    <input type="number" id="inputMargen" class="form-control border-dark fw-bold fs-4" value="120" oninput="calcularPrecio()">
                                </div>
                                <div class="text-center p-3 bg-light border border-dark rounded">
                                    <span class="small fw-bold text-muted">PRECIO FINAL</span>
                                    <h2 class="font-bangers text-success display-5" id="precioSugerido">$3.047</h2>
                                    <hr class="border-dark">
                                    <span class="small fw-bold">GANANCIA</span>
                                    <h4 class="text-ganancia font-bangers" id="gananciaVenta">$1.662</h4>
                                </div>
                                <div class="d-grid gap-2 mt-4">
                                    <button class="btn-mugiwara fs-4">💾 GUARDAR</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const costoFijo = 1385;
        function calcularPrecio() {
            const margen = document.getElementById('inputMargen').value;
            const precioFinal = costoFijo + (costoFijo * (margen / 100));
            const ganancia = precioFinal - costoFijo;
            document.getElementById('precioSugerido').innerText = `$${Math.round(precioFinal)}`;
            document.getElementById('gananciaVenta').innerText = `$${Math.round(ganancia)}`;
        }
    </script>
      <script src="/Sistema_mugiwara/public/js/redireccion.js"></script>
</body>
</html>