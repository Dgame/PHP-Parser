<?php

require_once 'Parser.php';

$options = getopt(null, ['file:']);

if (!array_key_exists('file', $options)) {
    print 'No file specified. Usage is: php parse.php --file=<filename>' . PHP_EOL;
} else {
    $p = new Parser();
    $p->parse($options['file']);
    $p->exportScopes('parse.json');
}
