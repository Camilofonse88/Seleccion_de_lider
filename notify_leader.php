<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['leader_id'])) {
    $leader_id = $_GET['leader_id'];
    $_SESSION['leader'] = $leader_id; // Guardar el líder en la sesión
    echo "Líder $leader_id notificado.";
} else {
    echo "No se especificó un ID de líder.";
}
?>
