<?php

namespace Tests\Support\LIb;

use Stringable;

class FormattedDataSet implements Stringable
{
    public const FORMAT = '%s=%s';

    public const DELIMITER = ', ';

    /**
     * @var array<string, list{string, ?string}>
     */
    protected array $entries;

    public function __construct(
        protected string $format = self::FORMAT,
        protected string $delimiter = self::DELIMITER
    ) {
        //
    }

    /**
     * @return $this
     */
    public function __call($key, $args): static
    {
        return $this->set($key, $args[0]);
    }

    public function __toString(): string
    {
        return $this->get();
    }

    /**
     * @return $this
     */
    public function set(string $key, string|int|bool|null $val): static
    {
        $this->entries[$key] = [$key, match (true) {
            is_string($val) && str_contains($val, ' ') => "'{$val}'",
            is_bool($val) => $val ? 'true' : 'false',
            is_int($val) => strval($val),
            default => $val
        }];

        return $this;
    }

    public function get(): string
    {
        return implode($this->delimiter, array_map(
            fn ($data) => sprintf($this->format, ...$data),
            array_filter($this->entries, fn ($data) => isset($data[1]))
        ));
    }

    public function method(string $method): static
    {
        return $this->set('method', "{$method}()");
    }

    public static function __callStatic($name, $arguments): static
    {
        return (new static())->set($name, $arguments[0]);
    }
}
