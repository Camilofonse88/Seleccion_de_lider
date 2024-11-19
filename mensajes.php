
<?php
include 'tipos_mensajes.php';

function enviarMensaje($mensaje, $ip, $puerto) {
    $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    socket_sendto($socket, json_encode($mensaje), strlen(json_encode($mensaje)), 0, $ip, $puerto);
    socket_close($socket);
}

function recibirMensajes($puerto) {
    $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    socket_bind($socket, '0.0.0.0', $puerto);

    while (true) {
        $buf = '';
        $from = '';
        $port = 0;
        socket_recvfrom($socket, $buf, 1024, 0, $from, $port);
        $mensaje = json_decode($buf, true);
        procesarMensaje($mensaje);
    }

    socket_close($socket);
}

function procesarMensaje($mensaje) {
    switch ($mensaje['tipo']) {
        case MENSAJE_PROPOSAL:
            // Manejar propuesta de liderazgo
            // Enviar confirmación
            $respuesta = [
                'tipo' => MENSAJE_CONFIRMACION,
                'lider' => $mensaje['lider'],
                'origen' => 'mi_ip', // Cambia esto a tu IP
                'puerto' => $mensaje['puerto'], // Puerto del nodo que envió la propuesta
            ];
            enviarMensaje($respuesta, $mensaje['origen'], $mensaje['puerto']);
            break;

        case MENSAJE_CONFIRMACION:
            // Manejar confirmaciones
            // Registrar el líder confirmado
            registrarEvento("Confirmación recibida de: " . $mensaje['lider']);
            break;

        case MENSAJE_FALLO:
            // Manejar notificaciones de fallo
            // Realizar las acciones necesarias
            registrarEvento("Notificación de fallo recibida");
            break;
    }
}
?>
