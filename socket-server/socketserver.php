<?php

/**
 * Socket.io in PHP implementation to solve limitation not able to access node
 */

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Workerman\Worker;
use PHPSocketIO\SocketIO;

$dotenv = new Dotenv\Dotenv(dirname(__DIR__));
$dotenv->load();

$io = new SocketIO(getenv('SOCKET_PORT'));
$io->on('connection', function($socket) use($io) {
    // silence is golden
});

Worker::runAll();