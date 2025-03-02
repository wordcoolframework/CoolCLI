<?php

namespace CoolCLI;

use CoolCLI\Concern\CommandConcern;

final class CoolCLI{

    use CommandConcern;
    private static array $commands  = [];

    public static function register(string $className) : void {
        self::$commands[] = $className;
    }

    public static function registerAllCommands(
        string $commandNamespace,
        string $commandDirectory
    ): void {
        $files = glob($commandDirectory . '/*.php');
        self::registerClasses($files, $commandNamespace);
    }

    private static function findClosestCommand(string $inputCommand): ?string {
        return self::suggestion($inputCommand);
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
            self::handleSuggestionCommand($suggestedCommand, $argument, $option);
        } else {
            echo "\033[1;31mCommand not found.\033[0m\n";
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