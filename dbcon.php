<?php
// Datos de conexión a la base de datos
$host = "localhost"; // Cambia esto con la dirección de tu servidor de base de datos
$usuario = "daniel"; // Cambia esto con tu nombre de usuario de la base de datos
$contrasena = "javer"; // Cambia esto con tu contraseña de la base de datos
$base_de_datos = "calificaciones_control";

// Conexión a la base de datos
$conexion = new mysqli($host, $usuario, $contrasena, $base_de_datos);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Inicializar mensajes
$mensaje = "";
$error = "";

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el nombre del formulario o de donde provenga
    $nombre = $_POST['name']; // Asegúrate de cambiar 'nombre' por el nombre correcto del campo en tu formulario

    // Consulta SQL de inserción
    $sql = "INSERT INTO alumnos (nombre) VALUES ('$nombre')"; // Asegúrate de cambiar 'tabla_nombre' y 'nombre_columna'

    // Ejecutar la consulta
    if ($conexion->query($sql) === TRUE) {
        $_SESSION['message register'] = "Alumno guardado correctamente.";     
    } else {
        $_SESSION['message register'] = "Error al guardar el alumno: " . $conexion->error;
    }
    header("Location: addalumnos.php");
            exit();
}

// Cerrar la conexión a la base de datos
$conexion->close();
?>

