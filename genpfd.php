<?php
require_once('tcpdf/tcpdf.php');
$variableid_usuaio =$_post ['id_usuario'];
$variableid_materia = $_POST ['id_materia'];
// Conexión a la base de datos (ajusta los valores según tu configuración)
$servername = "localhost";
$username = "daniel";
$password = "javer";
$dbname = "calificaciones_control";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Realizar la consulta
$query = "SELECT alumnos.nombre, calificaciones.proyecto1, calificaciones.proyecto2, calificaciones.proyecto3, calificaciones.promedio, calificaciones.aspe_gen
FROM calificaciones
JOIN alumnos ON calificaciones.id_alumno = alumnos.id_alumno
WHERE calificaciones.id_materia = '$variableid_materia'";
$result = $conn->query($query);


//consulta para obtener el nombre de la materia 
$query_materia = "SELECT materia FROM materias WHERE materias.id_materia = '5'";
$resultadoquerymateria = $conn->query($query_materia);

// Verificar si la consulta tuvo éxito
if (!$result || !$resultadoquerymateria) {
    die("Error en la consulta: " . $conn->error);
}

// Obtener resultados de la consulta
$resultados = array();
while ($row = $result->fetch_assoc()) {
    $resultados[] = $row;
}

// Obtener el resultado de la consulta de materia
$row_materia = $resultadoquerymateria->fetch_assoc();
$materia = $row_materia['materia'];

//generar consulta para obtener grupo
$query_obtenergrupo = "SELECT grupo.grupo_name FROM grupo WHERE grupo.id_usuario = '1'";
$result_obtenergrupo = $conn->query($query_obtenergrupo);
$row_grupo = $result_obtenergrupo->fetch_assoc();
$grupo = $row_grupo['grupo_name'];

// Cerrar la conexión a la base de datos
$conn->close();

// Crear una instancia de TCPDF con orientación de página y unidades personalizadas
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Establecer el título del documento
$pdf->SetTitle('Reporte');

// Establecer márgenes (izquierda, arriba, derecha, abajo)
$pdf->SetMargins(10, 10, 10);

// Establecer el ancho de la página
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->AddPage();

// Establecer estilo de la tabla
$style = array(
    'border' => 1,
    'vpadding' => 'auto',
    'hpadding' => 'auto',
    'fontsize' => 10,
);

// Agregar datos a la tabla
$pdf->SetFont('times', 'B', 12);
$nombreMaestro = "Ignacia M";
$turno = "Matutino";
$pdf->Cell(0, 10, "Maestro: $nombreMaestro", 0, 1, 'L');
$pdf->Cell(0, 10, "Grupo: $grupo.       Turno: $turno.      Materia: $materia");
$pdf->Ln(10);
$pdf->SetFont('times', '', 12);

// Encabezados de la tabla
$encabezados = array("Nombre del Alumno", "Proyecto 1", "Proyecto 2", "Proyecto 3", "Aspectos Gen", "Promedio");

// Ancho de las celdas
$numColumns = count($encabezados);
$availableWidth = $pdf->GetPageWidth() - $pdf->GetMargins()['left'] - $pdf->GetMargins()['right'];
$maxHeaderWidth = $availableWidth / $numColumns;

// Ajustar el ancho de la celda "Nombre del Alumno"
$encabezados[0] = "Nombre Alumno"; // Cambiar el encabezado para evitar problemas con el ancho automático
$adjustedWidth = 60; // Puedes ajustar esto según tus necesidades

// Calcular el ancho automático para las celdas restantes
$autoWidth = ($availableWidth - $adjustedWidth) / (count($encabezados) - 1);

// Agregar encabezados de la tabla con celdas de ancho automático
foreach ($encabezados as $key => $encabezado) {
    $width = ($key === 0) ? $adjustedWidth : $autoWidth;

    if ($encabezado === "Promedio") {
        $pdf->SetFillColor(255, 0, 0); // Rojo
        $pdf->SetTextColor(255, 255, 255); // Texto blanco
    } else {
        $pdf->SetFillColor(255, 255, 255); // Fondo blanco para otros encabezados
        $pdf->SetTextColor(0, 0, 0); // Texto negro para otros encabezados
    }

    $pdf->Cell($width, 10, substr($encabezado, 0, 30), 1, 0, 'C', true);

    // Restaurar colores originales
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
}

$pdf->Ln();

// Agregar datos a la tabla con celdas de ancho automático
foreach ($resultados as $row) {
    $pdf->Cell($adjustedWidth, 10, $row['nombre'], 1);
    $pdf->Cell($autoWidth, 10, $row['proyecto1'], 1);
    $pdf->Cell($autoWidth, 10, $row['proyecto2'], 1);
    $pdf->Cell($autoWidth, 10, $row['proyecto3'], 1);
    $pdf->Cell($autoWidth, 10, substr($row['aspe_gen'], 0, 15), 1);
    $pdf->Cell($autoWidth, 10, $row['promedio'], 1);
    $pdf->Ln();
}

// Guardar el contenido del PDF como una cadena
$pdfContent = $pdf->Output('reporte.pdf', 'S');

// Escribir el contenido en un archivo en el servidor
file_put_contents('reporte.pdf', $pdfContent);

// Ahora redirige al usuario al archivo PDF descargado
header("Location: reporte.pdf");
exit;
?>
