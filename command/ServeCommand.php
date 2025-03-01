<?php

namespace Command;

final class ServeCommand {

    public string $commandName = 'serve';
    public string $description = 'Serve the application | php cool serve {port} {host}';

    public static function handle(?string $host, ?int $port = null) : void {
        $defaultPort = 8000;
        $defaultHost = 'http://localhost';

        $port = $port ?? $defaultPort;
        $host = $host ?? $defaultHost;

        if (!is_numeric($port) || (int)$port <= 0 || (int)$port > 65535) {
            echo "The port is invalid. Please enter a valid number between 1 and 65535.\n";
            exit(1);
        }

        $port = (int)$port;

        $rootDir = getcwd();

        echo "[*] Server running on $host:$port \n";
        echo "Press Ctrl+C to exit . \n";
//
//        $command = sprintf('php -S localhost:%d -t %s', $port, escapeshellarg($rootDir));
//        shell_exec($command);
    }
}