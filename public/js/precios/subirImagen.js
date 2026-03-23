   function subirImagen(input, idPlato) {
            if (input.files && input.files[0]) {
                const formData = new FormData();
                formData.append('imagen', input.files[0]);
                formData.append('id', idPlato);

                // Spinner o feedback visual de "Cargando..."
                Swal.showLoading();

                fetch('index.php?route=precios/guardarImagen', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        // Dentro de tu función subirImagen en el .then:
                        if (data.success) {
                            // Fíjate que el nombre de la carpeta sea el mismo que en el controlador
                            const nuevaRuta = "/Sistema_mugiwara/public/img/imagenes_de_comidas/" + data.nuevoNombre;
                            document.getElementById('img-' + idPlato).src = nuevaRuta + "?t=" + new Date().getTime();
                            // ... resto del swal

                            Swal.fire({
                                icon: 'success',
                                title: '¡Imagen actualizada!',
                                toast: true,
                                position: 'top-end',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', 'No se pudo subir la imagen', 'error');
                    });
            }
        }