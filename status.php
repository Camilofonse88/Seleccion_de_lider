<?php
session_start();

$nodes = [
    ["id" => 1, "ip" => "192.168.0.2", "last_heartbeat" => time(), "active" => true],
    ["id" => 2, "ip" => "192.168.0.3", "last_heartbeat" => time(), "active" => true],
    ["id" => 3, "ip" => "192.168.0.4", "last_heartbeat" => time(), "active" => true]
];

// Actualiza el timestamp del último heartbeat recibido
function actualizarHeartbeat($node_id) {
    global $nodes;
    foreach ($nodes as &$node) {
        if ($node["id"] === $node_id) {
            $node["last_heartbeat"] = time();
            $node["active"] = true;
        }
    }
}

// Revisa si algún nodo no ha enviado heartbeat en los últimos 10 segundos
function verificarFallos() {
    global $nodes;
    foreach ($nodes as &$node) {
        if (time() - $node["last_heartbeat"] > 10) { // Si han pasado más de 10 segundos sin heartbeat
            $node["active"] = false;
            registrarFallo($node["id"]); // Registrar fallo en log.php
        }
    }
}

// Registrar fallo en log.php
function registrarFallo($node_id) {
    $logFile = '/var/www/html/logs/event.log';
    $logMessage = date('Y-m-d H:i:s') . " - Nodo " . $node_id . " ha fallado.\\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

// Respuesta JSON
header("Content-Type: application/json");
verificarFallos(); // Verificar fallos antes de enviar respuesta
echo json_encode([
    "nodes" => $nodes
]);
?>
