<?php

namespace App\Enums;

enum VersionAttestation: string
{
    case Provisoire = 'PROVISOIRE';
    case Originale = 'ORIGINALE';

    public function label(): string
    {
        return match ($this) {
            self::Provisoire => 'Provisoire',
            self::Originale => 'Originale',
        };
    }
}
