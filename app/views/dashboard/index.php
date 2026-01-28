<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<link rel="icon" type="image/x-icon" href="/Sistema_mugiwara/public/img/Gemini_Generated_Image_b3vr0wb3vr0wb3vr-removebg-preview.png" />

<link rel="stylesheet" href="/Sistema_mugiwara/public/css/cssDashboard.css">

   <title>Menu del sistema</title>

</head>



<body>



<!-- ===== BARRA SUPERIOR ===== -->

<div class="topbar">

    <div class="logo">🏴‍☠️ MUGIWARA</div>



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



<script>

function cambiar(seccion){

    const cont = document.getElementById("contenido");



    if(seccion=="pedidos"){

        cont.innerHTML="<h2>⚔️ Pedidos</h2><p>Aquí se cargan y gestionan los pedidos.</p>";

    }

    if(seccion=="stock"){

        window.location.href="stock.html";

    }

    if(seccion=="produccion"){

        cont.innerHTML="<h2>🍳 Producción</h2><p>Qué cocinar y qué está en proceso.</p>";

    }

    if(seccion=="caja"){

        cont.innerHTML="<h2>💰 Caja</h2><p>Ventas, pagos y ganancias.</p>";

    }

    if(seccion=="reportes"){

        cont.innerHTML="<h2>📜 Reportes</h2><p>Lo que más se vende y pérdidas.</p>";

    }

    if(seccion=="config"){

        cont.innerHTML="<h2>🛠️ Configuración</h2><p>Precios, productos y usuarios.</p>";

    }

}



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