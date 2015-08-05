<?php

require_once 'MagicGet.php';
require_once 'Pretty.php';

final class Procedure
{
    public $protection = 0;
    public $state      = 0;

    private $_line       = 0;
    private $_id         = null;
    private $_parameters = [];

    public function __construct(int $line, string $id)
    {
        $this->_line = $line;
        $this->_id   = $id;
    }

    public function addParameter(string $id, string $type)
    {
        $var       = new Variable($this->line, $id);
        $var->type = $type;

        $this->_parameters[$id] = $var;
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

        $pretty = implode(' ', $output);

        return $pretty . ' ' . $this->asString();
    }

    public function asString()
    {
        $params = [];

        $c = count($this->parameters);
        $i = 0;

        $params[] = '(';
        foreach ($this->parameters as $param) {
            $type = $param->type ? $param->type . ' ' : null;

            $params[] = $type . $param->id;

            if (($i + 1) < $c) {
                $params[] = ',';
            }

            $i++;
        }
        $params[] = ')';

        return $this->id . implode('', $params);
    }

    use MagicGet;
}
