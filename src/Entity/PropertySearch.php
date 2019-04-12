<?php
/**
 * Created by PhpStorm.
 * User: yvano berthol
 * Date: 2/26/2019
 * Time: 9:56 PM
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

class PropertySearch
{
    /**
     * @var int|null
     * @Assert\Range(min="20000000",max="100000000")
     */
    private $maxprice;

    /**
     * @var int|null
     * @Assert\Range(min="10",max="400")
     */
    private $minsurface;

    /**
     * @var ArrayCollection
     */
    private $optiones;

    public function __construct()
    {
        $this->optiones = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getMaxprice(): ?int
    {
        return $this->maxprice;
    }

    /**
     * @param int|null $maxprice
     */
    public function setMaxprice(?int $maxprice): void
    {
        $this->maxprice = $maxprice;
    }

    /**
     * @return int|null
     */
    public function getMinsurface(): ?int
    {
        return $this->minsurface;
    }

    /**
     * @param int|null $minsurface
     */
    public function setMinsurface(?int $minsurface): void
    {
        $this->minsurface = $minsurface;
    }

    /**
     * @return ArrayCollection
     */
    public function getOptiones(): ArrayCollection
    {
        return $this->optiones;
    }

    /**
     * @param ArrayCollection $optiones
     */
    public function setOptiones(ArrayCollection $optiones): void
    {
        $this->optiones = $optiones;
    }



}