<?php

namespace App\Enums;

enum UniteMesureEnum: string
{
    case MG = 'mg';
    case MCG = 'mcg';
    case L = 'litre';
    case ML = 'mL';
    case UI = 'UI';

    // Optionnel : Ajouter des labels lisibles
    public function label(): string
    {
        return match($this) {
            self::MG => 'Mg',
            self::MCG => 'Mcg',
            self::L => 'L',
            self::ML => 'mL',
            self::UI=> 'UI',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function toArray(): array{
        return array_map(
            fn(self $case) => ['value' => $case->value, 'label' => $case->label()],
            self::cases()
        );
    }
    

}
