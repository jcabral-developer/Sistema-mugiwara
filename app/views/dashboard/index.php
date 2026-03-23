<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon"
        href="/Sistema_mugiwara/public/img/Gemini_Generated_Image_b3vr0wb3vr0wb3vr-removebg-preview.png" />
    <link rel="stylesheet" href="/Sistema_mugiwara/public/css/cssDashboard.css">
    <title>Menu del sistema</title>
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
            
            <div onclick="confirmarLogout()" style="background: var(--red); border: 2px solid #000; box-shadow: 2px 2px 0px #000;">
            🚪 Salir
        </div>


        </div>

    </div>


    <script src="/Sistema_mugiwara/public/js/redireccion.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <script src="/Sistema_mugiwara/public/js/alertaLogin.js"></script>

</body>

</html>