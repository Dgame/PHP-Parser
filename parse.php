<?php

require_once 'Options.php';
require_once 'Parser.php';

$long_opts = [
    'file:',
    'pretty',
    'no-static',
    'no-abstract',
    'no-class',
    'no-trait',
    'no-interface',
    'no-public',
    'no-private',
    'no-protected',
    'help',
];

$opts = getopt('o:', $long_opts);

function usage()
{
    global $long_opts;

    print 'Allowed commands:' . PHP_EOL;
    foreach ($long_opts as $opt) {
        print "\t" . $opt . PHP_EOL;
    }
}

if (array_key_exists('help', $opts)) {
    usage();
} elseif (!array_key_exists('file', $opts)) {
    print 'No file specified. Usage is: php parse.php --file=<filename>' . PHP_EOL;
} else {
    $output_file = array_key_exists('o', $opts) ? $opts['o'] : null;

    $options_values = [
        'pretty'       => 'pretty',
        'no-static'    => 'noStatic',
        'no-abstract'  => 'noAbstract',
        'no-class'     => 'noClass',
        'no-trait'     => 'noTrait',
        'no-interface' => 'noInterface',
        'no-public'    => 'noPublic',
        'no-private'   => 'noPrivate',
        'no-protected' => 'noProtected',
    ];

    $options = new Options();
    foreach ($options_values as $key => $val) {
        $options->{$val} = array_key_exists($key, $opts);
    }

    $p = new Parser();
    $p->parse($opts['file']);
    $json = $p->exportScopes($options);

    if ($output_file) {
        file_put_contents($output_file, $json);
    } else {
        print $json . PHP_EOL;
    }
}
