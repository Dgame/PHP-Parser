<?php

require_once 'MagicGet.php';
require_once 'Pretty.php';

final class Variable
{
    public $type = null;

    public $protection = 0;
    public $state      = 0;

    private $_line = 0;
    private $_id   = null;

    public function __construct(int $line, string $id)
    {
        $this->_line = $line;
        $this->_id   = $id;
    }

    public function asPrettyString()
    {
        $output = [];

        $prot = Pretty::Protection($this->protection);
        if ($prot) {
            $output[] = $prot;
        }

        $state = Pretty::State($this->state);
        if ($state) {
            $output[] = $state;
        }

        if ($this->type) {
            $output[] = $this->type;
        }

        $output[] = $this->id;

        return implode(' ', $output);
    }

    use MagicGet;
}
