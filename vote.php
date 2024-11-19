<?php
session_start();

// Lista de candidatos
$candidatos = [
    '1' => 'Camilo Fonseca',
    '2' => 'Andres Bernal',
    '3' => 'Mauricio Narvaez',
    '4' => 'Voto en blanco'
];

// Inicializa el array de votos si no existe
if (!isset($_SESSION['votes'])) {
    $_SESSION['votes'] = [];
}

// Procesar el voto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['candidate'])) {
    $vote = $_POST['candidate'];
    if (isset($candidatos[$vote])) {
        $_SESSION['votes'][] = $vote;
    }
}

// Redirigir de vuelta al formulario de elección en index.php
header("Location: index.php");
exit;
?>
