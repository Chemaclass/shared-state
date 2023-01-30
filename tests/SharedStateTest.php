<?php

declare(strict_types=1);


use PHPUnit\Framework\TestCase;
use SharedState\SharedState;

final class SharedStateTest extends TestCase
{
    public function setUp(): void
    {
        SharedState::setConfig([
            'id-bool' => [
                'file-name' => 'shared-boolean-state.json',
                'minutes-diff-limit' => 10,
            ],
            'id-string' => [
                'file-name' => 'shared-string-state.json',
            ],
            'id-default' => [],
        ]);

        SharedState::clear('id-bool');
        SharedState::clear('id-string');
        SharedState::clear('id-default');
    }

    public function test_get_is_null_when_nothing_has_been_set(): void
    {
        $state = SharedState::forId('id-bool', new DateTimeImmutable('2023-01-01 10:00:00'));

        self::assertNull($state->get());
    }

    public function test_get_the_setted_value(): void
    {
        $state = SharedState::forId('id-bool', new DateTimeImmutable('2023-01-01 10:00:00'));
        $state->set(true);

        self::assertTrue($state->get());
    }

    public function test_get_the_setted_value_from_another_process(): void
    {
        $state1 = SharedState::forId('id-bool', new DateTimeImmutable('2023-01-01 10:00:00'));
        $state1->set(true);

        $state2 = SharedState::forId('id-bool', new DateTimeImmutable('2023-01-01 10:00:00'));

        self::assertTrue($state2->get());
    }

    public function test_get_old_setted_values_are_ignored(): void
    {
        $state1 = SharedState::forId('id-bool', new DateTimeImmutable('2023-01-01 10:00:00'));
        $state1->set(true);

        $state2 = SharedState::forId('id-bool', new DateTimeImmutable('2023-01-01 10:30:00'));

        self::assertNull($state2->get());
    }

    public function test_clear_removes_the_file(): void
    {
        $state1 = SharedState::forId('id-bool', new DateTimeImmutable('2023-01-01 10:00:00'));
        $state1->set(true);
        SharedState::clear('id-bool');

        $state3 = SharedState::forId('id-bool', new DateTimeImmutable('2023-01-01 10:00:00'));

        self::assertNull($state3->get());
    }

    public function test_get_the_setted_string_value_from_another_process(): void
    {
        $state1 = SharedState::forId('id-bool', new DateTimeImmutable('2023-01-01 10:00:00'));
        $state1->set('any value');

        $state2 = SharedState::forId('id-bool', new DateTimeImmutable('2023-01-01 10:00:00'));

        self::assertSame('any value', $state2->get());
    }

    public function test_default_file_name(): void
    {
        $state1 = SharedState::forId('id-default', new DateTimeImmutable('2023-01-01 10:00:00'));
        $state1->set('anything');

        $state2 = SharedState::forId('id-default', new DateTimeImmutable('2023-01-01 10:00:00'));

        self::assertSame('anything', $state2->get());
    }
}
