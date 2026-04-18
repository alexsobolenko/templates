<?php

declare(strict_types=1);

namespace App\Attribute;

use App\Enum\ColumnType;
use App\Exception\Attribute\InvalidColumnValueException;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
final readonly class Column
{
    /**
     * @param string $name
     * @param ColumnType $type
     * @param bool $primary
     * @param bool $required
     * @param bool $nullable
     */
    public function __construct(
        public string $name,
        public ColumnType $type = ColumnType::String,
        public bool $primary = false,
        public bool $required = false,
        public bool $nullable = false
    ) {}

    /**
     * @param mixed $value
     * @return mixed
     */
    public function castValue(mixed $value): mixed
    {
        if ($value === null) {
            if (!$this->nullable) {
                throw new InvalidColumnValueException('Column "' . $this->name . '" does not allow null values.');
            }

            return null;
        }

        return $this->type->castValue($value);
    }
}
