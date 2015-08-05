<?php

class Bar {
    /**
    * @var string
    */
    public $var_abc = null;
    /**
    * @var string
    */
    public $var_xyz = null;
    /**
    * @var string
    */
    public $var_foo = null;

    public function foourz() { }
}

class Foo {
    private $_foobar = null;
    
    /**
    * @param $a bool
    */
    public function foobar($a) {
        return new Bar();
    }

    /**
    *
    */
    public function test() {
        $arr = [];
    }
}

$f = new Foo();
