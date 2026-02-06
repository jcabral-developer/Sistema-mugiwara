let itemsCompra = [];

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

    const item = {
        id: insumoId,
        nombre: insumoTexto,
        cantidad: cantidad,
        unidad: unidad,
        precio: parseFloat(precio)
    };

    itemsCompra.push(item);
    renderizarTabla();
    
    // Limpiar inputs del detalle
    document.getElementById('itemCantidad').value = '';
    document.getElementById('itemPrecio').value = '';
}

function renderizarTabla() {
    const cuerpo = document.getElementById('cuerpoDetalle');
    let html = '';
    let total = 0;

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