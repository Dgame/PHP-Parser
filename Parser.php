<?php

error_reporting(E_ALL);

require_once 'basic_type_hint.php';
require_once 'Tokenizer.php';
require_once 'Cursor.php';
require_once 'Scopes.php';
require_once 'Pretty.php';

require_once 'Variable.php';
require_once 'Procedure.php';

final class Parser
{
    private $_scopes = null;

    public function parse(string $filename)
    {
        $scopes    = new Scopes();
        $tokenizer = new Tokenizer($filename);
        $cursor    = new Cursor($tokenizer->getTokens());

        $tok = $cursor->getCurrent();

        do {
            switch ($tok->type) {
                case T_CLASS:
                case T_INTERFACE:
                case T_TRAIT:
                    $scopes->pushScope($tok->line, $tok->type);
                    $scopes->getCurrent()->name = $cursor->next()->id;
                    break;

                case T_FUNCTION:
                    $proc = $this->_buildProcedure($cursor);
                    $scopes->getCurrent()->addProcedure($proc);
                    break;

                case T_VARIABLE:
                    $var = $this->_buildVariable($cursor);
                    if ($var->protection) { // only properties are consumed
                        $scopes->getCurrent()->addVariable($var);
                    }
                    break;

                case T_OPEN_CURLY:
                    $scopes->open();
                    break;

                case T_CLOSE_CURLY:
                    $scopes->close();
                    break;
            }

            $tok = $cursor->next();
        } while ($cursor->isValid());

        $this->_scopes = $scopes;
    }

    public function exportScopes(bool $pretty)
    {
        $output = [];

        foreach ($this->_scopes->getAll() as $scope) {
            if ($scope->type == T_CLASS || $scope->type == T_INTERFACE) {
                $type = Pretty::Type($scope->type);
                $key  = $type . ' ' . $scope->name;

                if ($pretty) {
                    $output[$key] = [];
                }

                foreach ($scope->variables as $var) {
                    if ($pretty) {
                        $output[$key]['properties'][] = $var->asPrettyString();
                    } else {
                        $output[] = [
                            $type        => $scope->name,
                            'text'       => $var->id,
                            'type'       => 'variable',
                            'typehint'   => $var->type,
                            'protection' => Pretty::Protection($var->protection),
                            'state'      => Pretty::State($var->state),
                        ];
                    }
                }

                foreach ($scope->procedures as $proc) {
                    if ($pretty) {
                        $output[$key]['functions'][] = $proc->asPrettyString();
                    } else {
                        $output[] = [
                            $type        => $scope->name,
                            'text'       => $proc->asString(),
                            'type'       => 'function',
                            'protection' => Pretty::Protection($proc->protection),
                            'state'      => Pretty::State($proc->state),
                        ];
                    }
                }
            }
        }

        return json_encode($output, JSON_PRETTY_PRINT);
    }

    private function _buildVariable(Cursor $cursor)
    {
        $tok = $cursor->getCurrent();
        assert($tok->type == T_VARIABLE);

        $var  = new Variable($tok->line, $tok->id);
        $info = $this->_extractInfo($cursor);

        if (array_key_exists('state', $info)) {
            $var->state = (int) $info['state'];
        }

        if (array_key_exists('protection', $info)) {
            $var->protection = (int) $info['protection'];
        }

        return $var;
    }

    private function _buildProcedure(Cursor $cursor)
    {
        $tok = $cursor->getCurrent();
        assert($tok->type == T_FUNCTION);

        $info = $this->_extractInfo($cursor);

        $tok = $cursor->next();

        $proc = new Procedure($tok->line, $tok->id);

        if (array_key_exists('state', $info)) {
            $proc->state = (int) $info['state'];
        }

        if (array_key_exists('protection', $info)) {
            $proc->protection = (int) $info['protection'];
        }

        $cursor->next(); // jump over '('
        $tok  = $cursor->next(); // ')' or the first param
        $type = '';
        while ($cursor->isValid() && $tok->type != T_CLOSE_PAREN) {
            switch ($tok->type) {
                case T_STRING: // typehint
                    $type = $tok->id;
                    break;

                case T_VARIABLE:
                    $proc->addParameter($tok->id, $type);
                    $type = ''; // reset
                    break;
            }

            $tok = $cursor->next();
        }

        // implicit abstract?
        if ($cursor->lookAhead()->type == T_SEMICOLON) {
            $proc->protection = T_ABSTRACT;
        }

        return $proc;
    }

    private function _extractInfo(Cursor $cursor)
    {
        $cursor->pushPosition();

        $info = [];

        $prev = $cursor->previous();
        while ($prev->type != T_SEMICOLON && $prev->type != T_OPEN_TAG && $prev->type != T_OPEN_CURLY) {
            switch ($prev->type) {
                case T_STATIC:
                case T_ABSTRACT:
                    $info['state'] = $prev->type;
                    break;

                case T_PUBLIC:
                case T_PRIVATE:
                case T_PROTECTED:
                    $info['protection'] = $prev->type;
                    break;
            }

            $prev = $cursor->previous();
        }

        $cursor->popPosition();

        return $info;
    }
}
