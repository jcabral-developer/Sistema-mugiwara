
    const modal = document.getElementById("miModal");
    const btnAbrir = document.getElementById("btnAbrir");
    const btnCerrar = document.getElementById("btnCerrar");

    btnAbrir.onclick = () => modal.style.display = "flex";
    btnCerrar.onclick = () => modal.style.display = "none";
    window.onclick = (e) => { if (e.target == modal) modal.style.display = "none"; }



 function filtrarCompras() {
    const desde = document.getElementById("fechaDesde").value;
    const hasta = document.getElementById("fechaHasta").value;
    const filas = document.querySelectorAll("#tablaCuerpoCompras tr[data-fecha]");

    filas.forEach(fila => {
        const fecha = fila.getAttribute("data-fecha"); // formato YYYY-MM-DD

        // Caso: no hay filtros -> mostrar todo
        if (!desde && !hasta) {
            fila.style.display = "";
            return;
        }

        // Comparaciones
        if (( !desde || fecha >= desde ) && ( !hasta || fecha <= hasta )) {
            fila.style.display = "";
        } else {
            fila.style.display = "none";
        }
    });
}



function limpiarBusquedaCompras() {
    document.getElementById("fechaDesde").value = "";
    document.getElementById("fechaHasta").value = "";
    filtrarCompras(); // refresca la tabla mostrando todo
}


    function filtrarInsumos() {

    let input = document.getElementById("inputBusqueda").value.toUpperCase();
    let tbody = document.getElementById("tablaCuerpo");
    let filas = tbody.getElementsByTagName("tr");

    for (let i = 0; i < filas.length; i += 2) {

        let filaPrincipal = filas[i];
        let filaDetalle = filas[i + 1];

        let texto = filaPrincipal.getElementsByTagName("td")[0].textContent.toUpperCase();

        if (texto.includes(input)) {
            filaPrincipal.style.display = "";
            filaDetalle.style.display = "";
        } else {
            filaPrincipal.style.display = "none";
            filaDetalle.style.display = "none";
        }
    }
}

    function filtrarPorRango() {

    let desde = document.getElementById("fechaDesde").value;
    let hasta = document.getElementById("fechaHasta").value;

    let filas = document.querySelectorAll("#tablaCuerpoCompras tr");

    filas.forEach(fila => {

        let fechaFila = fila.dataset.fecha; // ← magia

        let mostrar = true;

        if (desde && fechaFila < desde) mostrar = false;
        if (hasta && fechaFila > hasta) mostrar = false;

        fila.style.display = mostrar ? "" : "none";
    });
}





    function limpiarBusqueda() {
        document.getElementById("inputBusqueda").value = "";
     
        filtrarInsumos();
    }

    
