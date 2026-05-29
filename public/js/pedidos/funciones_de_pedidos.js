pedido = [];
let total = 0;

// Variables de control de estado del Modal
let platoEspecialTemporal = null;
let modoModalActual = 'entera'; // Controla si es extra entera o mitad/mitad

// Setea el comportamiento según la pestaña clickeada
function setModoModal(modo) {
  modoModalActual = modo;
  // Cambiamos colores estéticos a los títulos de las pestañas
  if (modo === 'entera') {
    document.getElementById('tab-extras-tab').classList.replace('text-white', 'text-warning');
    document.getElementById('tab-mitad-tab').classList.replace('text-warning', 'text-white');
  } else {
    document.getElementById('tab-mitad-tab').classList.replace('text-white', 'text-warning');
    document.getElementById('tab-extras-tab').classList.replace('text-warning', 'text-white');
  }
}

// TU FUNCIÓN PRINCIPAL ORIGINAL REFORMADA PARA MANEJAR LA INTERCEPCIÓN
function agregarPlato(id, nombre, precio) {
  const esEspecial = nombre.toLowerCase().includes("especial");
  if (esEspecial) {
    // Guardamos el plato actual en memoria temporal
    platoEspecialTemporal = { id, nombre, precio };
    // Reseteamos checkboxes y selects
    document.querySelectorAll('.check-especial').forEach(chk => chk.checked = false);
    document.getElementById('selectSaborIzq').value = "";
    document.getElementById('selectSaborDer').value = "";
    // Forzamos abrir por defecto en la pestaña de extras
    const triggerEl = document.querySelector('#tab-extras-tab');
    bootstrap.Tab.getInstance(triggerEl)?.show() || new bootstrap.Tab(triggerEl).show();
    setModoModal('entera');
    // Mostramos el Modal único
    const modalEl = new bootstrap.Modal(document.getElementById('modalIngredientesEspeciales'));
    modalEl.show();
  } else {
    // Flujo normal directo si no es especial
    ejecutarInsercionCarrito(id, nombre, precio, []);
  }
}

function confirmarSeleccionModal() {
  const modalElement = document.getElementById('modalIngredientesEspeciales');
  const modalInstance = bootstrap.Modal.getInstance(modalElement);
  modalInstance.hide();

  if (modoModalActual === 'entera') {
    // --- PROCESAR PESTAÑA 1: AGREGAR EXTRAS ENTEROS ---
    if (!platoEspecialTemporal) return;
    let extrasElegidos = [];
    document.querySelectorAll('.check-especial:checked').forEach(chk => {
      extrasElegidos.push({
        id: chk.value,
        nombre: chk.getAttribute('data-nombre'),
        cantidad: parseFloat(chk.getAttribute('data-cantidad')) || 0,
        unidad: chk.getAttribute('data-unidad'),
        precio: parseFloat(chk.getAttribute('data-precio')) || 0     
      });
    });
    ejecutarInsercionCarrito(
      platoEspecialTemporal.id,
      platoEspecialTemporal.nombre,
      platoEspecialTemporal.precio,
      extrasElegidos
    );
    platoEspecialTemporal = null; 
  } else {
    // --- PROCESAR PESTAÑA 2: PIZZA MITAD Y MITAD ---
    if (!platoEspecialTemporal) return; // <--- Aseguramos que tenemos la pizza base (Muzzarella)

    const cmbIzq = document.getElementById('selectSaborIzq');
    const cmbDer = document.getElementById('selectSaborDer');
    const optIzq = cmbIzq.options[cmbIzq.selectedIndex];
    const optDer = cmbDer.options[cmbDer.selectedIndex];

    if (cmbIzq.value === "" && cmbDer.value === "") {
      Swal.fire('¡Faltan sabores!', 'Por favor selecciona ambas mitades.', 'warning');
      return;
    }

    const nombreIzq = optIzq.text.trim(); // Levantamos el texto visible del select limpio
    const nombreDer = optDer.text.trim();

    // Mantenemos el precio base del plato
    const precioPizzaBase = platoEspecialTemporal.precio;
    const nombreCompuesto = `🍕 MITAD ${nombreIzq} / MITAD ${nombreDer}`;

    const extrasMitadMitad = [
      {
        id: cmbIzq.value,
        nombre: optIzq.getAttribute('data-nombre'),
        precio: parseFloat(optIzq.getAttribute('data-precio')) || 0,
        cantidad: parseFloat(optIzq.getAttribute('data-cantidad')) || 0, 
        unidad: optIzq.getAttribute('data-unidad'),
        lado: 'izquierda'
      },
      {
        id: cmbDer.value,
        nombre: optDer.getAttribute('data-nombre'),
        precio: parseFloat(optDer.getAttribute('data-precio')) || 0,
        cantidad: parseFloat(optDer.getAttribute('data-cantidad')) || 0, 
        unidad: optDer.getAttribute('data-unidad'),
        lado: 'derecha'
      }
    ];

    // Mandamos el ID real de la pizza de Muzzarella (platoEspecialTemporal.id)
    ejecutarInsercionCarrito(
      platoEspecialTemporal.id,
      nombreCompuesto,
      precioPizzaBase,
      extrasMitadMitad
    );
    platoEspecialTemporal = null;
  }
}
// INSERCIÓN REAL DENTRO DEL ARRAY CENTRAL (Mantiene tu buscador index)
function ejecutarInsercionCarrito(id, nombre, precio, extras) {
  const index = pedido.findIndex(item => {
    // Si es el mismo ID y tiene la misma cantidad exacta de extras de ingredientes
    if (item.id !== id) return false;
    if (item.extras.length !== extras.length) return false;
    return item.extras.every((ext, idx) => ext.id === extras[idx].id);
  });

  if (index > -1) {
    pedido[index].cant++;
  } else {
    const esPromo = id.toString().includes("promo_");
    pedido.push({
      id,
      nombre,
      precio,
      cant: 1,
      esPromo,
      extras: extras // Guardado como array adentro del ítem original
    });
  }
  renderizarPedido();
}

// TU RENDERIZADOR NATIVO CON TUS MISMOS ESTILOS
function renderizarPedido() {
  const lista = document.getElementById('lista-pedido');
  lista.innerHTML = '';
  total = 0;

  pedido.forEach((item, i) => {
    const subtotal = item.precio * item.cant;
    total += subtotal;
    // Verificamos si lleva extras para armarle la leyenda abajo
    const tieneExtras = item.extras && item.extras.length > 0;
    lista.innerHTML += `<tr> <td> <strong>${item.nombre}</strong> ${tieneExtras ? `<br><small class="text-muted" style="font-size:0.75rem;">+ Extras: ${item.extras.map(e => e.nombre).join(', ')}</small>` : ''} </td> <td> <span class="badge-cantidad">${item.cant}</span> </td> <td>$${subtotal}</td> <td class="text-end"> <button class="btn btn-sm btn-danger py-0" onclick="eliminarItem(${i})">x</button> </td> </tr>`;
  });

  document.getElementById('total-monto').innerText = `$${total}`;
}

// MANIPULADORES NATIVOS DE INTERFAZ DEL CHECKBOX
function toggleCheck(elemento) {
  const checkbox = elemento.querySelector('.check-especial');
  checkbox.checked = !checkbox.checked;
}

function eliminarItem(i) {
  pedido.splice(i, 1);
  renderizarPedido();
}

function cancelarPedido() {
  Swal.fire({
    title: '¿Borrar el pedido actual?',
    text: "Esta acción no se puede deshacer",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc3545',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'SÍ, BORRAR',
    cancelButtonText: 'NO, VOLVER'
  }).then((result) => {
    if (result.isConfirmed) {
      pedido = [];
      renderizarPedido();
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: 'Pedido borrado',
        showConfirmButton: false,
        timer: 1500
      });
    }
  });
}

function abrirCobro() {
  // Si el total es 0, mostramos una alerta de error/info
  if (total === 0) {
    Swal.fire({
      title: '¡El pedido está vacío!',
      text: 'Agregá al menos un producto para cobrar',
      icon: 'info',
      confirmButtonColor: '#198754'
    });
    return;
  }
  // Si hay productos, mostramos el modal de pago
  document.getElementById('total-modal').innerText = `$${total}`;
  const modalPago = new bootstrap.Modal(document.getElementById('modalPago'));
  modalPago.show();
}

function finalizarVenta(metodo) {
  alert(`Venta registrada ($${total}) por ${metodo}. Stock actualizado.`);
  pedido = [];
  renderizarPedido();
  bootstrap.Modal.getInstance(document.getElementById('modalPago')).hide();
}

async function confirmarVentaFinal(btnConfirmar) {

  if (pedido.length === 0) {
    Swal.fire("¡El carrito está vacío!", "", "warning");
    return;
  }
  if (!metodoSeleccionado) {
    Swal.fire("Por favor, selecciona un método de pago.", "", "info");
    return;
  }

  // --- CORRECCIÓN DE CÁLCULO ---
  // Calculamos el subtotal recorriendo el array pedido para evitar el error del $0
  const subtotalCalculado = pedido.reduce((acc, item) => acc + (item.precio * item.cant), 0);
  const delivery = parseFloat(document.getElementById('costoDelivery').value) || 0;
  const totalFinal = subtotalCalculado + delivery;
  // -----------------------------

  const data = {
    cliente: {
      nombre: document.getElementById('clienteNombre').value.trim() || "------",
      telefono: document.getElementById('clienteTel').value.trim(),
      direccion: document.getElementById('clienteDir').value.trim(),
      observaciones: document.getElementById('clienteObs').value.trim()
    },
    metodo_pago: metodoSeleccionado,
    subtotal: subtotalCalculado, // Usamos el valor real calculado
    delivery: delivery,
    total: totalFinal,
    items: pedido
  };

    // Buscá la variable que contiene todo el paquete de datos (cliente, items, total, etc.)
console.log("=== OBJETO ENVIADO AL BACKEND ===");
console.log(JSON.stringify(data, null, 2)); // El 'null, 2' lo formatea lindo con espacios
  

  // console.log('llege aqui');
  btnConfirmar.disabled = true;
  btnConfirmar.innerText = "⏳ PROCESANDO...";

  try {
    const res = await fetch("index.php?route=pedidos/guardarVenta", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify(data)
    });

    const text = await res.text();
    console.log("RESPUESTA CRUDA:", text);
    let response;
    try {
      response = JSON.parse(text);
    } catch (e) {
      console.error("Error parseando JSON", e);
      Swal.fire("Error", "Respuesta inválida del servidor", "error");
      return;
    }

    if (response.status === "ok") {
      console.log('llege aqui 2');
      Swal.fire({
        title: '¡Venta Realizada!',
        text: "¿Deseas compartir el comprobante por WhatsApp?",
        icon: 'success',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '✅ SÍ, COMPARTIR',
        cancelButtonText: '❌ SALIR',
        allowOutsideClick: false
      }).then(async (result) => {
        if (result.isConfirmed) {
          Swal.fire({
            title: 'Generando comprobante...',
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading()
            }
          });

          // ASIGNACIÓN AL TICKET CON FORMATO ARGENTINO
          document.getElementById('t-fecha').innerText = new Date().toLocaleString();
          document.getElementById('t-cliente').innerText = data.cliente.nombre;
          // Usamos los valores calculados arriba para el ticket
          document.getElementById('t-subtotal').innerText = subtotalCalculado.toLocaleString('es-AR');
          document.getElementById('t-delivery').innerText = delivery.toLocaleString('es-AR');
          document.getElementById('t-total').innerText = totalFinal.toLocaleString('es-AR');
          document.getElementById('t-pago').innerText = metodoSeleccionado;

          // ITEMS
          let itemsHtml = "";
          pedido.forEach(item => {
            itemsHtml += `<div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:14px;"> <span><b>${item.cant}x</b> ${item.nombre}</span> <span>$${(item.precio * item.cant).toLocaleString('es-AR')}</span> </div>`;
          });
          document.getElementById('t-items').innerHTML = itemsHtml;

          // GENERAR IMAGEN
          const element = document.getElementById('ticket-para-imagen');
          const canvas = await html2canvas(element, {
            scale: 2,
            backgroundColor: "#ffffff",
            useCORS: true
          });

          canvas.toBlob(async (blob) => {
            const item = new ClipboardItem({ "image/png": blob });
            await navigator.clipboard.write([item]);
            const telefono = (data.cliente.telefono || "").replace(/\D/g, '');
            const telFinal = (telefono.length === 10) ? '549' + telefono : telefono;
            const mensaje = encodeURIComponent("¡Gracias por tu compra en Mugiwara! ");
            window.open(`https://wa.me/${telFinal}?text=${mensaje}`, '_blank');
            Swal.fire("¡Listo!", "El comprobante fue copiado. Pégalo en WhatsApp.", "success")
              .then(() => {
                // 1. CERRAR EL MODAL MANUALMENTE
                const modalPagoElement = document.getElementById('modalPago');
                const modalPagoBS = bootstrap.Modal.getInstance(modalPagoElement);
                if (modalPagoBS) modalPagoBS.hide();
                // Limpiar campos de cliente
                document.getElementById('clienteNombre').value = '';
                document.getElementById('clienteTel').value = '';
                document.getElementById('clienteDir').value = '';
                document.getElementById('clienteObs').value = '';
                finalizarVentaLimpiar();
              });
          }, 'image/png');
        } else {
          // --- CORRECCIÓN: CERRAMOS EL MODAL AL DARLE A "SALIR" ---
          const modalPagoElement = document.getElementById('modalPago');
          const modalPagoBS = bootstrap.Modal.getInstance(modalPagoElement);
          if (modalPagoBS) {
            modalPagoBS.hide(); // Cierra el modal de cobro
          }
          // Limpiamos los campos del cliente para que no queden datos viejos en la próxima venta
          document.getElementById('clienteNombre').value = '';
          document.getElementById('clienteTel').value = '';
          document.getElementById('clienteDir').value = '';
          document.getElementById('clienteObs').value = '';
          // Vaciamos el carrito y reseteamos totales
          finalizarVentaLimpiar();
          // Un aviso rápido de que todo salió bien
          Swal.fire({
            icon: 'success',
            title: 'Venta registrada',
            showConfirmButton: false,
            timer: 1500
          });
          location.reload();
        }
      });
    } else {
      console.log('llege aqui error');
      Swal.fire("Error", response.message, "error");
    }
  } catch (err) {
    console.error(err);
    Swal.fire("Error", "Error de conexión", "error");
  } finally {
    btnConfirmar.disabled = false;
    btnConfirmar.innerText = "CONFIRMAR VENTA";
  }
}