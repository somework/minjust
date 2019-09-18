<?php

namespace SomeWork\Minjust\Entity;

class FullLawyer extends Lawyer
{
    /**
     * @var string
     */
    protected $chamberOfLaw;

    /**
     * @var \SomeWork\Minjust\Entity\LawFormation|null
     */
    protected $lawFormation;

    public static function init(Lawyer $lawyer): FullLawyer
    {
        return (new self())
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
     * @return FullLawyer
     */
    public function setChamberOfLaw(string $chamberOfLaw): FullLawyer
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
     * @return FullLawyer
     */
    public function setLawFormation(?LawFormation $lawFormation): FullLawyer
    {
        $this->lawFormation = $lawFormation;

        return $this;
    }
}
