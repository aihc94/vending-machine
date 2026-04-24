<?php

declare(strict_types=1);

namespace App\StockManager\Domain\Contracts;

interface StockManagerService
{
    public function obtainMachineStock(): array;

    public function updateProductStock(
        string $code,
        string $name,
        float $price,
        int $quantity,
    ): void;
    
    public function updateChangeStock(
        float $amount,
        int $quantity,
    ): void;
}