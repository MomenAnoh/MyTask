<?php

namespace App\Enums;

enum TaskStatusEnum: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case IN_PROGRESS = 'in_progress';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
