<?php

declare(strict_types=1);

namespace App\Purchase\Infrastructure\Persistence\MongoDb\Models;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: "purchase_history")]
#[ODM\Index(keys: ['identifier' => 'asc'])]
class PurchaseHistory
{
    #[ODM\Id]
    private ?string $id = null;

    #[ODM\Field(type: "string")]
    private string $identifier;

    #[ODM\Field(type: "string")]
    private string $action;

    #[ODM\Field(type: "float")]
    private float $amount;

    #[ODM\Field(type: "string")]
    private string $currency;

    #[ODM\Field(type: "date")]
    private \DateTime $createdAt;

    #[ODM\Field(type: "date")]
    private \DateTime $updatedAt;

    public function __construct(
        string $identifier,
        string $action,
        float $amount,
        string $currency,
        \DateTime $createdAt,
        \DateTime $updatedAt,
    ) {
        $this->identifier = $identifier;
        $this->action = $action;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function identifier(): string
    {
        return $this->identifier;
    }

    public function action(): string
    {
        return $this->action;
    }

    public function amount(): float
    {
        return $this->amount;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function createdAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function updatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}