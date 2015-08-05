<?php

require_once 'Scope.php';

final class Scopes
{
    private $_scopes    = [];
    private $_cur_scope = null;

    public function __construct()
    {
        $this->push(new Scope(0, 0));
    }

    public function getAll()
    {
        return $this->_scopes;
    }

    public function getCurrent()
    {
        return $this->_cur_scope;
    }

    public function pushScope(int $line, int $token)
    {
        $scope = new Scope($line, $token, $this->_cur_scope);
        $this->push($scope);
    }

    public function push(Scope $scope)
    {
        $this->_cur_scope = $scope;
        array_push($this->_scopes, $scope);
    }

    public function open()
    {
        $this->_cur_scope->usage++;
    }

    public function close()
    {
        $this->_cur_scope->usage--;
        if ($this->_cur_scope->usage <= 0) {
            $this->pop();
        }
    }

    public function pop()
    {
        // the global scope cannot be removed for valid files
        if (!$this->_cur_scope->previous) {
            throw new Exception('Unbalanced curlies');
        }

        $this->_cur_scope = $this->_cur_scope->previous;
    }
}
