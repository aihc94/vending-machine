<?php

declare(strict_types=1);

namespace App\Purchase\Domain\Repositories;

use App\Purchase\Domain\Entities\Change;
use App\Purchase\Domain\Entities\ChangeCollection;

interface ChangeRepository
{
    public function persist(Change $change): void;
    public function findByAmount(float $amount): Change;
    public function findAll(): ChangeCollection;
}