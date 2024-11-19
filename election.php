<?php
session_start();


// Verificar las variables de entorno
echo 'NODE_ID: ' . getenv('NODE_ID') . "\n";
echo 'ALL_NODES: ' . getenv('ALL_NODES') . "\n";
$leader = $_SESSION['leader'] ?? null;
$heartbeat_interval = 5; // Intervalo de heartbeat en segundos

// Función para iniciar una elección
function startElection() {
    global $node_id, $all_nodes;
    echo "Nodo $node_id iniciando elección...\n";
    $responses = 0;

    foreach ($all_nodes as $node) {
        [$ip, $port] = explode(':', $node);
        $url = "http://$ip:$port/respond_election.php";

        // Solo envía mensaje a nodos con IDs mayores
        if ((int)$node_id < (int)$port) {
            $response = @file_get_contents($url . "?node_id=$node_id");
            if ($response === "ok") {
                $responses++;
            }
        }
    }

    if ($responses === 0) {
        declareLeader();
    }
}

// Función para responder a una solicitud de elección
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['node_id']) && strpos($_SERVER['REQUEST_URI'], 'respond_election.php') !== false) {
    $requester_id = $_GET['node_id'];
    if ((int)$requester_id < (int)$node_id) {
        echo "ok";
    } else {
        echo "not ok";
    }
    exit;
}

// Función para declarar este nodo como líder
function declareLeader() {
    global $node_id, $all_nodes;
    $_SESSION['leader'] = $node_id;
    echo "Nodo $node_id se ha declarado líder\n";

    // Notificar a todos los nodos del nuevo líder
    foreach ($all_nodes as $node) {
        [$ip, $port] = explode(':', $node);
        $url = "http://$ip:$port/notify_leader.php?leader_id=$node_id";
        @file_get_contents($url);
    }
}

// Función para recibir notificación de un nuevo líder
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['leader_id']) && strpos($_SERVER['REQUEST_URI'], 'notify_leader.php') !== false) {
    $leader_id = $_GET['leader_id'];
    $_SESSION['leader'] = $leader_id;
    echo "Nodo $node_id reconoce a $leader_id como líder\n";
    exit;
}

// Función para enviar heartbeats a todos los nodos
function sendHeartbeat() {
    global $node_id, $all_nodes;
    foreach ($all_nodes as $node) {
        [$ip, $port] = explode(':', $node);
        $url = "http://$ip:$port/receive_heartbeat.php?node_id=$node_id";
        @file_get_contents($url);
    }
}

// Función para recibir heartbeats de otros nodos
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['node_id']) && strpos($_SERVER['REQUEST_URI'], 'receive_heartbeat.php') !== false) {
    $heartbeat_node_id = $_GET['node_id'];
    $_SESSION['last_heartbeat'][$heartbeat_node_id] = time();
    echo "Heartbeat recibido de nodo $heartbeat_node_id\n";
    exit;
}

// Comprobación periódica de fallos en los nodos
function checkFailures() {
    global $node_id, $heartbeat_interval;
    foreach ($_SESSION['last_heartbeat'] as $node => $last_time) {
        if ((time() - $last_time) > $heartbeat_interval * 2) { // Considera fallo si no hay respuesta en 2 intervalos
            echo "Fallo detectado en nodo $node. Iniciando elección...\n";
            startElection();
        }
    }
}

// Loop para enviar heartbeats y verificar fallos en intervalos regulares
while (true) {
    sendHeartbeat();       // Enviar heartbeats a otros nodos
    checkFailures();       // Comprobar si algún nodo ha fallado
    sleep($heartbeat_interval); // Esperar el intervalo definido antes de la próxima verificación
}

?>
