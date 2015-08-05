<?php

require_once 'MagicGet.php';

final class Scope
{
    public $usage = 0;
    public $name = null;

    private $_line  = 0;
    private $_type = 0;

    private $_variables = [];
    private $_procedures = [];

    private $_previous  = null;

    public function __construct(int $line, int $type, Scope $previous = null)
    {
        // the global scope
        if ($line == 0) {
            $this->usage = 1;
        }

        $this->_line = $line;
        $this->_type = $type;

        $this->_previous = $previous;
    }

    public function findByType(int $type)
    {
        $scope = $this;
        do {
            if ($scope->type == $type) {
                return $scope;
            }
            $scope = $scope->previous;
        } while ($scope);

        return null;
    }

    public function addVariable(Variable $var)
    {
        $this->_variables[$var->id] = $var;
    }

    public function addProcedure(Procedure $proc)
    {
        $this->_procedures[$proc->id] = $proc;
    }

    public function findVariable(Variable $v)
    {
        $scope = $this;
        do {
            $var = $scope->getVariable($v->name);
            if ($var) {
                return $var;
            }
            $scope = $scope->previous;
        } while ($scope);

        return null;
    }

    public function getVariable(string $name)
    {
        if (array_key_exists($name, $this->_variables)) {
            return $this->_variables[$name];
        }

        return null;
    }

    public function findProcedure(Procedure $p)
    {
        $scope = $this;
        do {
            $proc = $scope->get($p->name);
            if ($proc) {
                return $proc;
            }
            $scope = $scope->previous;
        } while ($scope);

        return null;
    }

    public function getProcedure(string $name)
    {
        if (array_key_exists($name, $this->_procedures)) {
            return $this->_procedures[$name];
        }

        return null;
    }

    use MagicGet;
}
