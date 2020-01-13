<?php

namespace SomeWork\Minjust;

use InvalidArgumentException;

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
    public const TERRITORIAL_SUBJECT = 'regCode';

    /**
     * @var string
     */
    public const MAX = 'max';

    /**
     * @var string
     */
    public const OFFSET = 'offset';

    /**
     * @var int
     */
    public const MAX_VALUE_MAX = 100;

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
    protected $max = FindRequest::MAX_VALUE_MAX;

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
        if ($max < 1) {
            throw new InvalidArgumentException(
                sprintf('Minimum value for "%s" is %s', static::MAX, 1)
            );
        }
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
        if ($offset < 0) {
            throw new InvalidArgumentException(
                sprintf('Minimum value for "%s" is %s', static::OFFSET, 0)
            );
        }
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
