<?php
session_start();

$heartbeat_timeout = 10; // Timeout en segundos para fallos de nodo

$nodes = [
    1 => ['ip' => '127.0.0.1', 'port' => 8081],
    2 => ['ip' => '127.0.0.1', 'port' => 8082],
    3 => ['ip' => '127.0.0.1', 'port' => 8083]
];

foreach ($nodes as $node_id => $node) {
    if (isset($_SESSION['last_heartbeat'][$node_id])) {
        $last_heartbeat = $_SESSION['last_heartbeat'][$node_id];
        
        // Si no se recibe un heartbeat dentro del tiempo de espera, el nodo ha fallado
        if ((time() - $last_heartbeat) > $heartbeat_timeout) {
            echo "Nodo $node_id ha fallado. Se procederá con la reelección.\n";
            // Iniciar reelección
            startElection();
        }
    }
}

function startElection() {
    echo "Iniciando elección...\n";
    // Inicia el proceso de reelección de líder
    include("start_election.php");
}
?>
