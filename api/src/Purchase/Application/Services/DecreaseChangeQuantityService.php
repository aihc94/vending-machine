<?php

declare(strict_types=1);

namespace App\Purchase\Application\Services;

use App\Purchase\Application\Commands\UpdateOrAddChangeCommand;
use App\Purchase\Application\Queries\FindChangeByAmountQuery;

class DecreaseChangeQuantityService
{
    public function __construct(
        private UpdateOrAddChangeCommand $changeCommand,
        private FindChangeByAmountQuery $query,
    ) {}

    public function execute(array $changeReturned): void
    {
        $this->validate($changeReturned);

        foreach ($changeReturned as $changeDevolutionArray) {
            $change = $this->query->execute((float)$changeDevolutionArray['amount']);

            $this->changeCommand->execute(
                $change->amount(),
                ($change->quantity() - (int)$changeDevolutionArray['quantityUsed'])
            );
        }
    }

    private function validate(array $changeReturned): void
    {
        foreach ($changeReturned as $changeDevolutionArray) {
            if (
                !isset($changeDevolutionArray['amount']) ||
                !isset($changeDevolutionArray['quantityUsed'])
            ) {
                throw new \Exception('Tried to update change with bad format');
            }
        }
    }
}