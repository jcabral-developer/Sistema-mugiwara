
    const modal = document.getElementById("miModal");
    const btnAbrir = document.getElementById("btnAbrir");
    const btnCerrar = document.getElementById("btnCerrar");

    btnAbrir.onclick = () => modal.style.display = "flex";
    btnCerrar.onclick = () => modal.style.display = "none";
    window.onclick = (e) => { if (e.target == modal) modal.style.display = "none"; }

    function filtrarInsumos() {
        let input = document.getElementById("inputBusqueda").value.toUpperCase();
        let filas = document.getElementById("tablaCuerpo").getElementsByTagName("tr");
        for (let i = 0; i < filas.length; i++) {
            let texto = filas[i].getElementsByTagName("td")[0].textContent.toUpperCase();
            filas[i].style.display = texto.includes(input) ? "" : "none";
        }
    }

    function limpiarBusqueda() {
        document.getElementById("inputBusqueda").value = "";
        filtrarInsumos();
    }