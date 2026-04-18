<?php

declare(strict_types=1);

namespace App\Core\Model;

use App\Attribute\Column;
use App\Attribute\Table;
use App\Core\App;
use App\Exception\Model\ModelConfigurationException;

/**
 * @phpstan-consistent-constructor
 */
abstract class AbstractModel
{
    /**
     * @param int $id
     * @return static|null
     */
    public static function find(int $id): ?static
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @param array $conditions
     * @param array $order
     * @return static|null
     */
    public static function findOne(array $conditions, array $order = []): ?static
    {
        $rows = static::findAll($conditions, $order, 1, 0);
        $row = $rows[0] ?? null;

        return $row === null ? null : static::fromArray($row);
    }

    /**
     * @param array $conditions
     * @param array $order
     * @param int|null $limit
     * @param int $offset
     * @return array
     */
    public static function findAll(array $conditions = [], array $order = [], ?int $limit = null, int $offset = 0): array
    {
        [$where, $params] = self::buildWhere($conditions);
        $orderBy = self::buildOrderBy($order);
        $limitClause = self::buildLimit($limit, $offset);
        $rows = App::$db->all(
            sprintf('SELECT * FROM `%s`%s%s%s', static::tableName(), $where, $orderBy, $limitClause),
            $params
        );

        return $rows;
    }

    /**
     * @return bool
     * @throws ModelConfigurationException
     */
    public function save(): bool
    {
        $columns = static::columns();
        $primaryProperty = null;
        $primaryColumn = null;
        $values = [];

        foreach ($columns as $propertyName => $column) {
            $property = new \ReflectionProperty($this, $propertyName);

            if ($column->primary) {
                $primaryProperty = $property;
                $primaryColumn = $column;
                continue;
            }

            $values[$column->name] = $property->isInitialized($this)
                ? self::normalizeValue($property->getValue($this), $column)
                : null;
        }

        if ($primaryProperty === null || $primaryColumn === null) {
            throw new ModelConfigurationException(sprintf('Primary column is not configured for model %s.', static::class));
        }

        $id = $primaryProperty->isInitialized($this) === true ? $primaryProperty->getValue($this) : null;

        if ($id === null) {
            $columnNames = array_keys($values);
            $placeholders = array_map(static fn (string $name): string => ':_p_' . $name, $columnNames);
            $lastInsertId = App::$db->insert(
                sprintf(
                    'INSERT INTO `%s` (`%s`) VALUES (%s)',
                    static::tableName(),
                    implode('`, `', $columnNames),
                    implode(', ', $placeholders)
                ),
                self::prefixParams($values)
            );

            $primaryProperty->setValue($this, $lastInsertId);

            return true;
        }

        $assignments = array_map(
            static fn (string $name): string => sprintf('`%s` = :_p_%s', $name, $name),
            array_keys($values)
        );
        $values[$primaryColumn->name] = $id;

        App::$db->query(
            sprintf(
                'UPDATE `%s` SET %s WHERE `%s` = :_p_%s',
                static::tableName(),
                implode(', ', $assignments),
                $primaryColumn->name,
                $primaryColumn->name
            ),
            self::prefixParams($values)
        );

        return true;
    }

    /**
     * @param array $data
     * @return static
     */
    public static function fromArray(array $data): static
    {
        $model = new static();
        foreach (static::columns() as $propertyName => $column) {
            if (array_key_exists($column->name, $data)) {
                $property = new \ReflectionProperty($model, $propertyName);
                $property->setValue($model, self::castValue($data[$column->name], $column));
            }
        }

        return $model;
    }

    /**
     * @return string
     * @throws ModelConfigurationException
     */
    protected static function tableName(): string
    {
        $attributes = (new \ReflectionClass(static::class))->getAttributes(Table::class);
        if ($attributes === []) {
            throw new ModelConfigurationException(sprintf('Table attribute is missing for model %s.', static::class));
        }

        return $attributes[0]->newInstance()->name;
    }

    /**
     * @return array
     */
    protected static function columns(): array
    {
        $columns = [];

        foreach ((new \ReflectionClass(static::class))->getProperties() as $property) {
            $attributes = $property->getAttributes(Column::class);
            if ($attributes === []) {
                continue;
            }

            $columns[$property->getName()] = $attributes[0]->newInstance();
        }

        return $columns;
    }

    /**
     * @param mixed $value
     * @param Column $column
     * @return mixed
     */
    private static function castValue(mixed $value, Column $column): mixed
    {
        return $column->castValue($value);
    }

    /**
     * @param array $conditions
     * @return array
     */
    private static function buildWhere(array $conditions): array
    {
        if ($conditions === []) {
            return ['', []];
        }

        $parts = [];
        $params = [];

        foreach ($conditions as $column => $value) {
            $paramName = '_p_' . $column;
            $parts[] = sprintf('`%s` = :%s', $column, $paramName);
            $params[$paramName] = $value;
        }

        return [' WHERE ' . implode(' AND ', $parts), $params];
    }

    /**
     * @param array $order
     * @return string
     */
    private static function buildOrderBy(array $order): string
    {
        if ($order === []) {
            return '';
        }

        $parts = [];

        foreach ($order as $column => $direction) {
            $parts[] = sprintf('`%s` %s', $column, strtoupper((string) $direction) === 'DESC' ? 'DESC' : 'ASC');
        }

        return ' ORDER BY ' . implode(', ', $parts);
    }

    /**
     * @param int|null $limit
     * @param int $offset
     * @return string
     */
    private static function buildLimit(?int $limit, int $offset): string
    {
        if ($limit === null) {
            return '';
        }

        return sprintf(' LIMIT %d OFFSET %d', $limit, $offset);
    }

    /**
     * @param array $params
     * @return array
     */
    private static function prefixParams(array $params): array
    {
        $prefixed = [];

        foreach ($params as $name => $value) {
            $prefixed['_p_' . $name] = $value;
        }

        return $prefixed;
    }

    /**
     * @param mixed $value
     * @param Column $column
     * @return mixed
     */
    private static function normalizeValue(mixed $value, Column $column): mixed
    {
        if ($value === null) {
            return null;
        }

        if ($column->type === \App\Enum\ColumnType::Timestamp && $value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }

        return $value;
    }
}
