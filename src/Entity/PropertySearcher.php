<?php


namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;


class PropertySearcher
{
    /**
     * @var int|null
     * @Assert\Range (min=10, max=400)
     */
    private $maxPrice;

    /**
     * @var int|null
     */
    private $minSurface;

    /**
     * @return int|null
     */
    public function getMaxPrice(): ?int
    {
        return $this->maxPrice;
    }

    /**
     * @param int|null $maxPrice
     */
    public function setMaxPrice(int $maxPrice): PropertySearcher
    {
        $this->maxPrice = $maxPrice;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMinSurface(): ?int
    {
        return $this->minSurface;
    }

    /**
     * @param int|null $minSurface
     */
    public function setMinSurface(int $minSurface): PropertySearcher
    {
        $this->minSurface = $minSurface;
        return $this;
    }

}