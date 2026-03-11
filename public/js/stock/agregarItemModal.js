
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

function guardarTodaLaCompra() {

   const fecha = document.getElementById('compraFecha').value; 
   const comprador = document.getElementById('compraComprador').value;

    if (!fecha || !comprador) {
        alert("Completa los datos del encabezado");
        return;
    }


    if (itemsCompra.length === 0) {
        alert("Agrega al menos un insumo");
        return;
    }

    if (!isNaN(total)) {
    console.log("El total es:", total);
    } else {
    console.error("Valor inválido:");
    }


const compra = { fecha: fecha, 
    comprador: comprador, 
    total: parseFloat(total) || 0, // Asegura que sea un número 
    items: itemsCompra 
}; 
    console.log(compra); 
    enviarCompra(compra); 
    
    }

function enviarCompra(compra) { 



    fetch(BASE_URL + "/index.php?route=stock/registrarCompra", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(compra)
    })
    .then(res => res.json())
    .then(data => {
        alert(data.mensaje);

        if (data.ok) {
            location.reload();
        }
    })
    .catch(err => {
        console.error(err);
        alert("Error al registrar la compra");
    });
}

