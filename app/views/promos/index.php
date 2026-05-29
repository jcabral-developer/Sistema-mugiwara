<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurador de Promociones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="icon" type="image/x-icon"
        href="/Sistema_mugiwara/public/img/Gemini_Generated_Image_b3vr0wb3vr0wb3vr-removebg-preview.png" />
    <link rel="stylesheet" href="/Sistema_mugiwara/public/css/cssPromo.css">

    <style>
        .bloqueado {
            opacity: 0.5;
            /* se ve más apagado */
            pointer-events: none;
            /* bloquea clicks en todo el div */
        }

        #valFinal {
            font-family: 'Tu-Fuente-Aqui', sans-serif;
            /* Si quieres que se vea como el de la preview, puedes usar: */
            font-weight: bold;
            font-style: italic;
        }

        /* Contenedor de la miniatura */
        .img-promo-container {
            width: 60px;
            height: 60px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Estilo de la imagen miniatura */
        .img-thumbnail-promo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Para que la imagen no se deforme */
            border-radius: 50%;
            /* Circular tipo moneda/recompensa */
            border: 2px solid var(--gold);
            /* Borde dorado de tu paleta */
            background-color: #fff;
            transition: transform 0.3s ease;
        }

        /* Efecto hover para verla un poquito más grande */
        .img-thumbnail-promo:hover {
            transform: scale(1.5);
            z-index: 10;
            cursor: pointer;
        }

        /* Ajuste de la tabla para que el texto no quede pegado */
        .table td {
            vertical-align: middle;
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

    <div class="container-fluid py-4 px-lg-5">


        <ul class="nav nav-tabs border-0 container-fluid" id="promoTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="registro-tab" data-bs-toggle="tab" data-bs-target="#registro"
                    type="button">
                    <i class="fas fa-edit me-2"></i>ESTABLECER PROMOS
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="consulta-tab" data-bs-toggle="tab" data-bs-target="#consulta"
                    type="button">
                    <i class="fas fa-search me-2"></i>CONSULTAR PROMOS
                </button>
            </li>
        </ul>

        <div class="tab-content mt-3" id="promoTabsContent">


            <div class="tab-pane fade show active" id="registro" role="tabpanel">
                <div class="row g-5">

                    <div class="col-xl-8">
                        <div class="pergamino-anime p-4 p-md-5">

                            <div class="mb-4">
                                <label class="form-label">Nombre del Combo / Oferta</label>
                                <input type="text" id="inNombre" class="form-control form-control-lg border-2"
                                    placeholder="Ej: Combo Familiar" oninput="actualizarPreview()">
                            </div>

                            <div class="mb-5">
                                <label class="form-label">Tipo de Oferta</label>
                                <div class="row g-2 selector-tipo">
                                    <div class="col-4">
                                        <input type="radio" class="btn-check" name="tipo" id="fijo" value="fijo" checked
                                            onchange="cambiarTipo()">
                                        <label class="btn btn-tipo w-100 py-3" for="fijo"><i
                                                class="fas fa-sack-dollar d-block mb-1"></i> Precio Fijo</label>
                                    </div>
                                    <div class="col-4 bloqueado">
                                        <input type="radio" class="btn-check" name="tipo" id="porcentaje"
                                            value="porcentaje" onchange="cambiarTipo()">
                                        <label class="btn btn-tipo w-100 py-3" for="porcentaje"><i
                                                class="fas fa-percentage d-block mb-1"></i> % Descuento</label>
                                    </div>
                                    <div class="col-4 bloqueado">
                                        <input type="radio" class="btn-check" name="tipo" id="xyy" value="xyy"
                                            onchange="cambiarTipo()">
                                        <label class="btn btn-tipo w-100 py-3" for="xyy"><i
                                                class="fas fa-box-open d-block mb-1"></i> 2x1 / 3x2</label>
                                    </div>
                                </div>
                            </div>


                            <div class="pergamino-inner p-4 mb-5">
                                <label class="font-bangers fs-3 text-wood">
                                    <i class="fas fa-utensils me-1"></i>Añadir Platos
                                </label>

                                <!-- CONTENEDOR RELATIVO (CLAVE PARA EL DROPDOWN) -->
                                <div class="position-relative">

                                    <div class="input-group input-group-lg mb-2">
                                        <input type="text" id="busqueda" class="form-control border-wood"
                                            placeholder="Escribe el nombre del plato...">

                                    </div>

                                    <!-- DROPDOWN DE SUGERENCIAS -->
                                    <div id="sugerencias" class="list-group shadow"
                                        style="position: absolute; width: 100%; z-index: 999; max-height: 250px; overflow-y: auto;">
                                    </div>

                                </div>

                                <!-- LISTA DE PRODUCTOS AGREGADOS -->
                                <div id="listaProductos" class="mt-3"></div>
                            </div>


                            <div id="panelConfig" class="p-4 rounded-4 mb-5 shadow-sm border border-2">

                            </div>

                            <div class="mb-5">
                                <label class="form-label">Imagen de la promo (Opcional)</label>
                                <input type="file" class="form-control border-2" id="inImagen"
                                    onchange="leerImagen(this)">
                            </div>

                            <button class="btn btn-publicar w-100 shadow" onclick="guardarPromo()">
                                <i class="fas fa-share-square me-2"></i>REGISTRAR PROMOCIÓN
                            </button>
                        </div>
                    </div>


                    <div class="col-xl-4">
                        <div class="sticky-preview">
                            <div class="card-preview-pro">
                                <div class="ribbon-popular">OFERTA</div>

                                <div class="preview-img-container">
                                    <img id="preImg" src="/Sistema_mugiwara/public/img/promos/promosDefecto.png"
                                        class="preview-img-pro">
                                </div>

                                <div class="p-4">
                                    <div class="text-center mb-3">
                                        <span class="badge-categoria">MENÚ ESPECIAL</span>
                                        <h2 id="preNombre" class="display-name">Nombre del Combo</h2>
                                    </div>

                                    <div class="ticket-container">
                                        <div class="ticket-header">
                                            <i class="fas fa-receipt me-2"></i> DETALLE DEL PEDIDO
                                        </div>
                                        <div id="preLista" class="ticket-body">
                                            <span class='text-muted small'>Esperando selección...</span>
                                        </div>
                                    </div>

                                    <div class="price-section mt-4">
                                        <div class="row align-items-center">
                                            <div class="col-12 text-center">
                                                <small class="text-muted text-uppercase d-block mb-1 fw-bold"
                                                    style="letter-spacing: 1px;">Precio Final</small>
                                                <span id="preViejo" class="old-price-strikethrough">$0</span>
                                                <div class="main-price-container">
                                                    <span class="currency-symbol"></span>
                                                    <span id="preNuevo" class="new-price-bold">0</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer-pro">
                                    <i class="fas fa-utensils me-2">🍕</i>
                                    <i class="fas fa-utensils me-2">🍔</i>
                                    <i class="fas fa-utensils me-2">🌭</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="tab-pane fade" id="consulta" role="tabpanel">
            <div class="seccion-bodega p-4 rounded shadow">
                <div class="d-flex justify-content-between align-items-center mb-4 container-fluid">
                    <h2 class="font-bangers m-0 text-wood" style="text-shadow: 2px 2px 0px var(--gold);">
                        REGISTROS ACTIVOS
                    </h2>
                    <div class="grupo-busqueda">
                        <i class="fas fa-search icono-busqueda text-wood"></i>
                        <input type="text" id="inputBusqueda" placeholder="Buscar por descripción..." class="text-wood">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr class="text-center">
                                <th>Imagen</th>
                                <th>Estado</th>
                                <th>Descripción</th>
                                <th>Modalidad</th>
                                <th>Precio Normal</th>
                                <th>Precio Promo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaRegistrados"></tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/Sistema_mugiwara/public/js/redireccion.js"></script>
    <script>


        document.addEventListener("DOMContentLoaded", cargarPromos);
        let editandoId = null; //variable  para editar una promo
        async function cargarPromos() {
            try {
                const res = await fetch("index.php?route=promos/obtenerPromos");
                const data = await res.json();
                promosGlobal = data; // -- guardo todas las promos para futuras ediciones de las promos
                const tabla = document.getElementById("tablaRegistrados");
                tabla.innerHTML = "";

                data.forEach(promo => {

                    //  calcular precio normal
                    let precioPromo = parseFloat(promo.precio_total) || 0;
                    let precioNormal = promo.productos.reduce(
                        (acc, p) => acc + (p.precio * p.cantidad || 0),
                        0
                    );

                    let fila = `
            <tr class="text-center">

                <td>
                    <div class="img-promo-container">
                        <img  src="/Sistema_mugiwara/public/img/promos/${promo.imagen || 'promosDefecto.png'}"
                             class="img-thumbnail-promo shadow-sm">
                    </div>
                </td>

                <td>
                    <span class="badge-wanted px-3 py-1">ACTIVO</span>
                </td>

                <td class="fw-bold text-start">${promo.nombre}</td>

                <td>${traducirTipo(promo.tipo)}</td>

                <td class="text-muted text-decoration-line-through fs-5">
                    $${precioNormal.toLocaleString()}
                </td>

               <td class="text-danger fw-bold fs-4">
    $${precioPromo.toLocaleString()}
</td>

                <td>
                    <button class="btn btn-sm btn-dark" onclick="editarPromo(${promo.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="eliminarPromo(${promo.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>

            </tr>
            `;

                    tabla.innerHTML += fila;
                });

            } catch (error) {
                console.error(error);
            }
        }

        function traducirTipo(tipo) {
            const tipos = {
                'fijo': 'Precio Fijo',
                'porcentaje': 'Descuento %',
                'xyy': 'Promoción XxY'
            };
            return tipos[tipo] || tipo;
        }
    </script>

    <script>
        // editar
        let promosGlobal = []; // <-- variable global
        function editarPromo(id) {

            const promo = promosGlobal.find(p => p.id == id);

            if (!promo) return;
            editandoId = id;
            //  cambiar a la pestaña de edición
            const tab = new bootstrap.Tab(document.querySelector('#registro-tab'));
            tab.show();

            // === DATOS BÁSICOS ===
            document.getElementById("inNombre").value = promo.nombre;

            // === TIPO ===
            tipoActual = promo.tipo;
            document.querySelector(`input[name="tipo"][value="${promo.tipo}"]`).checked = true;

            cambiarTipo(); //  genera el panel dinámico

            // === PRODUCTOS ===
            productosEnPromo = promo.productos.map(p => ({
                id: p.id,
                nombre: p.nombre,
                precio: p.precio, //  importante
                cantidad: p.cantidad
                
            }));


            renderLista();

            // === VALORES SEGÚN TIPO ===
            setTimeout(() => {
                if (promo.tipo === 'fijo') {
                    document.getElementById('valFinal').value = promo.precio_total;
                }
                else if (promo.tipo === 'porcentaje') {
                    document.getElementById('valPorc').value = promo.valor;
                }
                else if (promo.tipo === 'xyy') {
                    document.getElementById('cantX').value = promo.cantidad_x;
                    document.getElementById('cantY').value = promo.cantidad_y;
                }

                actualizarPreview();
            }, 100);

            // === IMAGEN ===
            if (promo.imagen) {
                document.getElementById("preImg").src =
                    `/Sistema_mugiwara/public/img/promos/${promo.imagen}`;
            }

        }


    </script>

    <script>

        // eliminar


        async function eliminarPromo(id) {

            const confirm = await Swal.fire({
                title: "¿Eliminar promoción?",
                text: "Esta acción desactivará la promo",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#e84118",
                cancelButtonColor: "#4b2c20",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            });

            if (!confirm.isConfirmed) return;

            try {
                const res = await fetch("index.php?route=promos/eliminarPromo", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ id })
                });

                const data = await res.json();

                if (data.status === "ok") {
                    Swal.fire("Eliminado", "La promo fue desactivada", "success");
                    cargarPromos(); // 🔥 recarga la tabla sin refresh
                } else {
                    Swal.fire("Error", data.message || "No se pudo eliminar", "error");
                }

            } catch (error) {
                console.error(error);
                Swal.fire("Error", "Error de conexión", "error");
            }
        }
    </script>
    <script>

        async function guardarPromo() {
            if (productosEnPromo.length === 0) {
                Swal.fire({
                    title: "¡Faltan agregar un plato!",
                    text: "Agregá al menos un plato al combo",
                    icon: "warning",
                    confirmButtonColor: "#4b2c20"
                });
                return;
            }

            const nombre = document.getElementById('inNombre').value.trim();
            if (!nombre) {
                Swal.fire({
                    title: "¿Cómo se llama la promo?",
                    text: "Agrege un nombre a la promo",
                    icon: "warning",
                    confirmButtonColor: "#4b2c20"
                });
                return;
            }

            let precio = 0;

            if (tipoActual === 'fijo') {
                const valFinal = document.getElementById('valFinal');
                precio = parseFloat(valFinal?.value) || 0;

            } else if (tipoActual === 'porcentaje') {
                const porc = document.getElementById('valPorc')?.value || 0;
                precio = totalNormal - (totalNormal * (porc / 100));

            } else if (tipoActual === 'xyy') {
                const x = parseInt(document.getElementById('cantX')?.value) || 1;
                const y = parseInt(document.getElementById('cantY')?.value) || 1;
                precio = totalNormal > 0 ? (totalNormal / x) * y : 0;
            }

            // VALIDACIÓN FINAL
            if (precio <= 0) {
                Swal.fire({
                    title: "Precio inválido",
                    text: "La promo debe tener un precio mayor a 0",
                    icon: "warning",
                    confirmButtonColor: "#4b2c20"
                });
                return;
            }



            // === DATOS BASE ===
            const inputImagen = document.getElementById('inImagen');
            const imagenArchivo = inputImagen.files[0]; // Capturamos el archivo real

            // Usamos FormData para poder enviar el archivo físico
            const formData = new FormData();

            // Datos básicos
            formData.append("nombre", nombre);
            formData.append("tipo", tipoActual);
            formData.append("productos", JSON.stringify(productosEnPromo)); // Los productos van como string JSON
            formData.append("precio_total", Math.round(precio));
            if (editandoId) {
                formData.append("id", editandoId); // 🔥 si existe → EDITAR
            }

            // Datos según tipo
            if (tipoActual === 'fijo') {
                formData.append("valor", parseFloat(document.getElementById('valFinal').value) || 0);
            } else if (tipoActual === 'porcentaje') {
                formData.append("valor", parseFloat(document.getElementById('valPorc').value) || 0);
            } else if (tipoActual === 'xyy') {
                formData.append("cantidad_x", parseInt(document.getElementById('cantX').value) || 1);
                formData.append("cantidad_y", parseInt(document.getElementById('cantY').value) || 1);
            }

            // Si hay una imagen seleccionada, la agregamos al envío
            if (imagenArchivo) {
                formData.append("imagen", imagenArchivo);
            }



            // === CONFIRMACIÓN ===
            const confirm = await Swal.fire({
                title: "¿Lanzar esta promoción?",
                text: "Se registrará en el sistema con su imagen",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#27ae60",
                cancelButtonColor: "#e84118",
                confirmButtonText: "¡Sí!",
                cancelButtonText: "No, esperar"
            });

            if (!confirm.isConfirmed) return;

            Swal.showLoading();

            try {
                const res = await fetch("index.php?route=promos/enviarPromo", {
                    method: "POST",
                    // IMPORTANTE: Al usar FormData, NO se debe poner el Header "Content-Type"
                    // El navegador lo configura automáticamente incluyendo el "boundary"
                    body: formData
                });

                const response = await res.json();

                if (response.status === "ok") {
                    await Swal.fire({
                        title: "¡ÉXITO!",
                        text: "La promoción ha sido registrada correctamente",
                        icon: "success",
                        confirmButtonColor: "#fbc531"
                    });
                    location.reload();
                } else {
                    Swal.fire({ title: "Error", text: response.message, icon: "error" });
                }

            } catch (error) {
                console.error(error);
                Swal.fire({ title: "Error", text: "No se pudo contactar con el servidor", icon: "error" });
            }
        }
    </script>

    <script>
        const platosDisponibles = <?php echo json_encode($platos); ?>;

        // OCULTAR SUGERENCIAS SI HACÉS CLICK AFUERA
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.position-relative')) {
                document.getElementById('sugerencias').innerHTML = "";
            }
        });

        // BUSCADOR
        document.getElementById('busqueda').addEventListener('input', function () {
            const texto = this.value.toLowerCase();
            const contenedor = document.getElementById('sugerencias');
            contenedor.innerHTML = "";

            if (texto.length === 0) return;

            const filtrados = platosDisponibles.filter(p =>
                p.descripcion && p.descripcion.toLowerCase().includes(texto)
            );

            filtrados.forEach(p => {
                const item = document.createElement('button');
                item.type = "button";
                item.className = "list-group-item list-group-item-action";

                item.innerText = `${p.descripcion} - $${parseFloat(p.precio_venta).toLocaleString('es-AR')}`;

                item.onclick = () => agregarProductoReal(p);

                contenedor.appendChild(item);
            });
        });

        // AGREGAR PRODUCTO REAL
        function agregarProductoReal(plato) {

            let existente = productosEnPromo.find(p => p.id == plato.id);

            if (existente) {
                existente.cantidad++;
            } else {
                productosEnPromo.push({
                    id: plato.id,
                    nombre: plato.descripcion,
                    precio: parseFloat(plato.precio_venta),
                    cantidad: 1
                });
            }

            document.getElementById('busqueda').value = "";
            document.getElementById('sugerencias').innerHTML = "";

            renderLista();
        }
    </script>

    <script>
        let productosEnPromo = [];
        let tipoActual = 'fijo';

        function cambiarTipo() {
            const tipo = document.querySelector('input[name="tipo"]:checked').value;
            tipoActual = tipo;
            const panel = document.getElementById('panelConfig');

            if (tipo === 'fijo') {
                panel.innerHTML = `
                <label class="fw-bold mb-2">💰 Valor Final de la Promo ($)</label>
                <input type="number" id="valFinal" class="form-control form-control-lg" placeholder="0" oninput="actualizarPreview()">
            `;
            } else if (tipo === 'porcentaje') {
                panel.innerHTML = `
                <label class="fw-bold mb-2 small text-muted">Ajustar Descuento</label>
                <input type="range" id="valPorc" class="form-range" min="0" max="100" value="15" oninput="actualizarPreview()">
                <div class="text-center fw-black fs-1 text-primary"><span id="lblPorc">15</span>% OFF</div>
            `;
            } else {
                panel.innerHTML = `
                <div class="row text-center">
                    <div class="col-6">
                        <label class="fw-bold mb-2 small text-muted">ENTREGAS (X)</label>
                        <input type="number" id="cantX" class="form-control form-control-lg fw-bold text-center border-marine" value="2" oninput="actualizarPreview()">
                    </div>
                    <div class="col-6">
                        <label class="fw-bold mb-2 small text-muted">COBRAS (Y)</label>
                        <input type="number" id="cantY" class="form-control form-control-lg fw-bold text-center border-success" value="1" oninput="actualizarPreview()">
                    </div>
                </div>
            `;
            }
            actualizarPreview();
        }


        function renderLista() {
            const lista = document.getElementById('listaProductos');
            lista.innerHTML = "";
            productosEnPromo.forEach(p => {
                lista.innerHTML += `
                <div class="item-agregado p-3 d-flex justify-content-between align-items-center list-item-anim shadow-sm">
                    <div>
                        <div class="fw-bold text-wood">${p.nombre}</div>
                        <div class="text-success small fw-bold">$${p.precio.toLocaleString()} c/u</div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <input type="number" class="form-control border-dark fw-bold" style="width:70px" value="${p.cantidad}" onchange="actualizarCant(${p.id}, this.value)">
                        <button class="btn btn-outline-danger btn-sm border-0" onclick="eliminarProd(${p.id})"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            `;
            });
            actualizarPreview();
        }

        function actualizarCant(id, val) {
            let p = productosEnPromo.find(x => x.id === id);
            if (p) p.cantidad = parseInt(val) || 1;
            actualizarPreview();
        }

        function eliminarProd(id) {
            productosEnPromo = productosEnPromo.filter(x => x.id !== id);
            renderLista();
        }

        function actualizarPreview() {
            // Nombre
            const nombreVal = document.getElementById('inNombre').value;
            document.getElementById('preNombre').innerText = nombreVal || "Nombre de la Oferta";

            // Items
            const preLista = document.getElementById('preLista');
            let totalNormal = 0;

            if (productosEnPromo.length === 0) {
                preLista.innerHTML = "<span class='text-muted small italic'>........</span>";
            } else {
                preLista.innerHTML = productosEnPromo.map(p => {
                    totalNormal += (p.precio * p.cantidad);
                    return `<div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                            <span>${p.cantidad}x ${p.nombre}</span>
                            <span>$${(p.precio * p.cantidad).toLocaleString()}</span>
                        </div>`;
                }).join("");
            }

            // Precios
            let precioFinal = 0;
            if (tipoActual === 'fijo') {
                precioFinal = parseFloat(document.getElementById('valFinal')?.value) || 0;
            } else if (tipoActual === 'porcentaje') {
                let porc = document.getElementById('valPorc')?.value || 0;
                document.getElementById('lblPorc').innerText = porc;
                precioFinal = totalNormal - (totalNormal * (porc / 100));
            } else if (tipoActual === 'xyy') {
                let x = parseInt(document.getElementById('cantX')?.value) || 1;
                let y = parseInt(document.getElementById('cantY')?.value) || 1;
                precioFinal = totalNormal > 0 ? (totalNormal / x) * y : 0;
            }

            document.getElementById('preViejo').innerText = "$" + totalNormal.toLocaleString();
            document.getElementById('preNuevo').innerText = "$" + Math.round(precioFinal).toLocaleString();
        }

        function leerImagen(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) { document.getElementById('preImg').src = e.target.result; }
                reader.readAsDataURL(input.files[0]);
            }
        }

        cambiarTipo();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>