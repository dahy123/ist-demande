<?php

namespace App\Enums;

enum TypeJustificatif: string
{
    case RecuIst = 'RECU_IST';
    case BordereauBancaire = 'BORDEREAU_BANCAIRE';

    public function label(): string
    {
        return match ($this) {
            self::RecuIst => 'Reçu IST',
            self::BordereauBancaire => 'Bordereau bancaire',
        };
    }
}
