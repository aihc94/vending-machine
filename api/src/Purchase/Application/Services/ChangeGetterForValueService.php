<?php

declare(strict_types=1);

namespace App\Purchase\Application\Services;

use App\Purchase\Application\Queries\FindAllChangeQuery;
use App\Purchase\Domain\Entities\ChangeCollection;
use App\Purchase\Domain\Exceptions\ChangeNotAvailableForAmountException;

class ChangeGetterForValueService
{
    public function __construct(
        private FindAllChangeQuery $changeQuery,
    ) {}

    public function getChangeForValue(
        float $amountToReturn
    ): array
    {
        $changeStock = $this->changeQuery->execute();

        $amountToReturn = round($amountToReturn, 2);
        $remaining = (int) round($amountToReturn * 100);

        $coins = [];
        foreach ($changeStock->all() as $change) {
            $coins[] = [
                'amount' => $change->amount(),
                'amount_cents' => (int) round($change->amount() * 100),
                'quantity' => $change->quantity(),
            ];
        }

        usort($coins, function ($a, $b) {
            return $b['amount_cents'] <=> $a['amount_cents'];
        });

        $totalAvailable = 0;
        foreach ($coins as $coin) {
            $totalAvailable += $coin['amount_cents'] * $coin['quantity'];
        }

        if ($totalAvailable < $remaining) {
            throw new ChangeNotAvailableForAmountException('Not money enough to return');
        }

        $result = [];

        foreach ($coins as $coin) {
            $coinValue = $coin['amount_cents'];
            $maxAvailable = $coin['quantity'];
    
            if ($coinValue <= 0) continue;
    
            $maxNeeded = intdiv($remaining, $coinValue);
            $fairLimit = (int) ceil($maxAvailable * 0.6);
    
            $use = min($maxNeeded, $maxAvailable, $fairLimit);
    
            if ($use > 0) {
                $result[$coinValue] = [
                    'amount' => $coin['amount'],
                    'used' => $use
                ];
    
                $remaining -= $use * $coinValue;
            }
        }

        if ($remaining > 0) {
            foreach ($coins as $coin) {
                $coinValue = $coin['amount_cents'];
                $maxAvailable = $coin['quantity'];
    
                $alreadyUsed = $result[$coinValue]['used'] ?? 0;
                $remainingStock = $maxAvailable - $alreadyUsed;
    
                if ($remainingStock <= 0) continue;
    
                $maxNeeded = intdiv($remaining, $coinValue);
                $use = min($maxNeeded, $remainingStock);
    
                if ($use > 0) {
                    if (!isset($result[$coinValue])) {
                        $result[$coinValue] = [
                            'amount' => $coin['amount'],
                            'used' => 0
                        ];
                    }
    
                    $result[$coinValue]['used'] += $use;
                    $remaining -= $use * $coinValue;
                }
    
                if ($remaining === 0) break;
            }
        }

        if ($remaining !== 0) {
            throw new ChangeNotAvailableForAmountException('Fatal error money not possible to return');
        }
    
        $changeToReturn = [];
    
        foreach ($result as $coin) {
            $changeToReturn[] = [
                'amount' => $coin['amount'],
                'quantityUsed' => $coin['used']
            ];
        }

        return $changeToReturn;
    }
}