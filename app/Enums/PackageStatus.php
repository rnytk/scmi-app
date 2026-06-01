<?php

namespace App\Enums;

enum PackageStatus: string
{
    case Created = 'created';
    case InOriginReception = 'in_origin_reception';
    case InTransit = 'in_transit';
    case InDestinationReception = 'in_destination_reception';
    case Delivered = 'delivered';

    public function label(): string
    {
        return match($this) {
            self::Created => 'Creado (En origen)',
            self::InOriginReception => 'En Recepción Origen',
            self::InTransit => 'En Tránsito',
            self::InDestinationReception => 'En Recepción Destino',
            self::Delivered => 'Entregado',
        };
    }
}