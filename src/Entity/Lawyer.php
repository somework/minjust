<?php

namespace BreviManu\Minjust\Entity;

class Lawyer
{
    /**
     * @var string
     */
    protected $fullName;

    /**
     * @var string
     */
    protected $registerNumber;

    /**
     * @var string
     */
    protected $certificateNumber;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $territorialSubject;

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     *
     * @return Lawyer
     */
    public function setFullName(string $fullName): Lawyer
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @return string
     */
    public function getRegisterNumber(): string
    {
        return $this->registerNumber;
    }

    /**
     * @param string $registerNumber
     *
     * @return Lawyer
     */
    public function setRegisterNumber(string $registerNumber): Lawyer
    {
        $this->registerNumber = $registerNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getCertificateNumber(): string
    {
        return $this->certificateNumber;
    }

    /**
     * @param string $certificateNumber
     *
     * @return Lawyer
     */
    public function setCertificateNumber(string $certificateNumber): Lawyer
    {
        $this->certificateNumber = $certificateNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return Lawyer
     */
    public function setStatus(string $status): Lawyer
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getTerritorialSubject(): string
    {
        return $this->territorialSubject;
    }

    /**
     * @param string $territorialSubject
     *
     * @return Lawyer
     */
    public function setTerritorialSubject(string $territorialSubject): Lawyer
    {
        $this->territorialSubject = $territorialSubject;

        return $this;
    }
}
