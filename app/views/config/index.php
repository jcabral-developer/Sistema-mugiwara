<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Configuración - Mugiwara</title>
    <link rel="stylesheet" href="../../public/css/cssConfig.css">
    <link rel="icon" type="image/x-icon" href="../../public/img/Gemini_Generated_Image_b3vr0wb3vr0wb3vr-removebg-preview.png" />
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
        <div onclick="location.href='produccion.html'">🍳 Producción</div>
        <div onclick="location.href='ganancias.html'">💰 Ganancias</div>
        <div onclick="location.href='reportes.html'">📜 Reportes</div>
        <div onclick="location.href='configuraciones.html'">🛠️ Config</div>
    </div>
</div>
<div class="main">
    <div class="card-wide">
        <h1 class="titulo-principal">⚙️ CONFIGURACIONES</h1>

        <div class="grid-main">
            <div class="section">
                <h2>🧾 REGISTRAR INSUMO</h2>
                <div class="box">
                    <div class="campo">
                        <label>Nombre del insumo</label>
                        <input type="text" id="ins_nombre" placeholder="Ej: Harina 0000">
                    </div>
                    
                    <div class="campo">
                        <label>Unidad de medida</label>
                        <select id="ins_unidad">
                            <option value="kg">Kilogramos (kg)</option>
                            <option value="g">Gramos (g)</option>
                            <option value="u">Unidades (u)</option>
                        </select>
                    </div>

                    <button class="btn-pro" onclick="notificar('Insumo Guardado')">➕ AGREGAR INSUMO</button>
                </div>
            </div>

            <div class="section">
                <h2>🍕 NUEVO PRODUCTO</h2>
                <div class="box">
                    <div class="campo">
                        <label>Nombre del plato / producto</label>
                        <input type="text" placeholder="Ej: Pizza Muzzarella">
                    </div>

                    <button class="btn-pro">➕ AGREGAR PRODUCTO</button>
                </div>
            </div>
        </div>

        <div class="section">
            
            <div class="box grid-receta">
                <div class="form-receta">
            <h2 id="h2-titulo">⚙️ DEFINIR RENDIMIENTO</h2>
                    <div class="campo-row">
                        <div class="campo">
                            <label>Producto</label>
                            <select><option>Pizza</option></select>
                        </div>
                        <div class="campo">
                            <label>Insumo</label>
                            <select><option>Harina</option></select>
                        </div>
                    </div>

                    <div class="campo-row">
                        <div class="campo">
                            <label>Cantidad usada</label>
                            <input type="number" placeholder="Ej: 250">
                        </div>
                        <div class="campo">
                            <label>Unidad</label>
                            <select><option>Gramos (g)</option></select>
                        </div>
                    </div>

                    <button class="btn-pro" style="background: var(--wood); color: var(--gold);">💾 GUARDAR REGLA DE PRODUCCIÓN</button>
                </div>

                <div class="tabla-container">
                    <h3>📜 REGLAS ACTIVAS</h3>
                    <table class="tabla-pro">
                        <thead>
                            <tr>
                                <th>INSUMO</th>
                                <th>PRODUCTO</th>
                                <th>RENDIMIENTO</th>
                                <th>ACCION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Harina (1kg)</td>
                                <td>Pizza</td>
                                <td><span class="tag-rinde">4 Unidades</span></td>
                                <td><button class="btn-del">🗑️</button></td>
                            </tr>
                            <tr>
                                <td>Carne (1kg)</td>
                                <td>Milanesa</td>
                                <td><span class="tag-rinde">6 Unidades</span></td>
                                <td><button class="btn-del">🗑️</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>