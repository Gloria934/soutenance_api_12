<?php

namespace App\Enums;

enum StatutEnum: string
{
    case ENCOURS = 'en-cours';
    case ANNULE = 'annule';
    case TERMINE = 'termine';

    // Optionnel : Ajouter des labels lisibles
    public function label(): string
    {
        return match($this) {
            self::ENCOURS => 'En cours',
            self::ANNULE => 'Annulé',
            self::TERMINE => 'Terminé',
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
