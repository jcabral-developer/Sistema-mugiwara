function eliminarCompra(id){

if(!confirm("¿Seguro que quieres eliminar esta compra?")){
    return;
}

fetch(BASE_URL + "/index.php?route=stock/eliminarCompra", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id })
})
.then(res => res.json())
.then(data => {
   if(data.success){
    mostrarMensaje("success", "✅ Compra eliminada correctamente");
    document.getElementById("fila-" + id)?.remove();
} else {
    mostrarMensaje("danger", "⚠️ No se pudo eliminar: " + (data.error || ""));
}

})
.catch(err => console.error(err));
}



function mostrarMensaje(tipo, texto){
    const contenedor = document.getElementById("mensajes");
    contenedor.innerHTML = `
        <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
            ${texto}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
}