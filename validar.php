<?php
ob_start();
session_start();

$usuario = $_POST['usuario'];
$contraseña = $_POST['contraseña'];

$servername = "localhost";
$username = "daniel";
$password = "javer";
$dbname = "calificaciones_control";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

$usuario = mysqli_real_escape_string($conn, $usuario);
$contraseña = mysqli_real_escape_string($conn, $contraseña);

$tuConsultaSQL = "SELECT usuario, AES_DECRYPT(password_encrypted, 'thehive') as decrypted_password FROM usuarios WHERE usuario = '$usuario'";
$result = mysqli_query($conn, $tuConsultaSQL);

if ($result) {
    $num_rows = mysqli_num_rows($result);

    if ($num_rows > 0) {
        $row = mysqli_fetch_assoc($result);

        // Verificar la contraseña usando AES_DECRYPT
        if ($contraseña === $row['decrypted_password']) {
            // Iniciar sesión solo si el usuario y la contraseña son correctos
            $_SESSION['usuario'] = $usuario;

            // Redirigir a la página después del inicio de sesión
            header("Location: index.php");
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Nombre de usuario no encontrado.";
    }

    mysqli_free_result($result);
} else {
    echo "Error en la consulta: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
