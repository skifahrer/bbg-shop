<?php

namespace App\Enum;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case SHIPPED = 'shipped';
    case CANCELLED = 'cancelled';
}
