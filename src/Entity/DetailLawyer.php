<?php

declare(strict_types=1);

namespace SomeWork\Minjust\Entity;

/**
 * @see \SomeWork\Minjust\Tests\Unit\Entity\DetailLawyerTest
 */
class DetailLawyer extends Lawyer
{
    /**
     * @var string
     */
    protected $chamberOfLaw = '';

    /**
     * @var LawFormation|null
     */
    protected $lawFormation;

    public function __construct(?Lawyer $lawyer = null)
    {
        if ($lawyer !== null) {
            $this->loadFromLawyer($lawyer);
        }
    }

    public function loadFromLawyer(Lawyer $lawyer): self
    {
        return $this
            ->setRegisterNumber($lawyer->getRegisterNumber())
            ->setFullName($lawyer->getFullName())
            ->setUrl($lawyer->getUrl())
            ->setLocation($lawyer->getLocation())
            ->setCertificateNumber($lawyer->getCertificateNumber())
            ->setStatus($lawyer->getStatus());
    }

    /**
     * @return string
     */
    public function getChamberOfLaw(): string
    {
        return $this->chamberOfLaw ?: '';
    }

    /**
     * @param string $chamberOfLaw
     *
     * @return DetailLawyer
     */
    public function setChamberOfLaw(string $chamberOfLaw): DetailLawyer
    {
        $this->chamberOfLaw = $chamberOfLaw;

        return $this;
    }

    /**
     * @return LawFormation|null
     */
    public function getLawFormation(): ?LawFormation
    {
        return $this->lawFormation;
    }

    /**
     * @param LawFormation|null $lawFormation
     *
     * @return DetailLawyer
     */
    public function setLawFormation(?LawFormation $lawFormation): DetailLawyer
    {
        $this->lawFormation = $lawFormation;

        return $this;
    }
}
