<?php

namespace App;

use Severyak\Brackets;

class Main
{
    protected string $address = '127.0.0.1';
    protected int $port;

    public function __construct($port)
    {
        $this->port = $port;
    }

    public function run()
    {
//        var_dump($this);
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_bind($socket, $this->address, $this->port);

        if (socket_listen($socket, 2)) {
            echo "Сервер запущен.\nАдрес: " . $this->address . "\nПорт: " . $this->port . "\n\n";
        }

        do {
            $msgsock = socket_accept($socket);

            if (pcntl_fork() === 0) {
                $msg = "\n" . INTRO . "\n\n";
                $msg .= "Добро пожаловать на сервер!\n\n";
                socket_write($msgsock, $msg, strlen($msg));

                do {
                    $msg = "Введите строку для валидации:\n";
                    socket_write($msgsock, $msg, strlen($msg));

                    $buffer = socket_read($msgsock, 2048, PHP_BINARY_READ);
                    $buffer = trim($buffer);

                    if ($buffer === 'quit') {
                        break;
                    }

//                printf(": %s\n", $buffer);

                    $msg = '';
                    try {
                        $lib = new Brackets($buffer);
                        if ($lib->check()) {
                            $msg .= 'Строка корректна';
                        } else {
                            $msg .= 'Строка некорректна';
                        }
                    } catch (\InvalidArgumentException $e) {
                        $msg .= "Строка содержит недопустимые символы или пуста";
                    }
                    $msg .= "\n\n";
                    socket_write($msgsock, $msg, strlen($msg));
                } while (true);
            }
            socket_close($msgsock);
        } while (true);

        socket_close($socket);
    }
}
