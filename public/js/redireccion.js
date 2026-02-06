
function cambiar(seccion){

    const cont = document.getElementById("contenido");



    if(seccion=="pedidos"){
        cont.innerHTML="<h2>⚔️ Pedidos</h2><p>Aquí se cargan y gestionan los pedidos.</p>";
    }
    if(seccion=="stock"){
        window.location.href = "index.php?route=stock";
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
          window.location.href = "index.php?route=config";
    }
}
