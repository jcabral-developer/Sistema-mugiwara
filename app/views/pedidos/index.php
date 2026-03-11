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

            <div onclick="cambiar('stock')">🍖 Stock <span  class="badge"><?php echo $bajoStock ? 'Bajo' : '' ;?></span></div>

             <div onclick="cambiar('precios')">🍳 Precios</div>

            <div onclick="cambiar('caja')">💰 Ganancias</div>

            <div onclick="cambiar('reportes')">📜 Reportes</div>

            <div onclick="cambiar('config')">🛠️ Config</div>


        </div>

    </div>

    <!-- <div class="topbar">
       
        <div class="stats-hoy">
            💰 Hoy: <strong>$125.000</strong> | 🧾 Pedidos: <strong>34</strong>
        </div>
    </div> -->

    <div class="main-container">
        <div class="row g-4">
            
            <div class="col-lg-7">
                <div class="card-pergamino h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="font-bangers m-0">🍱 MENÚ COMIDAS</h2>
                        <div class="input-group w-50">
                            <span class="input-group-text border-dark bg-white">🔍</span>
                            <input type="text" class="form-control border-dark" placeholder="Haki de observación (Buscar plato)...">
                        </div>
                    </div>

                    <div class="mb-3 d-flex gap-2">
                        <button class="btn btn-sm btn-dark font-bangers">TODOS</button>
                        <button class="btn btn-sm btn-outline-dark font-bangers">PIZZAS</button>
                        <button class="btn btn-sm btn-outline-dark font-bangers">SÁNDWICH</button>
                        <button class="btn btn-sm btn-outline-dark font-bangers">BEBIDAS</button>
                    </div>

                    <div class="menu-grid">
                        <div class="card-plato" onclick="agregarPlato('Pizza Muzzarella', 3047)">
                            <div class="fs-1">🍕</div>
                            <div class="fw-bold">PIZZA MUZZA</div>
                            <div class="precio">$3.047</div>
                        </div>
                        <div class="card-plato" onclick="agregarPlato('Sandwich Mila', 2400)">
                            <div class="fs-1">🥪</div>
                            <div class="fw-bold">SÁNGUCHE MILA</div>
                            <div class="precio">$2.400</div>
                        </div>
                        <div class="card-plato" onclick="agregarPlato('Papas Fritas', 2000)">
                            <div class="fs-1">🍟</div>
                            <div class="fw-bold">PAPAS FRITAS</div>
                            <div class="precio">$2.000</div>
                        </div>
                        <div class="card-plato" onclick="agregarPlato('Empanada J&Q', 875)">
                            <div class="fs-1">🥟</div>
                            <div class="fw-bold">EMPANADA J&Q</div>
                            <div class="precio">$875</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card-pergamino h-100 d-flex flex-column">
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

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="small fw-bold">TIPO:</label>
                            <select class="form-select form-select-sm border-dark">
                                <option>⚓ Local / Mesa</option>
                                <option>🏇 Delivery</option>
                                <option>🥡 Para llevar</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="small fw-bold">CLIENTE:</label>
                            <input type="text" class="form-control form-control-sm border-dark" placeholder="Nombre...">
                        </div>
                        <div class="col-12">
                            <textarea class="form-control form-control-sm border-dark" rows="1" placeholder="Observaciones (Sin cebolla, extra queso...)"></textarea>
                        </div>
                    </div>

                    <div class="total-section mb-3">
                        <span class="font-bangers fs-5">TOTAL A COBRAR:</span>
                        <h2 class="font-bangers m-0 text-white" id="total-monto">$0</h2>
                    </div>

                    <div class="row g-2">
                        <div class="col-8">
                            <button class="btn btn-success btn-mugiwara w-100" onclick="abrirCobro()">💰 COBRAR (ENTER)</button>
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
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content card-pergamino p-0">
                <div class="modal-header bg-dark text-white border-0">
                    <h5 class="modal-title font-bangers">CONFIRMAR PAGO</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <h1 class="font-bangers display-4" id="total-modal">$0</h1>
                    <div class="d-grid gap-2 mt-4">
                        <button class="btn btn-outline-dark fw-bold py-3" onclick="finalizarVenta('Efectivo')">💵 EFECTIVO</button>
                        <button class="btn btn-outline-primary fw-bold py-3" onclick="finalizarVenta('Transferencia')">📱 TRANSFERENCIA / MP</button>
                        <button class="btn btn-outline-info fw-bold py-3" onclick="finalizarVenta('Tarjeta')">💳 TARJETA</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/Sistema_mugiwara/public/js/pedidos/funciones_de_pedidos.js"></script>
    <script src="/Sistema_mugiwara/public/js/redireccion.js"></script>
</body>
</body>
</body>
</html>