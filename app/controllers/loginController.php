<?php

use function Laravel\Prompts\alert;

require_once BASE_PATH . '/app/models/User.php';
class LoginController
{

    public function mostrarLogin()
    {

        require_once __DIR__ . '/../views/auth/login.php';

    }

    public function login()
    {
  // 1️⃣ Validar datos
        if (empty($_POST['usuario']) || empty($_POST['contrasena'])) {
            $error = "Complete todos los campos";
            echo $error;
            require BASE_PATH . '/app/views/auth/login.php';
            return;
        }
      $usuario = trim($_POST['usuario']);
        
        
        //$usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_SPECIAL_CHARS);
        $password = $_POST['contrasena'];

        $userModel = new User();

        $user = $userModel->getUser($usuario);

   if ($user && password_verify($password, $user['CONTRASENIA'])) {


      $_SESSION['user_id']   = $user['ID'];
            $_SESSION['user_name'] = $user['USUARIO'];
            
            session_write_close();
         header('Location: /Sistema_mugiwara/public/index.php');
            exit;
        }

        $error = "Usuario o contraseña incorrectos ssss";
        echo $error;
        require BASE_PATH . '/app/views/auth/login.php';
    }

    public function logout()
    {
        session_destroy();
        header('Location: index.php');
        exit;
    }

}

