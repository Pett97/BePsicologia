<?php

namespace App\Models;

use Core\Database\Database;
use DateTime;
use Lib\Paginator;

class FixedSchedule
{
    /**
     * @var array<string, string>
     */
    private array $errors = [];

    public function __construct(
        private int $id = -1,
        private int $userID = 0,
        private int $dayOFWeek = 3,
        private DateTime|string $startTime = "2024-06-15 09:45:00",
        private DateTime|string $endTime = "2024-06-15 19:47:00"
    ) {
        $this->startTime = $this->startTime instanceof DateTime ? $this->startTime : new DateTime($this->startTime);
        $this->endTime = $this->endTime instanceof DateTime ? $this->endTime : new DateTime($this->endTime);
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

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getID(): int
    {
        return $this->id;
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
            try {
                $pdo = Database::getDatabaseConn();
                if ($this->newRecord()) {
                    $sql = 'INSERT INTO fixed_schedules (psychologist_id, day_of_week, start_time, end_time) 
                VALUES (:user, :day_of_week, :start_time, :end_time)';
                    $stmt = $pdo->prepare($sql);
                } else {
                    $sql = 'UPDATE fixed_schedules SET psychologist_id = :user, day_of_week = :day_of_week, 
                        start_time = :start_time, end_time = :end_time WHERE id = :id';
                    $stmt = $pdo->prepare($sql);
                    $id = $this->id;
                    $stmt->bindParam(':id', $id);
                }
                $userID = $this->getUserID();
                $dayOfWeek = $this->getDayOFWeek();
                $startTime = $this->getStartTime()->format('H:i:s');
                $endTime = $this->getEndTime()->format('H:i:s');

                $stmt->bindParam(':user', $userID);
                $stmt->bindParam(':day_of_week', $dayOfWeek);
                $stmt->bindParam(':start_time', $startTime);
                $stmt->bindParam(':end_time', $endTime);

                $stmt->execute();

                return true;
            } catch (\PDOException $e) {
                $this->errors['database'] = "Database error: " . $e->getMessage();
            }
        }
        return false;
    }


    public function newRecord(): bool
    {
        return $this->id == -1;
    }



    public static function findByID(int $id): ?FixedSchedule
    {
        $pdo = Database::getDatabaseConn();
        $sql = "SELECT id, psychologist_id, day_of_week, start_time, end_time FROM fixed_schedules WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            return null;
        }

        $row = $stmt->fetch();

        return new FixedSchedule(
            id: $row["id"],
            userID: $row["psychologist_id"],
            dayOFWeek: $row["day_of_week"],
            startTime: new \DateTime($row["start_time"]),
            endTime: new \DateTime($row["end_time"])
        );
    }

    public function destroy(): bool
    {
        try {
            $pdo = Database::getDatabaseConn();
            $sql = "DELETE FROM fixed_schedules WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":id", $this->id);
            $stmt->execute();

            return ($stmt->rowCount() !== 0);
        } catch (\PDOException $e) {
            return false;
        }
    }


    public static function paginate(int $page, int $per_page): Paginator
    {
        return new Paginator(
            class: FixedSchedule::class,
            page: $page,
            per_page: $per_page,
            table: 'fixed_schedules',
            attributes: ["psychologist_id", "day_of_week", "start_time","end_time"]
        );
    }
}
