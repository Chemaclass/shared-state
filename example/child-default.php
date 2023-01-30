<?php

declare(strict_types=1);

require \dirname(__DIR__) . '/vendor/autoload.php';

use SharedState\SharedState;

echo 'Starting child (id-default)...';

$state = SharedState::forId('id-default');
if ($state->get() === null) {
    $state->set('random: ' . mt_rand());
}

echo 'Finished. Value: ' . $state->get();
