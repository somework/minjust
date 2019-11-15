<?php

declare(strict_types=1);

namespace SomeWork\Minjust\Entity;

/**
 * @see \SomeWork\Minjust\Tests\Unit\Entity\LawFormationTest
 */
class LawFormation
{
    /**
     * @var string
     */
    protected $organizationalForm = '';

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $address = '';

    /**
     * @var string
     */
    protected $phone = '';

    /**
     * @var string
     */
    protected $email = '';

    /**
     * @return string
     */
    public function getOrganizationalForm(): string
    {
        return $this->organizationalForm ?: '';
    }

    /**
     * @param string $organizationalForm
     *
     * @return LawFormation
     */
    public function setOrganizationalForm(string $organizationalForm): LawFormation
    {
        $this->organizationalForm = $organizationalForm;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name ?: '';
    }

    /**
     * @param string $name
     *
     * @return LawFormation
     */
    public function setName(string $name): LawFormation
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address ?: '';
    }

    /**
     * @param string $address
     *
     * @return LawFormation
     */
    public function setAddress(string $address): LawFormation
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone ?: '';
    }

    /**
     * @param string $phone
     *
     * @return LawFormation
     */
    public function setPhone(string $phone): LawFormation
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email ?: '';
    }

    /**
     * @param string $email
     *
     * @return LawFormation
     */
    public function setEmail(string $email): LawFormation
    {
        $this->email = $email;

        return $this;
    }
}
