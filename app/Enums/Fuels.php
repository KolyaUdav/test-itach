<?php

namespace App\Enums;

enum Fuels: string
{
    case Ai95 = 'ai-95';
    case Ai92 = 'ai-92';
    case Ai98 = 'ai-98';
    case DtEuro = 'dt-euro';
    case DtArctic = 'dt-arctic';
    case AdBlue = 'adblue';
    case Gas = 'gas';

    public function getName(): string
    {
        return match ($this) {
            self::Ai95 => 'АИ-95',
            self::Ai92 => 'АИ-92',
            self::Ai98 => 'АИ-98',
            self::DtEuro => 'ДТ Евро',
            self::DtArctic => 'ДТ Арктика',
            self::AdBlue => 'AdBlue',
            self::Gas => 'Газ',
            default => '',
        };
    }
}