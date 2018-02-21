<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExpiredPassport
 *
 * @ORM\Table(name="expired_passport")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ExpiredPassport")
 */
class ExpiredPassport
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="series", type="integer", nullable=false)
     */
    private $series;

    /**
     * @var integer
     *
     * @ORM\Column(name="number", type="integer", nullable=false)
     */
    private $number;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set series
     *
     * @param integer $series
     *
     * @return ExpiredPassport
     */
    public function setSeries($series)
    {
        $this->series = $series;

        return $this;
    }

    /**
     * Get series
     *
     * @return integer
     */
    public function getSeries()
    {
        return $this->series;
    }

    /**
     * Set number
     *
     * @param integer $number
     *
     * @return ExpiredPassport
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }
}
