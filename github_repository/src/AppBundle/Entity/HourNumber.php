<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class HourNumber
{
    /**
     * @Assert\NotBlank(message="Zadejte počet hodin!")
     * @Assert\Range(
     *      min = 1,
     *      minMessage = "Minimální hodnota je {{ limit }}",
     * )
     */
    private $HourNumber;

    public function setHourNumber($HourNumber)
    {
        $this->HourNumber = $HourNumber;
    }

    public function getHourNumber()
    {
        return $this->HourNumber;
    }

}