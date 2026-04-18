<?php

declare(strict_types=1);

namespace App\Model;

use App\Attribute\Column;
use App\Attribute\Table;
use App\Core\Model\AbstractModel;
use App\Enum\ColumnType;

#[Table('tasks')]
final class Task extends AbstractModel
{
    #[Column('id', type: ColumnType::Integer, primary: true)]
    public ?int $id = null;

    #[Column('user_id', type: ColumnType::Integer, required: true)]
    public int $userId;

    #[Column('title', required: true)]
    public string $title;

    #[Column('description', type: ColumnType::Text, nullable: true)]
    public ?string $description = null;

    #[Column('is_completed', type: ColumnType::Boolean)]
    public bool $isCompleted = false;

    #[Column('due_at', type: ColumnType::Timestamp, nullable: true)]
    public ?\DateTimeImmutable $dueAt = null;
}
