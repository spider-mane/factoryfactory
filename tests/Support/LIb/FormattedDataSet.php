<?php

namespace Tests\Support\LIb;

use Stringable;

class FormattedDataSet implements Stringable
{
    /**
     * @var array<string, list{string, string}>
     */
    protected array $entries;

    public function __construct(
        protected string $format = '%s=%s',
        protected string $delimiter = ', '
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
    public function set(string $key, string $val): static
    {
        $this->entries[$key] = [$key, $val];

        return $this;
    }

    public function get(): string
    {
        return implode($this->delimiter, array_map(
            fn ($data) => sprintf($this->format, ...$data),
            $this->entries
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
