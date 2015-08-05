<?php

require_once 'Parser.php';

$options = getopt('o:', ['file:', 'pretty::']);

if (!array_key_exists('file', $options)) {
    print 'No file specified. Usage is: php parse.php --file=<filename>' . PHP_EOL;
} else {
    $output_file = array_key_exists('o', $options) ? $options['o'] : null;
    $pretty      = array_key_exists('pretty', $options) ? filter_var($options['pretty'], FILTER_VALIDATE_BOOLEAN) : true;

    $p = new Parser();
    $p->parse($options['file']);
    $json = $p->exportScopes($pretty);

    if ($output_file) {
        file_put_contents($output_file, $json);
    } else {
        print $json . PHP_EOL;
    }
}
