<?php

namespace App\Enums;

enum UniteVenteEnum: string
{
    case PLAQUETTES = 'plaquette';
    case BOITE = 'boite';
    case FLACON = 'flacon';
    case TUBE = 'tube';

    // Optionnel : Ajouter des labels lisibles
    public function label(): string
    {
        return match($this) {
            self::PLAQUETTES => 'Plaquette',
            self::BOITE => 'Boîte',
            self::FLACON => 'Flacon',
            self::TUBE=> 'Tube',
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
