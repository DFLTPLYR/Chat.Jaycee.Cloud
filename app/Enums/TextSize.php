<?php

namespace App\Enums;

enum TextSize: string
{
    case XS = 'text-xs';
    case SM = 'text-sm';
    case BASE = 'text-base'; // Recommended default
    case LG = 'text-lg';
    case XL = 'text-xl';
    case XL2 = 'text-2xl';
    case XL3 = 'text-3xl';
    case XL4 = 'text-4xl';
    case XL5 = 'text-5xl';
    case XL6 = 'text-6xl';
    case XL7 = 'text-7xl';
    case XL8 = 'text-8xl';
    case XL9 = 'text-9xl';

    public static function options(): array
    {
        return array_column(self::cases(), 'value');
    }
}
