<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Lang extends CI_Lang {

    public function line_or_def($line, $default = '')
    {
        $value = $this->line($line);
        return $value ? $value : $default;
    }
}
