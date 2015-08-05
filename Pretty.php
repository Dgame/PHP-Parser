<?php

final class Pretty
{
    private static $Protections = [
        T_PUBLIC    => 'public',
        T_PRIVATE   => 'private',
        T_PROTECTED => 'protected',
    ];

    private static $States = [
        T_STATIC   => 'static',
        T_ABSTRACT => 'abstract',
    ];

    public static function Protection(int $prot)
    {
        if (array_key_exists($prot, self::$Protections)) {
            return self::$Protections[$prot];
        }

        return null;
    }

    public static function State(int $state)
    {
        if (array_key_exists($state, self::$States)) {
            return self::$States[$state];
        }

        return null;
    }
}
