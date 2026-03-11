
function cambiar(seccion){

    const cont = document.getElementById("contenido");



    if(seccion=="pedidos"){
        window.location.href = "index.php?route=pedidos";
    }
    if(seccion=="stock"){
        window.location.href = "index.php?route=stock";
    }
    if(seccion=="precios"){
        window.location.href = "index.php?route=precios";
    }
    if(seccion=="caja"){
        cont.innerHTML="<h2>💰 Caja</h2><p>Ventas, pagos y ganancias.</p>";
    }
    if(seccion=="reportes"){
        cont.innerHTML="<h2>📜 Reportes</h2><p>Lo que más se vende y pérdidas.</p>";
    }
    if(seccion=="config"){
          window.location.href = "index.php?route=config";
    }
}


