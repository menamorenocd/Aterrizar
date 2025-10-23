<?php
require_once __DIR__ . '/Conexion.php';

class UsuarioModelo {
    private $conexion;

    public function __construct() {
        // Se establece la conexión a la base de datos
        $this->conexion = (new Conexion())->conectar();
    }

    /**
     * Registrar un nuevo usuario
     */
    public function registrar($nombre, $apellido1, $apellido2, $genero, $tipo_documeto, $numero_documento, $correo, $usuario, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO tb_usuario (
                    nombre_usuario, apellido1, apellido2, genero, tipo_documeto, 
                    numero_documento, correo_usuario, usuario_login, password_hash, rol
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Usuario')";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sssssssss", 
            $nombre, $apellido1, $apellido2, $genero, $tipo_documeto, 
            $numero_documento, $correo, $usuario, $hash
        );

        return $stmt->execute();
    }

    /**
     * Iniciar sesión
     */
    public function login($usuario, $password) {
        $sql = "SELECT * FROM tb_usuario WHERE usuario_login = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password_hash'])) {
            $this->actualizarUltimoLogin($user['id_usuario']);
            return $user;
        }

        return false;
    }

    /**
     * Actualiza la fecha del último inicio de sesión del usuario.
     */
    private function actualizarUltimoLogin($idUsuario) {
        $sql = "UPDATE tb_usuario SET fecha_ultimo_login = NOW() WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
    }

    /**
     * Obtener información de un usuario por ID (para ver su perfil)
     */
    public function obtenerUsuarioPorId($idUsuario) {
        $sql = "SELECT id_usuario, nombre_usuario, correo_usuario, usuario_login 
                FROM tb_usuario WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Actualizar perfil del usuario (nombre, correo, contraseña opcional)
     */
    public function actualizarPerfil($idUsuario, $nombre, $correo, $password = null) {
        if ($password) {
            // Si el usuario cambió su contraseña
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE tb_usuario SET nombre_usuario=?, correo_usuario=?, password_hash=? WHERE id_usuario=?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("sssi", $nombre, $correo, $hash, $idUsuario);
        } else {
            // Si solo cambió nombre o correo
            $sql = "UPDATE tb_usuario SET nombre_usuario=?, correo_usuario=? WHERE id_usuario=?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("ssi", $nombre, $correo, $idUsuario);
        }

        return $stmt->execute();
    }
}
?>
