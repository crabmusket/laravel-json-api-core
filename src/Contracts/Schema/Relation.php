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

namespace LaravelJsonApi\Core\Contracts\Schema;

interface Relation extends Field
{
    /**
     * Is this a to-one relation?
     *
     * @return bool
     */
    public function toOne(): bool;

    /**
     * Is this a to-many relation?
     *
     * @return bool
     */
    public function toMany(): bool;

    /**
     * Get the inverse resource type.
     *
     * @return string
     */
    public function inverse(): string;

    /**
     * @return bool
     */
    public function isIncludePath(): bool;

    /**
     * @return bool
     */
    public function isDefaultIncludePath(): bool;

    /**
     * @return bool
     */
    public function hasSelfLink(): bool;

    /**
     * @return bool
     */
    public function hasRelatedLink(): bool;
}
