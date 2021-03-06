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

namespace LaravelJsonApi\Core\Contracts\Document;

use LaravelJsonApi\Core\Contracts\Serializable;
use LaravelJsonApi\Core\Document\JsonApi;
use LaravelJsonApi\Core\Document\Links;
use LaravelJsonApi\Core\Json\Hash;

interface DataDocument extends Serializable
{

    /**
     * Get the data member.
     *
     * @return mixed|iterable|null
     */
    public function data();

    /**
     * Get the JSON API member.
     *
     * @return JsonApi
     */
    public function jsonApi(): JsonApi;

    /**
     * Get the top-level links member.
     *
     * @return Links
     */
    public function links(): Links;

    /**
     * Get the top-level meta member.
     *
     * @return Hash
     */
    public function meta(): Hash;
}
