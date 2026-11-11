<?php

namespace App\Enums;

enum Diplome: string
{
    case Dts = 'DTS';
    case Dtss = 'DTSS';
    case Ingeniorat = 'INGENIORAT';

    public function label(): string
    {
        return match ($this) {
            self::Dts => 'DTS',
            self::Dtss => 'DTSS',
            self::Ingeniorat => 'Ingéniorat',
        };
    }
}
