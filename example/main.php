<?php

declare(strict_types=1);

use SharedState\SharedState;

require \dirname(__DIR__) . '/vendor/autoload.php';

SharedState::setAppRoot(__DIR__);

exec('php ' . __DIR__ . '/child-bool.php', $output);
exec('php ' . __DIR__ . '/child-string.php', $output);
exec('php ' . __DIR__ . '/child-default.php', $output);
exec('php ' . __DIR__ . '/child-bool.php', $output);
exec('php ' . __DIR__ . '/child-string.php', $output);
exec('php ' . __DIR__ . '/child-default.php', $output);

dump($output);

SharedState::clear('id-bool');
SharedState::clear('id-string');
SharedState::clear('id-default');
