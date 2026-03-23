

        document.querySelectorAll(".btn-config").forEach(btn => {
            btn.addEventListener("click", function () {
                const platoId = this.dataset.id;

                
                const fila = this.closest("tr"); // Obtenemos la fila donde se hizo click
                const nombrePlato = fila.querySelector("td:first-child").innerText; // Primera columna
                const margenActual = fila.querySelector(".text-margen").innerText.replace("%", "").trim(); // Columna margen

                // Asignamos la descripción y el margen al modal antes de que carguen los ingredientes
                document.getElementById("descripcion").innerText = nombrePlato.toUpperCase();
                document.getElementById("inputMargen").value = margenActual;
                // ---------------------------------------------------------

                fetch("index.php?route=precios/obtenerIngredientes&plato=" + platoId)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById("modalEditor").dataset.plato = platoId;
                        const tbody = document.getElementById("tablaIngredientes");
                        const celdaTotal = document.getElementById("costo_total");

                        tbody.innerHTML = "";
                        let total = 0;

                        data.forEach(i => {
                            total += Number(i.parcial);
                            tbody.innerHTML += `
                        <tr>
                            <td>${i.descripcion.charAt(0).toUpperCase() + i.descripcion.slice(1)}</td>
                            <td>${Math.round(i.cantidad)} g</td>
                            <td>$${i.parcial}</td>
                        </tr>
                    `;
                        });

                        celdaTotal.innerText = "$" + Math.round(total);
                        celdaTotal.dataset.total = total;

                        // Ejecutamos el cálculo para que el precio sugerido se actualice con el margen que acabamos de heredar
                        calcularPrecio();
                    });
            });
        });

        document.getElementById("btnGuardarPrecio").addEventListener("click", function () {
            const platoId = document.getElementById("modalEditor").dataset.plato;
            const costo = Number(document.getElementById("costo_total").dataset.total);
            const margen = Number(document.getElementById("inputMargen").value);
            const precio = Number(document.getElementById("precioSugerido").innerText.replace("$", ""));
            const ganancia = Number(document.getElementById("gananciaVenta").innerText.replace("$", ""));

            fetch("index.php?route=precios/guardarPrecio", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    plato: platoId,
                    costo: costo,
                    margen: margen,
                    precio: precio,
                    ganancias: ganancia
                })
            })
                .then(res => res.text())
                .then(data => {
                    try {
                        const json = JSON.parse(data);
                        if (json.status === "ok") {
                            // 1. Cerramos el modal de Bootstrap primero
                            const modalElement = document.getElementById('modalEditor');
                            const modalInstance = bootstrap.Modal.getInstance(modalElement);
                            if (modalInstance) modalInstance.hide();

                            // 2. Disparamos el Alert
                            Swal.fire({
                                icon: "success",
                                title: "Precio actualizado",
                                text: "Los datos se guardaron correctamente.",
                                confirmButtonText: "Aceptar",
                                allowOutsideClick: false,
                                allowEscapeKey: true,
                                // Este evento se dispara cuando el Alert se termina de renderizar
                                didOpen: () => {
                                    const confirmBtn = Swal.getConfirmButton();
                                    if (confirmBtn) confirmBtn.focus(); // Forzamos el foco para que el Enter funcione sí o sí
                                }
                            }).then((result) => {
                                // Al presionar Enter o Clic, se refresca
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "No se pudo guardar",
                                text: json.message || ""
                            });
                        }
                    } catch (e) {
                        console.error("Respuesta no es JSON:", data);
                        Swal.fire({
                            icon: "error",
                            title: "Error del servidor",
                            text: "La respuesta no es válida."
                        });
                    }
                });
        });


        // buscador
        document.getElementById("buscadorPlatos").addEventListener("input", function () {
    const busqueda = this.value.toLowerCase(); // Lo que escribe el usuario
    const filas = document.querySelectorAll(".table-pirata tbody tr"); // Todas las filas de la tabla

    filas.forEach(fila => {
        // Obtenemos el texto de la primera celda (Nombre del Plato)
        const nombrePlato = fila.querySelector("td:first-child").textContent.toLowerCase();

        // Si el nombre incluye lo que buscamos, mostramos la fila, si no, la ocultamos
        if (nombrePlato.includes(busqueda)) {
            fila.style.display = ""; // Muestra la fila (valor por defecto)
        } else {
            fila.style.display = "none"; // Oculta la fila
        }
    });
});
    

 function calcularPrecio() {

            const margen = Number(document.getElementById('inputMargen').value);

            const costoFijo = Number(
                document.getElementById('costo_total').innerText.replace("$", "")
            );

            const sugeridoElement = document.getElementById('precioSugerido');
            const gananciaElement = document.getElementById('gananciaVenta');
            const alerta = document.getElementById('alertaMargen');

            const precioFinal = costoFijo + (costoFijo * (margen / 100));
            const ganancia = precioFinal - costoFijo;

            sugeridoElement.innerText = `$${precioFinal}`;
            gananciaElement.innerText = `$${Math.round(ganancia)}`;

            if (margen < 30) {
                alerta.style.display = "block";
            } else {
                alerta.style.display = "none";
            }

        }