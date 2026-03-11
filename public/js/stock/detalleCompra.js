function editarCompra(id) {
 
    fetch(`${BASE_URL}/index.php?route=stock/obtenerDetalleCompra&id=${id}`)
        .then(res => res.json())
        .then(data => {

            if (!data.ok) {
                alert(data.mensaje);
                return;
            }

            cargarModal(data.compra);
            abrirDetalle();

        })
        .catch(err => {
            console.error(err);
            alert("Error al obtener el detalle");
        });
}




function abrirDetalle() {
    document.getElementById("modalDetalleCompra").style.display = "flex";
}

function cerrarDetalle() {
    document.getElementById("modalDetalleCompra").style.display = "none";
}

function cargarModal(compra) {

    console.log("Datos recibidos de la BD:", compra);
    document.getElementById("det-id").innerText = "#" + compra.id;
    const fecha = new Date(compra.fecha + 'T00:00:00');
    document.getElementById("det-fecha").innerText = fecha.toLocaleDateString("es-AR"); 
    document.getElementById("det-comprador").innerText = compra.comprador;

    let html = "";
    let sumaReal = 0; // Variable para calcular la suma nosotros mismos

    compra.items.forEach(item => {
        const cant = parseFloat(item.cantidad) || 0;
        // IMPORTANTE: Asegúrate de que 'precio_unitario' sea el monto total del insumo en tu JSON
        const precioMonto = parseFloat(item.precio_unitario) || 0; 
        
        sumaReal += precioMonto; // Sumamos el monto al total

        html += `
        <tr>
            <td class="text-uppercase fw-bold">${item.descripcion}</td>
            <td class="text-center">${cant}</td>
            <td class="text-center">${item.unidad_medida}</td>
            <td class="text-end">$ ${precioMonto.toLocaleString('es-AR', {minimumFractionDigits: 0})}</td>
        </tr>`;
    });

    document.getElementById("det-items").innerHTML = html;
    
    // Mostramos la sumaReal calculada en el momento para que coincida con la vista
    document.getElementById("det-total").innerText = "$ " + sumaReal.toLocaleString('es-AR', {minimumFractionDigits: 0});
}