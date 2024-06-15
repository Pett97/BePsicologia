<?php

namespace App\Models;

use Core\Database\Database;

class City
{
    public string $name = "";
    public int $idState = 0;

    /**
     * @var array<string, string>
     */
    private array $errors = [];

    public function __construct(string $name = "", int $idState = 0, private int $id = -1)
    {
        $this->name = trim(strtoupper($name));
        $this->idState = $idState;
    }

    public function setIdState(int $idState): void
    {
        $this->idState = $idState;
    }

    public function getIdState(): int
    {
        return $this->idState;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getID(): int
    {
        return $this->id;
    }

    public function setName(string $newName): void
    {
        $this->name = trim(strtoupper($newName));
    }

    public function getName(): string
    {
        return $this->name;
    }

    private function addError(string $text): void
    {
        $this->errors[] = $text;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function save(): bool
    {
        if ($this->isValid()) {
            $pdo = Database::getDatabaseConn();
            if ($this->newRecord()) {
                $sql = "INSERT INTO citys (name, state_id) VALUES (:name, :state_id)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":name", $this->name);
                $stmt->bindParam(":state_id", $this->idState);
                $stmt->execute();
            } else {
                $sql = "UPDATE citys SET name = :name WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":name", $this->name);
                $stmt->bindParam(":id", $this->id);
                $stmt->execute();
            }
            return true;
        }
        return false;
    }

    public function newRecord(): bool
    {
        return $this->id == -1;
    }

    private function isValid(): bool
    {
        $this->errors = [];

        if (empty($this->getName())) {
            $this->addError("Nome da cidade não pode ser vazio");
        }

        return empty($this->errors);
    }

    /**
     * @return array<int, City>
     */
    public static function all(): array
    {
        $cities = [];
        $pdo = Database::getDatabaseConn();
        $resp = $pdo->query("SELECT id, name, state_id FROM citys");
        foreach ($resp as $row) {
            $cities[] = new City(name: $row["name"], idState: $row["state_id"], id: $row["id"]);
        }
        return $cities;
    }

    public static function findByID(int $id): ?City
    {
        $pdo = Database::getDatabaseConn();
        $sql = "SELECT id, name, state_id FROM citys WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            return null;
        }

        $row = $stmt->fetch();

        return new City(name: $row["name"], idState: $row["state_id"], id: $row["id"]);
    }

    public function destroy(): bool
    {
        $pdo = Database::getDatabaseConn();
        $sql = "DELETE FROM citys WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        return $stmt->rowCount() !== 0;
    }
}
