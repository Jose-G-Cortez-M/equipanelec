<?php

declare(strict_types=1);

namespace App\Entity;


class User
{
    protected ?string $id;

    protected ?string $cedula;

    protected ?string $nombre;

    protected array $roles;

    protected string $email;

    protected ?string $telefono;

    protected string $password;

    protected ?string $sueldo;



}