<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mugiwara - Recuperar Cuenta</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/cssLogin.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/cssRecuperar.css">
    <link rel="icon" href="<?= BASE_URL ?>/img/Gemini_Generated_Image_b3vr0wb3vr0wb3vr-removebg-preview.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Bangers&display=swap" rel="stylesheet">
</head>
<body>

<div class="split-screen">
    <div class="left-side">
        <div class="form-container">
            <header class="form-header">
                <img src="/Sistema_mugiwara/public/img/Gemini_Generated_Image_b3vr0wb3vr0wb3vr-removebg-preview.png" alt="Logo" class="main-logo">
                <h1>Recuperar acceso</h1>
                <p>Ingresá tu correo electrónico para restablecer tu usuario y contraseña.</p>
            </header>

            <form action="<?= BASE_URL ?>/index.php?route=reset_request" class="modern-form" method="post">
                <div class="input-wrapper">
                    <label for="email">Correo electrónico registrado</label>
                    <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" required>
                </div>

                <button type="submit" class="btn-primary" name="recuperar">ENVIAR INSTRUCCIONES</button>
                
                <div class="form-options" style="justify-content: center; margin-top: 20px;">
                    <a href="login.php" class="forgot-link">← Volver al inicio de sesión</a>
                </div>
            </form>

            <footer class="form-footer">
                <p>&copy; 2026 Mugiwara System. Todos los derechos reservados.</p>
                <p style="text-align: center;">Desarrollado por <a href="https://tupagina.com" target="_blank" class="signature">juandev</a></p>
            </footer>
        </div>
    </div>

    <div class="right-side reset-bg">
        <div class="overlay-text">
            <h2>"Incluso en los momentos más oscuros, siempre hay un camino de regreso."</h2>
        </div>
    </div>
</div>

</body>
</html>