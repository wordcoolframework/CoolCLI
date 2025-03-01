# CoolCLI

CoolCLI is a lightweight and extensible CLI tool for executing custom commands in PHP. With CoolCLI, you can easily define your own commands and use them

## 🚀 Features

- ✅ **Easy Command Definition**: Create your own command classes and add them to the CLI.
- ✅ **Supports Arguments & Options**: Define various arguments and options for your commands.
- ✅ **Modular Structure**: Add new commands without modifying the core CLI.
- ✅ **Similar to Laravel Artisan**: If you're familiar with Artisan, CoolCLI will feel intuitive.

## 📥 Installation

To install CoolCLI, run the following command:

```php
composer require wordcoolframework/cool-cli
```
## 📌 Defining a New Command

To create a new command, add a new class in the `command/` directory. For example, `GreetCommand.php`:

```php
namespace Command;

final class GreetCommand {
    public string $commandName = 'greet';
    public string $description = 'Prints a greeting message';

    public static function handle(?string $name = 'World') : void {
        echo "Hello, $name!\n";
    }
}

```

Now, you can execute this command:

```shell
php cool greet
php cool greet John
```


## 🛠️ Configuration

CLI settings are stored in the config.php file:

```php
return [
    "CommandNamespace"  => "\\Command",
    "CommandDirectory"  => "/command",
];
```

## 🌍 Contributing
🔹 Contributions and improvements are always welcome! To contribute:

- Fork this repository.
- Create a new branch (```git checkout -b feature-name```).
- Commit your changes (```git commit -m 'Add some feature'```).
- Submit a Pull Request to the main repository.