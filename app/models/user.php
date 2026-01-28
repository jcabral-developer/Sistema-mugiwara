<?php 


require_once __DIR__ . '/../config/database.php';

Class User{

    private $db;

    public function __construct(){

    $this->db = Database::connect();

    }
    public function getUser($usuario)
    {
        $sql = "SELECT * FROM usuarios WHERE USUARIO = :usuario LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->execute();

         $user = $stmt->fetch();
    if ($user) {
        $password = $user['CONTRASENIA'];

        // Verificamos si ya está hasheada (bcrypt o argon2)
        if (!preg_match('/^\$2y\$|\$argon2/', $password)) {
            // No está encriptada → la encriptamos
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Actualizamos en la BD
            $updateSql = "UPDATE usuarios SET CONTRASENIA = :hash WHERE USUARIO = :usuario";
            $updateStmt = $this->db->prepare($updateSql);
            $updateStmt->bindParam(':hash', $hash, PDO::PARAM_STR);
            $updateStmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            $updateStmt->execute();

            // Reemplazamos en el objeto retornado
            $user['CONTRASENIA'] = $hash;
        }
    }

    return $user;


        
    }


}