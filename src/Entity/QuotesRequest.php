<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use \DateTime;

class QuotesRequest
{
    /**
     * @Assert\NotBlank(message="Company symbol is required")
     */
    private ?string $companyCode = null;

    /**
     * @Assert\NotBlank(message="Start date is required")
     * @Assert\Type("DateTimeInterface")
     * @Assert\LessThanOrEqual("now")
     * @Assert\LessThanOrEqual(propertyPath="endDate")
     */
    private ?DateTime $startDate = null;

    /**
     * @Assert\NotBlank(message="End date is required")
     * @Assert\Type("DateTimeInterface")
     * @Assert\LessThanOrEqual("now")
     * @Assert\GreaterThanOrEqual(propertyPath="startDate")
     */
    private ?DateTime $endDate = null;

    /**
     * @Assert\NotBlank(message="Email address is required")
     * @Assert\Email(
     *     message="This email is not valid"
     * )
     */
    private ?string $email = null;

    public function getCompanyCode(): ?string
    {
        return $this->companyCode;
    }

    public function setCompanyCode(string $companySymbol): void
    {
        $this->companyCode = $companySymbol;
    }

    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}
