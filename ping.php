<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['node_id'])) {
    $node_id = $_GET['node_id'];
    
    // Guardar o actualizar el tiempo de última señal de vida (heartbeat)
    if (!isset($_SESSION['last_heartbeat'])) {
        $_SESSION['last_heartbeat'] = [];
    }
    
    $_SESSION['last_heartbeat'][$node_id] = time();
    
    echo "Heartbeat recibido de nodo $node_id.";
} else {
    echo "No se especificó un ID de nodo.";
}
?>
