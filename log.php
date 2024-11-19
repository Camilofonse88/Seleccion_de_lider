<?php
session_start();

// Ruta al archivo de log
$logFile = __DIR__ . '/logs/vote.log';

function registrarEventoLog($node_id, $tipo) {
    global $logFile;

    if ($tipo == 'fallo') {
        $logMessage = date('Y-m-d H:i:s') . " - Nodo " . $node_id . " ha fallado.\n";
    } else {
        $logMessage = date('Y-m-d H:i:s') . " - Evento exitoso registrado para el nodo " . $node_id . ".\n";
    }

    // Escribir el mensaje en el archivo de log
    file_put_contents($logFile, $logMessage, FILE_APPEND);

    return $tipo == 'fallo' ? "Fallo registrado para el nodo " . $node_id : "Evento exitoso registrado para el nodo " . $node_id;
}

function reeleccionLider() {
    // Supongamos que el sistema tiene una variable que guarda los nodos activos
    // Si el líder ha fallado, se busca el nodo con el ID más alto
    $nodos_activos = [1, 2, 3]; // Por ejemplo, los nodos activos
    sort($nodos_activos, SORT_DESC);
    $nuevo_lider = $nodos_activos[0]; // El nodo con el ID más alto es el nuevo líder
    echo "Nuevo líder elegido: Nodo " . $nuevo_lider;
}

// Verifica si se proporcionan el ID del nodo y el tipo de evento
if (isset($_GET['node_id']) && isset($_GET['tipo'])) {
    $node_id = $_GET['node_id'];
    $tipo = $_GET['tipo'];

    // Si el tipo es "fallo", ejecuta la reelección
    if ($tipo == 'fallo') {
        reeleccionLider();
    }

    // Registrar evento y mostrar mensaje
    $mensaje = registrarEventoLog($node_id, $tipo);
    echo $mensaje;
} else {
    echo "No se especificó un ID de nodo o tipo de evento válido.";
}
?>
