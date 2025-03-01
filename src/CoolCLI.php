<?php

namespace CoolCLI;

final class CoolCLI{

    private static array $commands  = [];

    public static function register(string $className) : void {
        self::$commands[] = $className;
    }

    public static function registerAllCommands(
        string $commandNamespace,
        string $commandDirectory
    ): void {

        $files = glob($commandDirectory . '/*.php');

        foreach ($files as $file) {
            $className = $commandNamespace . '\\' . pathinfo($file, PATHINFO_FILENAME);
            if (class_exists($className)) {
                self::register($className);
            }
        }
    }

    private static function findClosestCommand(string $inputCommand): ?string {
        $commands = array_map(fn($cmd) => (new $cmd)->commandName, self::$commands);
        $closest = null;
        $shortestDistance = PHP_INT_MAX;

        foreach ($commands as $command) {
            $lev = levenshtein($inputCommand, $command);
            if ($lev < $shortestDistance) {
                $closest = $command;
                $shortestDistance = $lev;
            }
        }

        return ($shortestDistance <= 3) ? $closest : null;
    }

    public static function run(?string $command, ?string $option, string|int|null $argument = null) : void {

        if (!$command) {
            self::showCommands();
            return;
        }

        foreach (self::$commands as $commandClass){
            $commandName = (new $commandClass);
            if (strtolower($command) === $commandName->commandName) {
                $commandClass::handle($argument, $option);
                return;
            }
        }

        $suggestedCommand = self::findClosestCommand($command);
        if ($suggestedCommand) {
            echo "Command not found. Did you mean: '$suggestedCommand'?\n";
        } else {
            echo "Command not found.\n";
        }
    }

    private static function showCommands(): void {
        echo "Available commands:\n\n";

        foreach (self::$commands as $commandClass) {
            $commandName = (new $commandClass)->commandName;
            $commandDesc = (new $commandClass)->description ?? 'no description available';
            printf(" %-10s %s\n", "*" . $commandName, $commandDesc);
        }

        echo "\nUsage: php cool <command> [options]\n";
    }

}