<?php

namespace App\Enums;

enum StatutEnum: string
{
    case ENATTENTE = 'en-attente';
    case ANNULE = 'annule';
    case TERMINE = 'termine';
    case CONFIRME = 'confirme';

    // Optionnel : Ajouter des labels lisibles
    public function label(): string
    {
        return match ($this) {
            self::ENATTENTE => 'En attente',
            self::ANNULE => 'Annulé',
            self::TERMINE => 'Terminé',
            self::CONFIRME => 'Confirmé',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function toArray(): array
    {
        return array_map(
            fn(self $case) => ['value' => $case->value, 'label' => $case->label()],
            self::cases()
        );
    }


}
