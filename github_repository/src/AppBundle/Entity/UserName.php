<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_log")
 */
class UserName
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @Assert\NotBlank(message="Uživatelské jméno nesmí být prázdné!")
     * @ORM\Column(name="user_name", type="string")
     */
    private $UserName;

    public function setUserName($UserName)
    {
        $this->UserName = strtolower($UserName);
    }

    public function getUserName()
    {
        return $this->UserName;
    }

     /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $Created;

    public function setCreated()
    {
        $this->Created = new \DateTime("now");
    }

    public function getCreated()
    {
        return $this->Created;
    }

    /**
     * @ORM\Column(name="user_ip", type="string")
     */
    private $Ip;

    public function setIp()
    {
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP))
        {
            $ip = $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP))
        {
            $ip = $forward;
        }
        else
        {
            $ip = $remote;
        }

        $this->Ip = $ip;
    }

    public function getIp()
    {
        return $this->Ip;
    }
}