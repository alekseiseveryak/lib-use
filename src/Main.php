<?php

namespace App;

use Severyak\Brackets;

class Main
{
    protected $input;
    protected $output = 'Строка пуста';

    public function __construct($input)
    {
        $this->input = $input;
    }

    public function run()
    {
        $success = false;

        if ($this->input) {
            try {
                $lib = new Brackets($this->input);
                if ($lib->check()) {
                    $success = true;
                    $this->output = 'Строка корректна';
                } else {
                    $this->output = 'Строка некорректна';
                }
            } catch (\InvalidArgumentException $e) {
                $this->output = "Строка содержит недопустимые символы";
            }
        }

        if ($success) {
            header('HTTP/1.1 200 OK');
        } else {
            header('HTTP/1.1 400 Bad Request');
        }

        echo $this->output;
    }
}
