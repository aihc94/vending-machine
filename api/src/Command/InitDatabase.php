<?php

namespace App\Command;

use App\Product\Application\Commands\UpdateOrAddProductCommand;
use App\Purchase\Application\Commands\UpdateOrAddChangeCommand;
use App\Product\Domain\Entities\Product;
use App\Purchase\Domain\Entities\Change;
use MongoDB\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:init-database')]
class InitDatabase extends Command
{
    protected static $defaultName = 'app:init-database';

    private UpdateOrAddProductCommand $productCommand;
    private UpdateOrAddChangeCommand $changeCommand;

    public function __construct(
        UpdateOrAddProductCommand $productCommand,
        UpdateOrAddChangeCommand $changeCommand
    ) {
        parent::__construct();

        $this->productCommand = $productCommand;
        $this->changeCommand = $changeCommand;
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int
    {
        $client = new Client('mongodb://admin:notYourProblem@database:27017');
        $db = $client->selectDatabase('vending_machine');

        $collection = $db->selectCollection('product');

        $collection->createIndex(
            ['code' => 1],
            ['unique' => true]
        );

        $productSeed = [
            [
                'code' => 'WT',
                'name' => 'Water super fresh',
                'price' => 0.65,
                'quantity' => 10,
            ],
            [
                'code' => 'JU',
                'name' => 'Sweetest juice',
                'price' => 1.00,
                'quantity' => 10,
            ],
            [
                'code' => 'SO',
                'name' => 'Soda',
                'price' => 1.50,
                'quantity' => 10,
            ],
        ];

        foreach ($productSeed as $product) {
            $this->productCommand->execute(
                $product['code'],
                $product['name'],
                $product['price'],
                $product['quantity'],
            );
        }

        $collection = $db->selectCollection('purchase_history');

        $collection->createIndex(
            ['identifier' => 1]
        );

        $collection = $db->selectCollection('change');

        $collection->createIndex(
            ['amount' => 1],
            ['unique' => true]
        );

        $changeSeed = [
            [
                'amount' => 0.05,
                'currency' => Change::CURRENCY,
                'quantity' => 50,
            ],
            [
                'amount' => 0.10,
                'currency' => Change::CURRENCY,
                'quantity' => 50,
            ],
            [
                'amount' => 0.25,
                'currency' => Change::CURRENCY,
                'quantity' => 50,
            ],
        ];

        foreach ($changeSeed as $change) {
            $this->changeCommand->execute(
                $change['amount'],
                $change['quantity'],
            );
        }

        $output->writeln('Base de datos inicializada correctamente');

        return Command::SUCCESS;
    }
}