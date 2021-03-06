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

use Illuminate\Contracts\Support\Arrayable;
use LaravelJsonApi\Core\Support\Arr;
use function http_build_query;
use function is_array;

class QueryParameters implements Arrayable
{

    /**
     * @var IncludePaths|null
     */
    private $includePaths;

    /**
     * @var FieldSets|null
     */
    private $fieldSets;

    /**
     * @var SortFields|null
     */
    private $sort;

    /**
     * @var array|null
     */
    private $pagination;

    /**
     * @var array|null
     */
    private $filters;

    /**
     * @param array $value
     * @return static
     */
    public static function fromArray(array $value): self
    {
        return new self(
            isset($value['include']) ? IncludePaths::fromArray($value['include']) : null,
            isset($value['fields']) ? FieldSets::fromArray($value['fields']) : null,
            isset($value['sort']) ? SortFields::fromArray($value['sort']) : null,
            isset($value['page']) ? Arr::cast($value['page']) : null,
            isset($value['filter']) ? Arr::cast($value['filter']) : null
        );
    }

    /**
     * QueryParameters constructor.
     *
     * @param IncludePaths|null $includePaths
     * @param FieldSets|null $fieldSets
     * @param SortFields|null $sortFields
     * @param array|null $page
     * @param array|null $filters
     */
    public function __construct(
        IncludePaths $includePaths = null,
        FieldSets $fieldSets = null,
        SortFields $sortFields = null,
        array $page = null,
        array $filters = null
    ) {
        $this->includePaths = $includePaths;
        $this->fieldSets = $fieldSets;
        $this->sort = $sortFields;
        $this->pagination = $page;
        $this->filters = $filters;
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
        $query = [];

        if ($this->includePaths && $this->includePaths->isNotEmpty()) {
            $query['include'] = $this->includePaths->toString();
        }

        if ($this->fieldSets && $this->includePaths->isNotEmpty()) {
            $query['fields'] = $this->fieldSets->toArray();
        }

        if ($this->sort && $this->sort->isNotEmpty()) {
            $query['sort'] = $this->sort->toString();
        }

        if (is_array($this->pagination) && !empty($this->pagination)) {
            $query['page'] = $this->pagination;
        }

        if (is_array($this->filters) && !empty($this->pagination)) {
            $query['filter'] = $this->filters;
        }

        return http_build_query($query);
    }

    /**
     * @return IncludePaths|null
     */
    public function includePaths(): ?IncludePaths
    {
        return $this->includePaths;
    }

    /**
     * @param IncludePaths|array|string $paths
     * @return $this
     */
    public function withIncludePaths($paths): self
    {
        $this->includePaths = IncludePaths::cast($paths);

        return $this;
    }

    /**
     * @return $this
     */
    public function withoutIncludePaths(): self
    {
        $this->includePaths = null;

        return $this;
    }

    /**
     * @return FieldSets|null
     */
    public function fields(): ?FieldSets
    {
        return $this->fieldSets;
    }

    /**
     * Set the sparse field sets.
     *
     * @param FieldSets|array $fieldSets
     * @return $this
     */
    public function withSparseFieldSets($fieldSets): self
    {
        $this->fieldSets = FieldSets::cast($fieldSets);

        return $this;
    }

    /**
     * Remove all sparse field sets.
     *
     * @return $this
     */
    public function withoutSparseFieldSets(): self
    {
        $this->fieldSets = null;

        return $this;
    }

    /**
     * Add sparse fields by resource type.
     *
     * @param string $type
     * @param array $fields
     * @return $this
     */
    public function withFieldSet(string $type, array $fields): self
    {
        if (!$this->fieldSets) {
            $this->fieldSets = new FieldSets();
        }

        $this->fieldSets->push(new FieldSet($type, ...$fields));

        return $this;
    }

    /**
     * Remove field sets by resource type.
     *
     * @param string ...$types
     * @return $this
     */
    public function withoutFieldSet(string ...$types): self
    {
        if ($this->fieldSets) {
            $this->fieldSets->forget($types);
        }

        return $this;
    }

    /**
     * @return SortFields|null
     */
    public function sort(): ?SortFields
    {
        return $this->sort;
    }

    /**
     * @param SortFields|array|string $fields
     * @return $this
     */
    public function withSortFields($fields): self
    {
        $this->sort = SortFields::cast($fields);

        return $this;
    }

    /**
     * @return $this
     */
    public function withoutSortFields(): self
    {
        $this->sort = null;

        return $this;
    }

    /**
     * @return array|null
     */
    public function page(): ?array
    {
        return $this->pagination;
    }

    /**
     * Set pagination.
     *
     * @param Arrayable|array $pagination
     * @return $this
     */
    public function withPagination($pagination): self
    {
        $this->pagination = Arr::cast($pagination);

        return $this;
    }

    /**
     * Remove pagination.
     *
     * @return $this
     */
    public function withoutPagination(): self
    {
        $this->pagination = null;

        return $this;
    }

    /**
     * @return array|null
     */
    public function filter(): ?array
    {
        return $this->filters;
    }

    /**
     * Set filters.
     *
     * @param Arrayable|array $filters
     * @return $this
     */
    public function withFilters($filters): self
    {
        $this->filters = Arr::cast($filters);

        return $this;
    }

    /**
     * Remove filters.
     *
     * @return $this
     */
    public function withoutFilters(): self
    {
        $this->filters = null;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        $query = [];

        if ($this->includePaths && $this->includePaths->isNotEmpty()) {
            $query['include'] = $this->includePaths->toArray();
        }

        if ($this->fieldSets && $this->fieldSets->isNotEmpty()) {
            $query['fields'] = $this->fieldSets->toArray();
        }

        if ($this->sort && $this->sort->isNotEmpty()) {
            $query['sort'] = $this->sort->toArray();
        }

        if (is_array($this->pagination) && !empty($this->pagination)) {
            $query['page'] = $this->pagination;
        }

        if (is_array($this->filters) && !empty($this->filters)) {
            $query['filter'] = $this->filters;
        }

        return $query;
    }

}
