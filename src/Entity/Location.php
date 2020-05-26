<?php

namespace SomeWork\Minjust\Entity;

class Location
{
    /**
     * @var string
     */
    protected $id = '';

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return Location
     */
    public function setId(string $id): Location
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Location
     */
    public function setName(string $name): Location
    {
        $this->name = $name;

        return $this;
    }
}
