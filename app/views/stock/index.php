<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock - Mugiwara</title>
    <link rel="stylesheet" href="../../public/css/cssDeModalStock.css">
    <link rel="stylesheet" href="../../public/css/cssStock.css">
     <link rel="icon" type="image/x-i
    con" href="../../public/img/Gemini_Generated_Image_b3vr0wb3vr0wb3vr-removebg-preview.png" />
    <style>
        /* [Aquí va tu CSS que te pego más abajo] */
    </style>
</head>
<body>

<div class="topbar">
 <div class="logo-wrapper">
    <img src="../../public/img/Gemini_Generated_Image_b3vr0wb3vr0wb3vr-removebg-preview.png" alt="Logo Mugiwara" class="logo-img">
    <a href="index.html" class="logo">MUGIWARA</a>
</div>
    <div class="menu">
        <div onclick="location.href='index.html'">⚔️ Pedidos</div>
        <div onclick="location.href='stock.html'">🍖 Stock <span class="badge">Bajo</span></div>
        <div>🍳 Producción</div>
        <div>💰 Caja</div>
        <div>📜 Reportes</div>
        <div>🛠️ Config</div>
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
        <div class="grid-cabecera">
            <div class="campo-grupo">
                <label>📅 FECHA</label>
                <input type="date" value="2026-01-16">
            </div>
            <div class="campo-grupo">
                <label>🧑 PROVEEDOR</label>
                <input type="text" placeholder="Nombre del proveedor">
            </div>
        </div>
        <div class="seccion-insumos">
            <h3>DETALLE DEL PRODUCTO</h3>
            <div class="grid-cabecera" style="background: white;">
                <div class="campo-grupo">
                    <label>INSUMO</label>
                    <input type="text">
                </div>
                <div class="campo-grupo">
                    <label>CANTIDAD</label>
                    <input type="number" placeholder="0.00">
                </div>
                <div class="campo-grupo">
                    <label>PRECIO TOTAL ($)</label>
                    <input type="number" placeholder="Costo total">
                </div>
            </div>
        </div>
        <div class="footer-acciones">
            <button id="btnCerrar" class="btn-cancelar">DESCARTAR</button>
            <button class="btn-registrar">⚓ REGISTRAR</button>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById("miModal");
    const btnAbrir = document.getElementById("btnAbrir");
    const btnCerrar = document.getElementById("btnCerrar");

    btnAbrir.onclick = () => modal.style.display = "flex";
    btnCerrar.onclick = () => modal.style.display = "none";
    window.onclick = (e) => { if (e.target == modal) modal.style.display = "none"; }

    function filtrarInsumos() {
        let input = document.getElementById("inputBusqueda").value.toUpperCase();
        let filas = document.getElementById("tablaCuerpo").getElementsByTagName("tr");
        for (let i = 0; i < filas.length; i++) {
            let texto = filas[i].getElementsByTagName("td")[0].textContent.toUpperCase();
            filas[i].style.display = texto.includes(input) ? "" : "none";
        }
    }

    function limpiarBusqueda() {
        document.getElementById("inputBusqueda").value = "";
        filtrarInsumos();
    }
</script>
</body>
</html>