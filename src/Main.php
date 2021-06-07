<?php

namespace App;

use Severyak\Brackets;

class Main
{
    protected string $filename;

    public function __construct($filename)
    {
        if (!$filename) {
            die('Путь к файлу пуст');
        }
        $this->filename = $filename;
    }

    public function run()
    {
        if (file_exists($this->filename)) {
            $str = file_get_contents($this->filename);
            try {
                $lib = new Brackets($str);
                if ($lib->check()) {
                    echo 'Строка корректна';
                } else {
                    echo 'Строка некорректна';
                }
            } catch (\InvalidArgumentException $e) {
                die("Строка содержит недопустимые символы или пуста\n" . $this->filename . "\n" . $str);
            }
        } else {
            die("Файл не найден:\n" . $this->filename);
        }
    }
}
