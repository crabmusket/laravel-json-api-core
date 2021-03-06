<?php
/**
 * Copyright 2020 Cloud Creativity Limited
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types=1);

namespace LaravelJsonApi\Core\Query;

use Countable;
use Illuminate\Contracts\Support\Arrayable;
use IteratorAggregate;
use UnexpectedValueException;
use function collect;
use function count;
use function explode;
use function is_array;
use function is_string;

class IncludePaths implements IteratorAggregate, Countable, Arrayable
{

    /**
     * @var RelationshipPath[]
     */
    private $stack;

    /**
     * @param IncludePaths|RelationshipPath|array|string $value
     * @return IncludePaths
     */
    public static function cast($value): self
    {
        if ($value instanceof self) {
            return $value;
        }

        if ($value instanceof RelationshipPath) {
            return new self($value);
        }

        if (is_array($value)) {
            return self::fromArray($value);
        }

        if (is_string($value)) {
            return self::fromString($value);
        }

        throw new UnexpectedValueException('Unexpected include path value.');
    }

    /**
     * @param array $paths
     * @return IncludePaths
     */
    public static function fromArray(array $paths): self
    {
        return new self(...collect($paths)->map(function ($path) {
            return RelationshipPath::cast($path);
        }));
    }

    /**
     * @param string $paths
     * @return IncludePaths
     */
    public static function fromString(string $paths): self
    {
        return new self(...collect(explode(',', $paths))->map(function (string $path) {
            return RelationshipPath::fromString($path);
        }));
    }

    /**
     * IncludePaths constructor.
     *
     * @param RelationshipPath ...$paths
     */
    public function __construct(RelationshipPath ...$paths)
    {
        $this->stack = $paths;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return implode(',', $this->stack);
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->stack);
    }

    /**
     * @return bool
     */
    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        yield from $this->stack;
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        return count($this->stack);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return collect($this->stack)->map(function (RelationshipPath $path) {
            return $path->toString();
        })->all();
    }

}
