function filtrarInsumos() {
    const input = document.getElementById("inputBusqueda");
    const filtro = input.value.toLowerCase();

    const filas = document.querySelectorAll(".tabla-pro tbody tr");

    filas.forEach(fila => {
        const textoFila = fila.innerText.toLowerCase();
        fila.style.display = textoFila.includes(filtro) ? "" : "none";
    });
}
