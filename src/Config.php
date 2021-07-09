<?php

namespace App;

use Symfony\Component\Yaml\Yaml;

class Config
{
    public \stdClass $params;
    protected string $filename = ROOT . '/config.yml';
    protected array $default = [
        'port' => 10000,
    ];

    public function __construct()
    {
        if (!file_exists($this->filename)) {
            $yaml = Yaml::dump($this->default, 2, 4);
            file_put_contents($this->filename, $yaml);
        }
    }

    public function load()
    {
        $array = Yaml::parseFile($this->filename);
        $this->params = json_decode(json_encode($array));
    }
}
