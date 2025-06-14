<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Drd\Subscribe\Api\Data;

interface ProductSuscriptionConfigInterface
{
    /**
     * @return bool
     */
    public function getIsSubscribable(): bool;

    /**
     * @param bool $value
     * @return self
     */
    public function setIsSubscribable(bool $value): self;

    /**
     * @return array
     */
    public function getFrequencies(): array;

    /**
     * @param array $value
     * @return self
     */
    public function setAssignedPlans(array $value): self;

    /**
     * @return array
     */
    public function getAssignedPlans(): array;

    /**
     * @param array $value
     * @return self
     */
    public function setFrequencies(array $value): self;

    /**
     * @return string
     */
    public function getDefault(): string;

    /**
     * @param string $value
     * @return self
     */
    public function setDefault(string $value): self;
}
