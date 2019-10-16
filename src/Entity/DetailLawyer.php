<?php

namespace SomeWork\Minjust\Entity;

class DetailLawyer extends Lawyer
{
    /**
     * @var string
     */
    protected $chamberOfLaw;

    /**
     * @var \SomeWork\Minjust\Entity\LawFormation|null
     */
    protected $lawFormation;

    public static function init(Lawyer $lawyer): DetailLawyer
    {
        return (new self())
            ->loadFromLawyer($lawyer);
    }

    public function loadFromLawyer(Lawyer $lawyer): self
    {
        return $this
            ->setRegisterNumber($lawyer->getRegisterNumber())
            ->setFullName($lawyer->getFullName())
            ->setUrl($lawyer->getUrl())
            ->setTerritorialSubject($lawyer->getTerritorialSubject())
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
     * @return \SomeWork\Minjust\Entity\LawFormation|null
     */
    public function getLawFormation(): ?LawFormation
    {
        return $this->lawFormation;
    }

    /**
     * @param \SomeWork\Minjust\Entity\LawFormation|null $lawFormation
     *
     * @return DetailLawyer
     */
    public function setLawFormation(?LawFormation $lawFormation): DetailLawyer
    {
        $this->lawFormation = $lawFormation;

        return $this;
    }
}
