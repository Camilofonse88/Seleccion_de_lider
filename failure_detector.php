<?php
session_start();

$heartbeat_interval = 5; // Intervalo de tiempo entre verificaciones
$fail_threshold = 10; // Tiempo después del cual un nodo se considera caído (en segundos)

$nodes = [
    1 => ["ip" => "127.0.0.1", "last_heartbeat" => time()],
    2 => ["ip" => "127.0.0.1", "last_heartbeat" => time()],
    3 => ["ip" => "127.0.0.1", "last_heartbeat" => time()],
];

function checkNodes() {
    global $nodes, $heartbeat_interval, $fail_threshold;

    foreach ($nodes as $node_id => $node) {
        if ((time() - $node['last_heartbeat']) > $fail_threshold) {
            echo "Nodo $node_id ha fallado. Iniciando reelección...\n";
            startElection();
        }
    }
}

function startElection() {
    // Lógica de elección
    echo "Iniciando proceso de reelección de líder...\n";
    include("start_election.php");
}

while (true) {
    checkNodes();
    sleep($heartbeat_interval);
}
?>
