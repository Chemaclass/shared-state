<?php

declare(strict_types=1);

require \dirname(__DIR__) . '/vendor/autoload.php';

use SharedState\SharedState;

echo 'Starting child (id-bool)...';

try {
    $state = SharedState::forId('id-bool');
    if ($state->get() === null) {
        $state->set((bool)random_int(0, 1));
    }
} catch (Exception $e) {
}

echo 'Finished. Value: ' . $state->get() ? 'true' : 'false';
