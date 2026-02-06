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

    <!-- ===== BARRA SUPERIOR ===== -->
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


    <!-- ===== CONTENIDO ===== -->

    <div class="main">

        <div class="card" id="contenido">

            <div class="titulo">Bienvenido al Sistema MUGIWARA</div>

            <div class="sub">Gestión de pedidos, stock y ganancias</div>

            <div class="imagen-negocio" onclick="entrarSistema()"></div>

            <p>Este sistema te permite controlar todo el negocio de comidas desde un solo lugar.</p>

        </div>

    </div>


    <script src="/Sistema_mugiwara/public/js/redireccion.js"></script>

    <script>
        function entrarSistema() {

            const audio = document.getElementById("introSound");
            audio.currentTime = 0; // por si hacen click 2 veces
            audio.play();
            setTimeout(() => {

                window.location.href = "index.php";

            }, 20000);

        }
    </script>

    <audio id="introSound" src="sounds/Voicy_One piece intro.mp3"></audio>

</body>

</html>