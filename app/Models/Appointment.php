<?php

namespace App\Models;

use Core\Database\Database;
use DateInterval;
use DateTime;

class Appointment
{
    /**
     * @var array<string, string>
     */
    private array $errors = [];
    private int $userID;
    private DateTime $date;
    private DateTime $startHour;
    private int $periodHours;
    private int $clientID;

    public function __construct(
        int $userID,
        DateTime $date,
        DateTime $startHour,
        int $periodHours,
        int $clientID
    ) {
        $this->userID = $userID;
        $this->date = $date;
        $this->startHour = $startHour;
        $this->periodHours = $periodHours;
        $this->clientID = $clientID;
    }

    public function save(): bool
    {
        if ($this->isValid()) {
            $pdo = Database::getDatabaseConn();
            $sql = 'INSERT INTO appointments (psychologist_id, date, start_time, end_time, client_id) 
            VALUES (:user, :date, :startHour, :end_time, :client_id)';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':user', $this->getUserID());
            $stmt->bindValue(':date', $this->date->format('Y-m-d'), \PDO::PARAM_STR);
            $stmt->bindValue(':startHour', $this->startHour->format('H:i:s'), \PDO::PARAM_STR);
            $stmt->bindValue(':end_time', $this->getEndDate()->format('H:i:s'), \PDO::PARAM_STR);
            $stmt->bindValue(':client_id', $this->getClientID());

            $stmt->execute();
            return true;
        }
        return false;
    }

    public function isValid(): bool
    {
        $this->errors = [];

        if (empty($this->date)) {
            $this->errors['date'] = 'Precisa informar a Data';
        }
        if (empty($this->periodHours)) {
            $this->errors['periodHours'] = 'Precisa informar a quantidade de Horas';
        }
        if (empty($this->clientID)) {
            $this->errors['client'] = 'Precisa informar o ID do Cliente';
        }

        return empty($this->errors);
    }


    public function hasErrors(): bool
    {
        return empty($this->errors);
    }

    public function errors(string $index): ?string
    {
        return $this->errors[$index] ?? null;
    }

    public function getEndDate(): DateTime
    {
        return $this->dateEnd($this->getPeriodHours());
    }

    private function dateEnd(int $hours): DateTime
    {
        $endTime = clone $this->getStartHour();
        $interval = new DateInterval("PT" . $hours . "H");
        $endTime->add($interval);
        return $endTime;
    }

    public function getPeriodHours(): int
    {
        return $this->periodHours;
    }

    public function setPeriodHours(int $value): void
    {
        $this->periodHours = $value;
    }

    public function getUserID(): int
    {
        return $this->userID;
    }

    public function setUserID(int $value): void
    {
        $this->userID = $value;
    }

    public function getClientID(): int
    {
        return $this->clientID;
    }

    public function setClientID(int $value): void
    {
        $this->clientID = $value;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $value): void
    {
        $this->date = $value;
    }

    public function getStartHour(): DateTime
    {
        return $this->startHour;
    }

    public function setStartHour(DateTime $value): void
    {
        $this->startHour = $value;
    }
}
