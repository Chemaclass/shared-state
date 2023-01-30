<?php

declare(strict_types=1);

namespace SharedState;

use DateTimeImmutable;

/**
 * Example:
 * {
 *   "value": true,
 *   "timestamp": 123456789
 * }
 */
final class SharedState
{
    private const CONFIG_FILENAME = 'shared-states.php';

    private const DEFAULT_SHARED_STATE_FILE_NAME = 'shared-state.json';
    private const DEFAULT_MINUTES_DIFF_LIMIT = 10;

    /**
     * @var array<string, array{
     *     file-name?: string,
     *     minutes-diff-limit?: int,
     * }>
     */
    private static array $config = [];

    private static string $appRoot = '';

    private function __construct(
        private DateTimeImmutable $now,
        private string $filePath,
        private int $minutesDiffLimit
    ) {
    }

    /**
     * @param array<string, array{
     *     file-name?: string,
     *     minutes-diff-limit?: int,
     * }> $config
     */
    public static function setConfig(array $config): void
    {
        self::$config = $config;
    }

    public static function forId(string $id, ?DateTimeImmutable $now = null): self
    {
        if ($now === null) {
            $now = new DateTimeImmutable();
        }

        if (self::$config === []) {
            self::$config = self::loadSharedStatesConfig();
        }

        return new self(
            $now,
            self::getFilepath($id),
            self::getMinutesDiffLimit($id),
        );
    }

    public static function clear(string $id): void
    {
        $filePath = self::getFilepath($id);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    public static function getFilepath(string $id): string
    {
        if (self::$config === []) {
            self::$config = self::loadSharedStatesConfig();
        }

        $fileName = self::$config[$id]['file-name']
            ?? $id . '-' . self::DEFAULT_SHARED_STATE_FILE_NAME;

        return self::getAppRoot() . '/' . $fileName;
    }

    public static function getMinutesDiffLimit(string $id): int
    {
        return self::$config[$id]['minutes-diff-limit']
            ?? self::DEFAULT_MINUTES_DIFF_LIMIT;
    }

    public static function setAppRoot(string $appRoot): void
    {
        self::$appRoot = $appRoot;
    }

    public function get(): mixed
    {
        if (!file_exists($this->filePath)) {
            return null;
        }

        /** @var array{value?: mixed, timestamp?: int} $content */
        $content = (array)json_decode((string)file_get_contents($this->filePath), true, 512, JSON_THROW_ON_ERROR);
        $timestamp = $content['timestamp'] ?? 0;

        if ($this->isTooOld($timestamp)) {
            return null;
        }

        return $content['value'] ?? null;
    }

    public function set(mixed $value): void
    {
        $content = ['value' => $value, 'timestamp' => $this->now->getTimestamp()];
        file_put_contents($this->filePath, json_encode($content));
    }

    /**
     * @return array<string, array{
     *     file-name?: string,
     *     minutes-diff-limit?: int,
     * }>
     */
    private static function loadSharedStatesConfig(): array
    {
        return require self::getAppRoot() . '/' . self::CONFIG_FILENAME;
    }

    private static function getAppRoot(): string
    {
        if (self::$appRoot === '') {
            self::$appRoot = (string)getcwd();
        }

        return self::$appRoot;
    }

    private function isTooOld(int $timestamp): bool
    {
        $date = (new DateTimeImmutable())->setTimestamp($timestamp);

        return $this->now->diff($date)->i > $this->minutesDiffLimit;
    }
}
