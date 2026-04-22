<?php

namespace App\Command;

use MongoDB\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitMongoCommand extends Command
{
    protected static $defaultName = 'app:init-database';

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

        $collection->insertMany([
            [
                'username' => 'admin',
                'email' => 'admin@example.com',
                'roles' => ['ROLE_ADMIN'],
                'createdAt' => new \MongoDB\BSON\UTCDateTime()
            ],
            [
                'username' => 'user1',
                'email' => 'user1@example.com',
                'roles' => ['ROLE_USER'],
                'createdAt' => new \MongoDB\BSON\UTCDateTime()
            ]
        ]);

        $output->writeln('Base de datos inicializada correctamente');

        return Command::SUCCESS;
    }
}