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

namespace LaravelJsonApi\Core\Document\ResourceObject;

use LogicException;
use function is_iterable;

class ToMany extends Relation
{

    /**
     * @var iterable|null
     */
    private $data;

    /**
     * @var bool
     */
    private $showData;

    /**
     * ToMany constructor.
     *
     * @param iterable|null $data
     * @param bool $showData
     * @param string $baseUri
     * @param string $fieldName
     */
    public function __construct(?iterable $data, bool $showData, string $baseUri, string $fieldName)
    {
        if (!is_iterable($data) && true === $showData) {
            throw new LogicException('Expecting data to be iterable when show data is true.');
        }

        parent::__construct($baseUri, $fieldName);
        $this->data = $data;
        $this->showData = $showData;
    }

    /**
     * @inheritDoc
     */
    public function data()
    {
        if (is_iterable($this->data)) {
            return $this->data;
        }

        throw new LogicException('Not expecting to show data.');
    }

    /**
     * @inheritDoc
     */
    public function showData(): bool
    {
        return $this->showData;
    }

}
