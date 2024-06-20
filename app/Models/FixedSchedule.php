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
        private int $userID =0,
        private int $dayOFWeek=0,
        private DateTime $startTime= new \DateTime("2024-06-15 17:00:00"),
        private DateTime $endTime = new \DateTime("2024-06-15 17:00:00")
    ) {

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
            $pdo = Database::getDatabaseConn();
            $sql = 'INSERT INTO fixed_schedules (psychologist_id, day_of_week, start_time, end_time) 
            VALUES (:user, :day_of_week, :start_time, :end_time)';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':user', $this->getUserID());
            $stmt->bindValue(':day_of_week', $this->getDayOFWeek());
            $stmt->bindValue(':start_time', $this->getStartTime()->format('H:i:s'));
            $stmt->bindValue(':end_time', $this->getEndTime()->format('H:i:s'));
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
            attributes: ["id"]
        );
    }
}
