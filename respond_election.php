<?php
session_start();

$node_id = $_GET['node_id']; // Nodo que inició la elección

if (isset($_SESSION['leader'])) {
    // Si ya hay líder, este nodo no participa en la elección
    echo "ok"; // Confirma que este nodo ha recibido la elección
} else {
    // Si no hay líder, este nodo debe contestar afirmativamente
    echo "ok";
}
?>
