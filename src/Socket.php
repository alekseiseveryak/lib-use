<?php

namespace App;

use Severyak\Brackets;

class Socket
{
    protected string $address = '127.0.0.1';
    protected int $port = 0;
    protected $master;
    protected array $clients = [];
    protected bool $abort = false;

    public function __construct()
    {
    }

    public function start()
    {
        $null = null;
        $result = true;
        $this->abort = false;
        $this->master = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        $result &= socket_set_option($this->master, SOL_SOCKET, SO_REUSEADDR, 1);
        $result &= socket_bind($this->master, $this->address, $this->port);
        $result &= socket_listen($this->master, 2);

        if ($result) {
            echo "Сервер запущен.\nАдрес: " . $this->address . "\nПорт: " . $this->port . "\n\n";
        } else {
            die("Невозможно привязать и прослушивать $this->address: $this->port\n");
        }

        $this->clients = [$this->master];

        do {
            $read = $this->clients;
            $readyChanges = @socket_select($read, $null, $null, 0, 10);

            pcntl_signal_dispatch();

            // Флаг закрытия сокета
            if ($this->abort) {
                break;
            }

            if ($readyChanges < 1) {
                continue;
            }

            // Проверка на новое подключение
            if (in_array($this->master, $read)) {
                $newSocket = socket_accept($this->master);
                $this->clients[] = $newSocket;
                socket_getpeername($newSocket, $ip);
                echo "Подключился клиент: {$ip}\n";

                $msg = "\n" . WELCOME . "\n\n";
                $msg .= "Добро пожаловать на сервер!\n\n";
                $msg .= "Введите строку для валидации:\n\n";
                $this->send($newSocket, $msg);

                $key = array_search($this->master, $read);
                unset($read[$key]);
            }

            // Цикл по всем клиентам с проверкой изменений в каждом из них
            foreach ($this->clients as $clientSocket) {
                // Проверяем наличие новых данных в клиентском сокете
                if (in_array($clientSocket, $read)) {
                    $input = @socket_read($clientSocket, 1024);

                    // Проверяем, если клиент отсоединился
                    if ($input === false) {
                        $this->disconnect($clientSocket);
                        continue;
                    }

                    $input = trim($input);

                    if ($input === 'quit') {
                        $this->disconnect($clientSocket);
                        continue;
                    }
                    if ($input === 'q') {
                        $this->stop();
                        continue;
                    }

                    $msg = '';
                    try {
                        $lib = new Brackets($input);
                        if ($lib->check()) {
                            $msg .= 'Строка корректна';
                        } else {
                            $msg .= 'Строка некорректна';
                        }
                    } catch (\InvalidArgumentException $e) {
                        $msg .= "Строка содержит недопустимые символы или пуста\n\n";
                        $msg .= "Введите строку для валидации:";
                    }
                    $msg .= "\n\n";
                    $this->send($clientSocket, $msg);

//                    $output = $this->tell($client, "Вы сказали: $input\n");
//                    if (!$output) {
//                        $this->quitClient($client, $key);
//                    }
                }
            }

        } while (true);

        if ($this->master) {
            socket_close($this->master);
        }
    }

    public function stop()
    {
        $this->abort = true;
        foreach ($this->clients as $clientIndex => $clientSocket) {
            if ($clientSocket !== $this->master) {
                socket_close($clientSocket);
                unset($this->clients[$clientIndex]);
            }
        }
        echo "Сервер остановлен.\n\n";
    }

    protected function disconnect($clientSocket)
    {
        if ($clientSocket) {
            socket_close($clientSocket);
        }
        $clientIndex = array_search($clientSocket, $this->clients);
        unset($this->clients[$clientIndex]);
    }

    protected function send($clientSocket, $message)
    {
        if ($clientSocket) {
            @socket_write($clientSocket, $message, strlen($message));
        }
    }

    public function getPort()
    {
        return $this->port;
    }

    public function setPort($port)
    {
        $this->port = $port;
    }
}
