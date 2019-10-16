<?php

namespace SomeWork\Minjust;

use InvalidArgumentException;

class FindRequest
{
    public const FULL_NAME = 'lawyername';

    public const REGISTER_NUMBER = 'regnumber';

    public const CERTIFICATE_NUMBER = 'lawicard';

    public const STATUS = 'lawstatus';

    public const FORM_OF_LEGAL_PRACTICE = 'formation';

    public const TERRITORIAL_SUBJECT = 'lawregion';

    public const MAX = 'max';

    public const OFFSET = 'offset';

    private const MAX_VALUE_MAX = 100;

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
     * @var int|null
     */
    protected $territorialSubject;

    protected $fullData = false;

    /**
     * @var int
     */
    protected $max = 50;

    /**
     * @var int
     */
    protected $offset = 0;

    public function getFormData(): array
    {
        return [
            self::FULL_NAME              => $this->getFullName(),
            self::REGISTER_NUMBER        => $this->getRegisterNumber(),
            self::CERTIFICATE_NUMBER     => $this->getCertificateNumber(),
            self::STATUS                 => $this->getStatus(),
            self::FORM_OF_LEGAL_PRACTICE => $this->getFormOfLegalPractice(),
            self::TERRITORIAL_SUBJECT    => $this->getTerritorialSubject(),
            self::MAX                    => $this->getMax(),
            self::OFFSET                 => $this->getOffset(),
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
     * @return int|null
     */
    public function getTerritorialSubject(): ?int
    {
        return $this->territorialSubject;
    }

    /**
     * @param int|null $territorialSubject
     *
     * @return FindRequest
     */
    public function setTerritorialSubject(?int $territorialSubject): FindRequest
    {
        $this->territorialSubject = $territorialSubject;

        return $this;
    }

    /**
     * @return int
     */
    public function getMax(): int
    {
        return $this->max;
    }

    /**
     * @param int $max
     *
     * @return FindRequest
     */
    public function setMax(int $max): FindRequest
    {
        if ($max > static::MAX_VALUE_MAX) {
            throw new InvalidArgumentException(
                sprintf('Maximum value for "%s" is %s', static::MAX, static::MAX_VALUE_MAX)
            );
        }
        $this->max = $max;

        return $this;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     *
     * @return FindRequest
     */
    public function setOffset(int $offset): FindRequest
    {
        $this->offset = $offset;

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
