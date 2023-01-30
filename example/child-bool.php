<?php

declare(strict_types=1);

require \dirname(__DIR__) . '/vendor/autoload.php';

use SharedState\SharedState;

echo 'Starting child...';

try {
    $state1 = SharedState::forId('id-bool', new DateTimeImmutable());
    if ($state1->get() === null) {
        $state1->set((bool)random_int(0, 1));
    }
} catch (Exception $e) {
}

echo 'Finished. Value: ' . $state1->get() ? 'true' : 'false';
