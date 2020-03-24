<?php

namespace SomeWork\Minjust;

/**
 * @see \SomeWork\Minjust\Tests\Unit\FindRequestTest
 */
class FindRequest
{
    /**
     * @var string
     */
    public const FULL_NAME = 'fullName';

    /**
     * @var string
     */
    public const REGISTER_NUMBER = 'registerNumber';

    /**
     * @var string
     */
    public const CERTIFICATE_NUMBER = 'identityCard';

    /**
     * @var string
     */
    public const STATUS = 'status';

    /**
     * @var string
     */
    public const FORM_OF_LEGAL_PRACTICE = 'orgForm';

    /**
     * @var string
     */
    public const TERRITORIAL_SUBJECT = 'countryCode';

    /**
     * @var string
     */
    public const PAGE = 'page';

    /**
     * @var string
     */
    protected $fullName = '';

    /**
     * @var string
     */
    protected $registerNumber = '';

    /**
     * @var string
     */
    protected $certificateNumber = '';

    /**
     * @var int|null
     */
    protected $status;

    /**
     * @var int|null
     */
    protected $formOfLegalPractice;

    /**
     * @var string|null
     */
    protected $territorialSubject;

    /**
     * @var bool
     */
    protected $fullData = false;

    /**
     * @var int
     */
    protected $page = 1;

    public function getFormData(): array
    {
        return [
            self::FULL_NAME              => $this->getFullName(),
            self::REGISTER_NUMBER        => $this->getRegisterNumber(),
            self::CERTIFICATE_NUMBER     => $this->getCertificateNumber(),
            self::STATUS                 => $this->getStatus(),
            self::FORM_OF_LEGAL_PRACTICE => $this->getFormOfLegalPractice(),
            self::TERRITORIAL_SUBJECT    => $this->getTerritorialSubject(),
            self::PAGE                   => $this->getPage() - 1,
        ];
    }

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
     * @return FindRequest
     */
    public function setFullName(string $fullName): FindRequest
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
     * @return FindRequest
     */
    public function setRegisterNumber(string $registerNumber): FindRequest
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
     * @return FindRequest
     */
    public function setCertificateNumber(string $certificateNumber): FindRequest
    {
        $this->certificateNumber = $certificateNumber;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @param int|null $status
     *
     * @return FindRequest
     */
    public function setStatus(?int $status): FindRequest
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getFormOfLegalPractice(): ?int
    {
        return $this->formOfLegalPractice;
    }

    /**
     * @param int|null $formOfLegalPractice
     *
     * @return FindRequest
     */
    public function setFormOfLegalPractice(?int $formOfLegalPractice): FindRequest
    {
        $this->formOfLegalPractice = $formOfLegalPractice;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTerritorialSubject(): ?string
    {
        return $this->territorialSubject;
    }

    /**
     * @param string|null $territorialSubject
     *
     * @return FindRequest
     */
    public function setTerritorialSubject(?string $territorialSubject): FindRequest
    {
        $this->territorialSubject = $territorialSubject;

        return $this;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     *
     * @return FindRequest
     */
    public function setPage(int $page): FindRequest
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFullData(): bool
    {
        return $this->fullData;
    }

    /**
     * @param bool $fullData
     *
     * @return FindRequest
     */
    public function setFullData(bool $fullData = true): FindRequest
    {
        $this->fullData = $fullData;

        return $this;
    }
}
