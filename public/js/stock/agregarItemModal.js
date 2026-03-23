
let itemsCompra = [];
let insumosNuevos = [];

function agregarItem() {
    const insumoId = document.getElementById('itemInsumo').value;
    const insumoTexto = document.getElementById('itemInsumo').options[document.getElementById('itemInsumo').selectedIndex].text;
    const cantidad = document.getElementById('itemCantidad').value;
    const unidad = document.getElementById('itemUnidad').value;
    const precio = document.getElementById('itemPrecio').value;
   
    if (!insumoId || !cantidad || !precio) {
        alert("Por favor completa los datos del ítem");
        return;
    }
    const item = { id: parseInt(insumoId), nombre: insumoTexto, cantidad: parseFloat(cantidad), unidad: unidad, precio: parseFloat(precio), };
  
  
    itemsCompra.push(item);
    renderizarTabla();
    
    // Limpiar inputs del detalle
    document.getElementById('itemCantidad').value = '';
    document.getElementById('itemPrecio').value = '';
}
 let  total = 0;


function renderizarTabla() {
    const cuerpo = document.getElementById('cuerpoDetalle');
    let html = '';
     total = 0;

    itemsCompra.forEach((item, index) => {
        html += `
            <tr>
                <td class="text-uppercase">${item.nombre}</td>
                <td>${item.cantidad} <span class="badge bg-secondary">${item.unidad}</span></td>
                <td class="fw-bold">$ ${item.precio.toLocaleString()}</td>
                <td class="text-center">
                    <button onclick="eliminarItem(${index})" class="btn-remove-item">🗑️</button>
                </td>
            </tr>
        `;
total += parseFloat(item.precio);

    });

    cuerpo.innerHTML = html;
    document.getElementById('totalCompra').innerText = `$ ${total.toLocaleString()}`;
}

function eliminarItem(index) {
    itemsCompra.splice(index, 1);
    renderizarTabla();
}
// ... (tus variables itemsCompra y la función agregarItem se mantienen igual)

function guardarTodaLaCompra() {
    // Capturamos los datos del encabezado de tu modal
    const fecha = document.getElementById('compraFecha').value; 
    const comprador = document.getElementById('compraComprador').value;

    // Validaciones básicas
    if (!fecha || !comprador) {
        Swal.fire("Atención", "Completa los datos del encabezado (Fecha y Comprador)", "warning");
        return;
    }

    if (itemsCompra.length === 0) {
        Swal.fire("Atención", "Agrega al menos un insumo a la lista", "warning");
        return;
    }

    // Calculamos el total acumulado de los items
    const totalFinal = itemsCompra.reduce((acc, item) => acc + item.precio, 0);

    // Armamos el objeto exactamente como lo espera tu backend
    const compra = { 
        fecha: fecha, 
        comprador: comprador, 
        total: totalFinal, 
        items: itemsCompra 
    }; 

    console.log("Enviando compra:", compra); 
    
 
    enviarCompra(compra); 
}

function enviarCompra(compra) { 
    Swal.fire({
        title: 'Procesando compra...',
        text: 'Actualizando stock y registros',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(BASE_URL + "/index.php?route=stock/registrarCompra", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(compra)
    })
    .then(res => res.json())
    .then(data => {
        console.log(data);
        if (data.ok) {
            Swal.fire({
                title: "¡Éxito!",
                text: data.mensaje || "Compra registrada correctamente",
                icon: "success",
                confirmButtonColor: "#198754"
            }).then(() => {
               location.reload(); 
            });
        } else {
            Swal.fire({
                title: "Atención",
                text: data.mensaje || "No se pudo registrar la compra",
                icon: "warning",
                confirmButtonColor: "#ffc107"
            });
        }
    })
    .catch(err => {
        console.error("Error en Fetch:", err);
        Swal.fire({
            title: "Error Crítico",
            text: "Hubo un problema de conexión con el sistema.",
            icon: "error",
            confirmButtonColor: "#dc3545"
        });
    });
}