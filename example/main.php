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

##########
# OUTPUT #
##########
//array:6 [
//  0 => "Starting child (id-bool)...true"
//  1 => "Starting child (id-string)...Finished. Value: something random: 1532820209"
//  2 => "Starting child (id-default)...Finished. Value: random: 429732778"
//  3 => "Starting child (id-bool)...true"
//  4 => "Starting child (id-string)...Finished. Value: something random: 1532820209"
//  5 => "Starting child (id-default)...Finished. Value: random: 429732778"
//]
