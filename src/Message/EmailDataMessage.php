<?php

namespace App\Message;

use \DateTime;

class EmailDataMessage
{
    private string $userEmail;
    private DateTime $startDate;
    private DateTime $endDate;
    private string $companyName;

    public function __construct(string $userEmail, string $companyName, DateTime $startDate, DateTime $endDate)
    {
        $this->userEmail = $userEmail;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->companyName = $companyName;
    }

    /**
     * @return string
     */
    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    /**
     * @return DateTime
     */
    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }

    /**
     * @return DateTime
     */
    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    /**
     * @return string
     */
    public function getUserEmail(): string
    {
        return $this->userEmail;
    }
}
