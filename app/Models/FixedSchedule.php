<?php

namespace App\Models;

use Core\Database\Database;
use DateTime;

class FixedSchedule
{
    /**
     * @var array<string, string>
     */
    private array $errors = [];
    private int $userID;
    private int $dayOFWeek;
    private DateTime $startTime;
    private DateTime $endTime;

    public function __construct(
        int $userID,
        int $dayOFWeek,
        DateTime $startTime,
        DateTime $endTime,
    ) {
        $this->userID = $userID;
        $this->dayOFWeek = $dayOFWeek;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }

    public function isValid(): bool
    {
        $this->errors = [];

        if (empty($this->userID)) {
            $this->errors["userID"] = "Precisa Informar o Psicologo";
        }
        if (empty($this->dayOFWeek)) {
            $this->errors["dayOFWeek"] = "Precisa Informar o DIA";
        }
        if (empty($this->startTime)) {
            $this->errors["startTime"] = "Precisa Informar o Horário de Início";
        }
        if (empty($this->endTime)) {
            $this->errors["endTime"] = "Precisa Informar o Horário de Fim";
        }
        return empty($this->errors);
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function errors(string $index): ?string
    {
        return $this->errors[$index] ?? null;
    }

    public function save(): bool
    {
        if ($this->isValid()) {
            $pdo = Database::getDatabaseConn();
            $sql = 'INSERT INTO fixed_schedules (psychologist_id, day_of_week, start_time, end_time) 
            VALUES (:user, :day_of_week, :start_time, :end_time)';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':user', $this->getUserID(), \PDO::PARAM_INT);
            $stmt->bindValue(':day_of_week', $this->getDayOFWeek(), \PDO::PARAM_INT);
            $stmt->bindValue(':start_time', $this->getStartTime()->format('H:i:s'), \PDO::PARAM_STR);
            $stmt->bindValue(':end_time', $this->getEndTime()->format('H:i:s'), \PDO::PARAM_STR);
            $stmt->execute();
            return true;
        }
        return false;
    }

    public function getUserID(): int
    {
        return $this->userID;
    }

    public function setUserID(int $value): void
    {
        $this->userID = $value;
    }

    public function getDayOFWeek(): int
    {
        return $this->dayOFWeek;
    }

    public function setDayOFWeek(int $value): void
    {
        $this->dayOFWeek = $value;
    }

    public function getStartTime(): DateTime
    {
        return $this->startTime;
    }

    public function setStartTime(DateTime $value): void
    {
        $this->startTime = $value;
    }

    public function getEndTime(): DateTime
    {
        return $this->endTime;
    }

    public function setEndTime(DateTime $value): void
    {
        $this->endTime = $value;
    }
}
