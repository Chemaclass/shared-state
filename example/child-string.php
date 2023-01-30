<?php

declare(strict_types=1);

require \dirname(__DIR__) . '/vendor/autoload.php';

use SharedState\SharedState;

echo 'Starting child...';

$state1 = SharedState::forId('id-string', new DateTimeImmutable());
if ($state1->get() === null) {
    $state1->set('something random: ' . mt_rand());
}
sleep(1);

echo 'Child finished with value: ' . $state1->get();
