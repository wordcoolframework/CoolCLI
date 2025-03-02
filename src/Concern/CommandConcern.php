<?php

namespace CoolCLI\Concern;

use CoolCLI\Color\ConsoleColor;

trait CommandConcern{

    use ConsoleColor;
    public static function registerClasses($files, $commandNamespace) : void {
        foreach ($files as $file) {
            $className = $commandNamespace . '\\' . pathinfo($file, PATHINFO_FILENAME);
            if (class_exists($className)) {
                self::register($className);
            }
        }
    }

    public static function suggestion (string $inputCommand) {
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

    public static function handleCommand(?string $command, array $commands, ?string $argument, $option) : void {
        foreach ($commands as $commandClass){
            $commandName = (new $commandClass);
            if (strtolower($command) === $commandName->commandName) {
                $commandClass::handle($argument, $option);
                return;
            }
        }
    }

    public static function handleSuggestionCommand(
        string $suggestedCommand,
        $argument,
        $option
    ) : void {

        echo self::yellow("Command not found!") . "\n";
        echo "Did you mean: " . self::green("'$suggestedCommand'") . " ?\n";
        echo "Select an option: \n";
        echo self::blue("[1]") . " Yes\n";
        echo self::red("[2]") . " No\n";

        do {
            $input = trim(readline("Enter your choice (1/2): "));
        } while (!in_array($input, ['1', '2']));

        if ($input === '1') {
            foreach (self::$commands as $commandClass) {
                $commandInstance = new $commandClass;
                if (strtolower($suggestedCommand) === strtolower($commandInstance->commandName)) {
                    $commandClass::handle($argument, $option);
                    return;
                }
            }
        } else {
            echo "\033[1;31mCommand cancelled.\033[0m\n";
        }
    }

}