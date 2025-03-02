<?php

namespace CoolCLI\Color;

trait ConsoleColor {

    public static function yellow(string $text): string {
        return "\033[1;33m$text\033[0m";
    }

    public static function green(string $text): string {
        return "\033[1;32m$text\033[0m";
    }

    public static function blue(string $text): string {
        return "\033[1;34m$text\033[0m";
    }

    public static function red(string $text): string {
        return "\033[1;31m$text\033[0m";
    }

}