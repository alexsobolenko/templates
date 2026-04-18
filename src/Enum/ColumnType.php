<?php

declare(strict_types=1);

namespace App\Enum;

enum ColumnType: string
{
    case Boolean = 'boolean';
    case Integer = 'integer';
    case String = 'string';
    case Text = 'text';
    case Timestamp = 'timestamp';

    /**
     * @param mixed $value
     * @return mixed
     */
    public function castValue(mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        return match ($this) {
            self::Boolean => filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false,
            self::Integer => (int) $value,
            self::String,
            self::Text => (string) $value,
            self::Timestamp => $value instanceof \DateTimeImmutable ? $value : new \DateTimeImmutable((string) $value),
        };
    }
}
