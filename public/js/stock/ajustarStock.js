function editarInsumo(id, nombre, stock,unidad) {

     if(unidad != 'un'){

    let totalGramos = stock;

    // División entera para obtener kilos
    let kilos = Math.floor(totalGramos / 1000);

    // El resto de la división para obtener los gramos sobrantes
    let gramos = totalGramos % 1000;

    document.getElementById('stock_gr').value = gramos;
    document.getElementById('stock_kg').value = kilos;

     }else{

        document.getElementById('stock_kg').value = stock;
     }


    document.getElementById('ajusteId').value = id;
    document.getElementById('ajusteTitulo').innerText = "⚙️ CONFIGURAR: " + nombre;
   
    // Seleccionamos el <select> por su id
//let selectUnidad = document.getElementById('unidad');

   // document.getElementById('ajusteMinimo').value = minimo;
//    if (unidad === 'gr') {
//     selectUnidad.value = 'gr';
// } else if (unidad === 'kg') {
//     selectUnidad.value = 'kg';
// } else if (unidad === 'un') {
//     selectUnidad.value = 'un';
// }

    document.getElementById('modalAjusteInsumo').style.display = 'flex';

}




function editarLimite(id, nombre,minimo,unidad_medida) {

if(unidad_medida != 'un'){

    let totalGramos = minimo;

// División entera para obtener kilos
let kilos = Math.floor(totalGramos / 1000);

// El resto de la división para obtener los gramos sobrantes
let gramos = totalGramos % 1000;

//document.getElementById('prueba').value = minimo;
 
     document.getElementById('ajusteId').value = id;
     document.getElementById('limite_kg').value = kilos;
     document.getElementById('limite_gr').value = gramos;



}
else{
  document.getElementById('ajusteId').value = id;
     document.getElementById('limite_kg').value = minimo;
      document.getElementById('limite_gr').value = '';

}

//let selectUnidad = document.getElementById('unidad_limite');

   // document.getElementById('ajusteMinimo').value = minimo;
//    if (unidad_medida === 'gr') {
//     selectUnidad.value = 'gr';
// } else if (unidad_medida === 'kg') {
//     selectUnidad.value = 'kg';
// } else if (unidad_medida === 'un') {
//     selectUnidad.value = 'un';
// }
 document.getElementById('ajusteTitulo2').innerText = "⚙️ CONFIGURAR LIMITE: " + nombre;
    document.getElementById('modalAjusteLimite').style.display = 'flex';
}

function cerrarAjuste() {
    document.getElementById('modalAjusteInsumo').style.display = 'none';
}
function cerrarAjusteLimite() {
    document.getElementById('modalAjusteLimite').style.display = 'none';
}

document.getElementById('formAjusteStock').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('ajusteId').value;
    let kg = parseFloat(document.getElementById("stock_kg").value) || 0;
    let gr = parseFloat(document.getElementById("stock_gr").value) || 0;
    const unidad = document.getElementById('unidad').value;


    let nuevoStock = 0;
   

    if(unidad != 'un')
    {
         nuevoStock = (kg * 1000) + gr;
        document.getElementById("ajusteStock").value = nuevoStock;
       
    }else{
        nuevoStock = kg;// <---- Acá le paso a la variable nuevoStock lo que tiene la variable kg que a su vez contiene lo que el usuario ingreso en el primer input,ya que seguramente es por ingreso de cantidad del insumo en unidades o litros.
 
    }

   

    const data = {
        id: id,
        stock: nuevoStock,
        //minimo: total_limite,
        uni: unidad
        //uni_limit: unidad_limite
    };

    fetch(BASE_URL + "/index.php?route=stock/actualizarLimitesYStock", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
        if(res.ok) {
            
            cerrarAjuste();
            
        location.reload();

          const contenedorMensajes = document.querySelector("#listado #mensajes");
            contenedorMensajes.innerHTML = `
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="border: 2px solid black; font-weight: bold;">
                    ⚓ ¡LIMITE Y STOCK ACTUALIZADO! ${res.mensaje}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;


        } else {
            alert("❌ Error: " + res.mensaje);
        }
    })
    .catch(error => console.error("Error:", error));
});

// *************************************************************

document.getElementById('modalAjusteLimite').addEventListener('submit', function(e) {
    e.preventDefault();


 const id = document.getElementById('ajusteId').value;
  const unidad_limite = document.getElementById('unidad_limite').value;
  let kg_limite = parseFloat(document.getElementById("limite_kg").value) || 0;
  let gr_limite = parseFloat(document.getElementById("limite_gr").value) || 0;

 let total_limite = 0;


   if(unidad_limite != 'un')
     {
             total_limite = (kg_limite * 1000) + gr_limite;
             document.getElementById("ajusteMinimo").value = total_limite;
     }else{
         total_limite = kg_limite;// <---- Acá le paso a la variable total_limite lo que tiene la variable kg_limite que a su vez contiene lo que el usuario ingreso en el primer input,ya que seguramente es por ingreso de cantidad del insumo en unidades o litros.
     }


const data = {
        id: id,
        minimo: total_limite,
        uni_limit: unidad_limite
    };

  fetch(BASE_URL + "/index.php?route=stock/actualizarLimites", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
        if(res.ok) {
            
            cerrarAjuste();
            
        location.reload();

          const contenedorMensajes = document.querySelector("#listado #mensajes");
            contenedorMensajes.innerHTML = `
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="border: 2px solid black; font-weight: bold;">
                    ⚓ ¡LIMITE Y STOCK ACTUALIZADO! ${res.mensaje}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;


        } else {
            alert("❌ Error: " + res.mensaje);
        }
    })
    .catch(error => console.error("Error:", error));
});





/**
 * Función para actualizar visualmente la fila de la tabla
 */
function actualizarFilaTabla(id, stock, minimo, unidad) {
    // Buscamos el botón que tiene el id del insumo para hallar la fila
    const botones = document.querySelectorAll(`button[onclick*="editarInsumo(${id},"]`);
    
    botones.forEach(btn => {
        const fila = btn.closest('tr');
        if (fila) {
            // 1. Actualizar el número del stock (segunda celda).
          const celdaStock = fila.cells[1].querySelector('span');

// aquí suponemos que tienes la variable `unidad` con el value del <select>
if (unidad === "kg") {
    if (stock < 1000) {
        // si es menor a 1000 lo mostramos como kilos
        celdaStock.innerText = stock + " kg";
    } else {
        // si es 1000 o más lo mostramos como gramos
        celdaStock.innerText = stock.toLocaleString('es-AR') + " gr";
    }
} else if (unidad === "gr") {
    celdaStock.innerText = stock.toLocaleString('es-AR') + " gr";
} else {
    // fallback por si agregas otras unidades
    celdaStock.innerText = stock + " " + unidad;
}
            // 2. Actualizar el badge de estado (tercera celda)
            const celdaEstado = fila.cells[2];
            if (stock <= 0) {
                celdaEstado.innerHTML = '<b style="color: #dc3545">🔴 AGOTADO</b>';
            } else if (stock <= minimo) {
                celdaEstado.innerHTML = '<b style="color: #ffc107">🟠 BAJO</b>';
            } else {
                celdaEstado.innerHTML = '<b style="color: var(--op-green)">🟢 OK</b>';
            }

            // 3. ¡IMPORTANTE! Actualizar el propio botón para que si lo vuelven a abrir,
            // tenga los datos nuevos y no los viejos.
            const nombreExtraido = document.getElementById('ajusteTitulo').innerText.replace("⚙️ CONFIGURAR: ", "");
            btn.setAttribute('onclick', `editarInsumo(${id}, '${nombreExtraido}', ${stock}, ${minimo})`);
        }
    });
}