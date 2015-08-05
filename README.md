# PHP-Parser

Parses your php file and extract class, method/function and property informations.

Basic usage: `php parse.php --file=<filename>`

## Example #1

Command: `php parse.php --file=test-001.php -o=parse.json --pretty`

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

Output (in `parse.json`):
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

#Example #2

Command: `php parse.php --file=test-001.php -o=parse.json` (without `--pretty`)

Same Input

Output (in `parse.json`)

```json
[
    {
        "class": "Bar",
        "text": "$var_abc",
        "snippet": "var_abc",
        "type": "property",
        "typehint": null,
        "protection": "public",
        "state": null
    },
    {
        "class": "Bar",
        "text": "$var_xyz",
        "snippet": "var_xyz",
        "type": "property",
        "typehint": null,
        "protection": "public",
        "state": null
    },
    {
        "class": "Bar",
        "text": "$var_foo",
        "snippet": "var_foo",
        "type": "property",
        "typehint": null,
        "protection": "public",
        "state": null
    },
    {
        "class": "Bar",
        "text": "foourz",
        "snippet": "foourz()",
        "type": "function",
        "protection": "public",
        "state": null
    },
    {
        "class": "Foo",
        "text": "$_foobar",
        "snippet": "_foobar",
        "type": "property",
        "typehint": null,
        "protection": "private",
        "state": null
    },
    {
        "class": "Foo",
        "text": "foobar",
        "snippet": "foobar($a)",
        "type": "function",
        "protection": "public",
        "state": null
    },
    {
        "class": "Foo",
        "text": "test",
        "snippet": "test($a,$b)",
        "type": "function",
        "protection": "public",
        "state": null
    }
]
```

Without `-o=<output>` the result will be printed to stdin.

You can furthermore configurate the output by using these options:
 - no-static
 - no-abstract
 - no-trait
 - no-interface
 - no-private
 - no-protected
