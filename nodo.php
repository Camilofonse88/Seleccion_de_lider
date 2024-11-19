<?php
include 'tipos_mensajes.php';
include 'mensajes.php';
include 'logs.php';

$puerto = 8080; // Cambia esto para cada nodo
recibirMensajes($puerto);

// Enviar un mensaje de propuesta
$propuesta = [
    'tipo' => MENSAJE_PROPOSAL,
    'lider' => 'Candidato A',
    'origen' => 'mi_ip', // Cambia esto a tu IP
    'puerto' => $puerto,
];
enviarMensaje($propuesta, 'ip_del_destinatario', $puerto);

// Función para enviar un heartbeat cada 5 segundos
function enviarHeartbeat() {
    $heartbeat = [
        'tipo' => 'heartbeat',
        'origen' => 'mi_ip', // Cambia esto a la IP del nodo
        'timestamp' => time(),
    ];
    enviarMensaje($heartbeat, 'ip_del_status', 8080); // Cambia a la IP del status
}

// Ejecutar el heartbeat periódicamente
while (true) {
    enviarHeartbeat();
    sleep(5); // Espera 5 segundos antes del siguiente heartbeat
}
?>
    