<?php

declare(strict_types=1);

namespace App\Model;

use App\Attribute\Column;
use App\Attribute\Table;
use App\Core\Model\AbstractModel;
use App\Enum\ColumnType;

#[Table('users')]
final class User extends AbstractModel
{
    #[Column('id', type: ColumnType::Integer, primary: true)]
    public ?int $id = null;

    #[Column('email', required: true)]
    public string $email;

    #[Column('password_hash', required: true)]
    public string $passwordHash;

    #[Column('username', required: true)]
    public string $username;

    #[Column('is_admin', type: ColumnType::Boolean)]
    public bool $isAdmin = false;

    #[Column('email_verified_at', type: ColumnType::Timestamp, nullable: true)]
    public ?\DateTimeImmutable $emailVerifiedAt = null;

    #[Column('verification_token', nullable: true)]
    public ?string $verificationToken = null;
}
