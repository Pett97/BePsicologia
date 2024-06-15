<?php

namespace App\Models;

use Core\Database\Database;

class Insurance
{
    //cons dbPath  = '/var/www/database/brand.txt';

    public string $name = "";

    /**
     * @var array<string, string>
     */
    private array $errors = [];

    public function __construct(private int $id = -1, string $name = "")
    {
        $this->name = trim(strtoupper($name));
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

    private function addErro(string $text): void
    {
        $this->errors[] = $text;
    }

    public function hasErrors(): bool
    {
        return empty($this->errors);
    }

    public function save(): bool
    {
        if ($this->isValid()) {
            $pdo = Database::getDatabaseConn();
            if ($this->newRecord()) {
                $sql = "INSERT INTO insurances (name) VALUES (:name)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":name", $this->name);

                $stmt->execute();
            } else {
                $sql = "UPDATE states set name = :name WHERE id = :id";
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
            $this->addErro("Nome Convenio Não Pode ser Vazio");
        }

        return empty($this->errors);
    }
    /**
     * @return array<int, Insurance>
     */
    public static function all(): array
    {
        $insurances = [];
        $pdo = Database::getDatabaseConn();
        $resp = $pdo->query("SELECT name,id FROM insurances");
        foreach ($resp as $row) {
            $insurances[] = new Insurance(name: $row["name"], id: $row["id"]);
        }
        return $insurances;
    }


    public static function findByID(int $id): Insurance|null
    {
        $pdo = Database::getDatabaseConn();
        $sql = "SELECT id,name FROM insurances WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $id);

        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            return null;
        }

        $row = $stmt->fetch();

        return new Insurance(id: $row["id"], name: $row["name"]);
    }

    public function destroy(): bool
    {
        $pdo = Database::getDatabaseConn();
        $sql = "DELETE FROM insurances WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        return ($stmt->rowCount() !== 0);
    }
}
