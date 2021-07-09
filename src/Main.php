<?php

namespace App;

class Main
{
    protected Socket $socket;
    protected Config $config;
    protected int $currentPort;

    public function __construct()
    {
        $this->config = new Config();
        $this->socket = new Socket();
        $this->loadConfig();
        $this->socket->setPort($this->currentPort);

        pcntl_signal(SIGHUP, function () {
            $this->restart();
        });
        pcntl_signal_dispatch();

        echo WELCOME . "\n\n";
    }

    public function run()
    {
        $this->socket->start();
    }

    protected function restart()
    {
        $this->loadConfig();
        if ($this->socket->getPort() !== $this->currentPort) {
            echo "Выполняется перезапуск сервера...\n";
            $this->socket->stop();
            $this->socket->setPort($this->currentPort);
            $this->socket->start();
        } else {
            echo "Перезапуск не требуется...\n";
        }
    }

    protected function loadConfig()
    {
        $this->config->load();
        $port = (int) $this->config->params->port ?? false;

        if ($port) {
            $this->currentPort = $port;
        } else {
            die("Невозможно получить номер порта\n");
        }
    }
}
