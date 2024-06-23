<?php

namespace App\Models;

use Core\Database\Database;
use DateInterval;
use DateTime;
use Lib\Paginator;

class Appointment
{
    /**
     * @var array<string, string>
     */
    private array $errors = [];


    public function __construct(
        private int $id = -1,
        private int $userID = 0,
        private DateTime $date = new DateTime("2024-06-15"),
        private DateTime $startHour = new DateTime("11:00:00"),
        private int $periodHours = 4,
        private int $clientID = 0
    ) {
    }

    public function getID(): int
    {
        return $this->id;
    }

    public function destroy(): bool
    {
        try {
            $pdo = Database::getDatabaseConn();
            $sql = "DELETE FROM appointments WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":id", $this->id);
            $stmt->execute();

            return ($stmt->rowCount() !== 0);
        } catch (\PDOException $e) {
            $this->addError("Database error: " . $e->getMessage());
            return false;
        }
    }

    private function addError(string $text): void
    {
        $this->errors[] = $text;
    }



    public function save(): bool
    {
        if ($this->isValid()) {
            try {
                $pdo = Database::getDatabaseConn();
                if ($this->newRecord()) {
                    $sql = 'INSERT INTO appointments (psychologist_id, date, start_time, end_time, client_id) 
                            VALUES (:user, :date, :start_time, :end_time, :client_id)';
                } else {
                    $sql = 'UPDATE appointments 
                            SET psychologist_id = :user, date = :date
                            , start_time = :start_time, end_time = :end_time, client_id = :client_id 
                            WHERE id = :id';
                }
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':user', $this->getUserID());
                $stmt->bindValue(':date', $this->date->format('Y-m-d'));
                $stmt->bindValue(':start_time', $this->startHour->format('H:i:s'));
                $stmt->bindValue(':end_time', $this->getEndDate()->format('H:i:s'));
                $stmt->bindValue(':client_id', $this->getClientID());
                if (!$this->newRecord()) {
                    $stmt->bindParam(':id', $this->id);
                }

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



    public static function paginate(int $page, int $per_page): Paginator
    {
        return new Paginator(
            class: Appointment::class,
            page: $page,
            per_page: $per_page,
            table: 'appointments',
            attributes: ["id"]
        );
    }

    public static function findByID(int $id): ?Appointment
    {
        $pdo = Database::getDatabaseConn();
        $sql = "SELECT id, psychologist_id, date, start_time, end_time, client_id FROM appointments WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            return null;
        }

        $row = $stmt->fetch();

        $startHour = new DateTime($row["start_time"]);
        $endHour = new DateTime($row["end_time"]);
        $interval = $startHour->diff($endHour);
        $hours = $interval->h + ($interval->days * 24) + ($interval->i / 60); // Corrigindo para incluir minutos

        return new Appointment(
            id: $row["id"],
            userID: $row["psychologist_id"],
            date: new DateTime($row["date"]),
            startHour: $startHour,
            periodHours: $hours,
            clientID: $row["client_id"]
        );
    }
}
