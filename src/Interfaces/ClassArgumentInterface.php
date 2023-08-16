<?php

namespace WebTheory\Factory\Interfaces;

interface ClassArgumentInterface
{
    /**
     * @return class-string
     */
    public function getClass(): string;

    /**
     * @return array<string, mixed>
     */
    public function getArgs(): array;
}
