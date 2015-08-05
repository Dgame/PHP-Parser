# PHP-Parser

Parses your php file and extract class, method/function and property informations.

Usage: `php parse.php --file=<filename>`

## Example

Input
```php
<?php

class Bar
{
    public $var_abc = null;
    public $var_xyz = null;
    public $var_foo = null;

    public function foourz()
    {

    }
}

class Foo
{
    private $_foobar = null;

    public function foobar($a)
    {

    }

    public function test()
    {

    }
}
```

Output:
```json
{
    "class Bar": {
        "properties": [
            "public $var_abc",
            "public $var_xyz",
            "public $var_foo"
        ],
        "functions": [
            "public foourz()"
        ]
    },
    "class Foo": {
        "properties": [
            "private $_foobar"
        ],
        "functions": [
            "public foobar($a)",
            "public test()"
        ]
    }
}
```
