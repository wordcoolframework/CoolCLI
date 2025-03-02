<?php

final class Setup {

    private static string $rootPath;

    public static int $outSideVendor = 3;

    public static function init() : void{
        self::$rootPath = dirname(__DIR__, self::$outSideVendor)
            . DIRECTORY_SEPARATOR;
    }

    public static function getRootPath(): string {
        return self::$rootPath;
    }

    public static function createDirectories(array $directories) : void {
        foreach ($directories as $dir) {
            $path = self::$rootPath . $dir;
            if (!is_dir($path)) {
                if (mkdir($path, 0755, true)) {
                    echo "[*] Created directory: $path\n";
                } else {
                    echo "[!] Failed to create directory: $path\n";
                }
            }
        }
    }

    public static function createFiles(array $files) : void {
        foreach ($files as $file => $content) {
            $path = self::$rootPath . $file;
            if (!file_exists($path)) {
                if (file_put_contents($path, $content) !== false) {
                    echo "[*] Created file: $path\n";
                } else {
                    echo "[!] Failed to create file: $path\n";
                }
            }
        }
    }
}

Setup::init();

$rootPath = Setup::getRootPath();

$directories = [
    'command',
    'config',
];

$files = [

    'config/cli.php' => "<?php\n\nreturn [\n    'CommandNamespace' => \"\\Command\",\n    'CommandDirectory' => \"/command\",\n ];",

    'cool' => "#!/usr/bin/env php\n<?php\n\n" .
        "use Configuration\\Config;\n" .
        "use CoolCLI\\CoolCLI;\n\n" .
        "require_once 'vendor/autoload.php';\n\n" .
        "\$commandNamespace = Config::get('cli.CommandNamespace');\n" .
        "\$commandDirectory = __DIR__ . Config::get('cli.CommandDirectory');\n\n" .
        "CoolCLI::registerAllCommands(\$commandNamespace, \$commandDirectory);\n\n" .
        "\$command    = \$argv[1] ?? null;\n" .
        "\$argument   = \$argv[2] ?? null;\n" .
        "\$option     = \$argv[3] ?? null;\n\n" .
        "CoolCLI::run(\$command, \$argument, \$option);\n"
];

Setup::createDirectories($directories);
Setup::createFiles($files);

$scriptPath = __FILE__;

if (file_exists($scriptPath)) {
    unlink($scriptPath);
    echo "[*] Setup is completed !: $scriptPath\n";
}