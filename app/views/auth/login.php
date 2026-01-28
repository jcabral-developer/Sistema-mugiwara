<!-- /**
 * SISTEMA MUGIWARA - GESTIÓN GASTRONÓMICA
 * @author: juandev
 * @version: 1.0.0
 * @website: https://Juandev.com
 * @year: 2026
 */ -->


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mugiwara - Acceso Corporativo</title>
<link rel="stylesheet" href="<?= BASE_URL ?>/css/cssLogin.css">
<link rel="icon" href="<?= BASE_URL ?>/img/Gemini_Generated_Image_b3vr0wb3vr0wb3vr-removebg-preview.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Bangers&display=swap" rel="stylesheet">
</head>
<body>

<div class="split-screen">
    <div class="left-side">
        <div class="form-container">
            <header class="form-header">
                <img src="/Sistema_mugiwara/public/img/Gemini_Generated_Image_b3vr0wb3vr0wb3vr-removebg-preview.png" alt="Logo" class="main-logo">
                <h1 style="text-align: center;" >Iniciar sesión en el sistema</h1>
                <p >Ingresá tus credenciales.</p>
            </header>

            <form action="<?= BASE_URL ?>/index.php?route=login" class="modern-form" method="post">
                <div class="input-wrapper">
                    <label for="user">Nombre de usuario</label>
                    <input type="text" id="user" placeholder="" name="usuario" required>
                </div>

                <div class="input-wrapper">
                    <label for="pass">Contraseña</label>
                    <input type="password" id="pass"  name="contrasena" required>
                </div>

                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox"> Recordarme
                    </label>
                    <a href="#" class="forgot-link">¿Olvidaste tu clave?</a>
                </div>

                <button type="submit" class="btn-primary" name="iniciar" value="iniciar">INICIAR SESIÓN</button>
            </form>

            <footer class="form-footer">
                <p>&copy; 2026 Mugiwara System. Todos los derechos reservados.</p>
                <p style="text-align: center;">Desarrollado por <a href="https://tupagina.com" target="_blank" class="signature">juandev</a></p>
            </footer>
        </div>
    </div>

    <div class="right-side">
        <div class="overlay-text">
            <!-- <h2>"La comida no se le niega a nadie que tenga hambre"</h2> -->
            
        </div>
    </div>
</div>

</body>
</html>