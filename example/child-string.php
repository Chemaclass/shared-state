<?php

declare(strict_types=1);

require \dirname(__DIR__) . '/vendor/autoload.php';

use SharedState\SharedState;

echo 'Starting child (id-string)...';

$state = SharedState::forId('id-string');
if ($state->get() === null) {
    $state->set('something random: ' . mt_rand());
}

echo 'Finished. Value: ' . $state->get();
