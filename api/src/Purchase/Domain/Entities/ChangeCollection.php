<?php

declare(strict_types=1);

namespace App\Purchase\Domain\Entities;

class ChangeCollection
{
    private array $changeArray;

    public function __construct(array $changeArray) {
        foreach ($changeArray as $change) {
            if (!$change instanceof Change) {
                throw new \InvalidArgumentException();
            }
        }

        $this->changeArray = $changeArray;
    }
    
    /** @return Change[] */
    public function all(): array {
        return $this->changeArray;
    }
}