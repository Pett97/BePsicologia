<?php

namespace App\Models;

use Core\Database\Database;
use Lib\Paginator;

class Car
{
    //const DB_PATH  = '/var/www/database/cars.txt';
    private string $name = "";

    /**
     * @var array<string, string>
     */
    private array $errors = [];

    public function __construct(private int $id = -1, string $name = "")
    {
        $this->name = strtoupper($name);
    }

    public function setName(string $newName): void
    {
        $this->name = strtoupper($newName);
    }

    public function getName(): string
    {
        return $this->name;
    }


    public function setID(int $newID): void
    {
        $this->id = $newID;
    }

    public function getID(): int
    {
        return $this->id;
    }

    public function hasErrors(): bool
    {
        $this->isValid();
        if (empty($this->errors)) {
            return false;
        }
        return true;
    }
    /**
     * @return array<int, Car>
     */
    public static function all(): array
    {
        $cars = [];
        $pdo = Database::getDatabaseConn();
        $resp = $pdo->query("SELECT id,name FROM cars");
        foreach ($resp as $row) {
            $cars[] = new Car(id: $row["id"], name: $row["name"]);
        }
        return $cars;
    }

    public static function findByID(int $id): Car|null
    {
        $pdo = Database::getDatabaseConn();
        $sql = "SELECT id,name FROM cars WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $id);

        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            return null;
        }

        $row = $stmt->fetch();

        return new Car(id: $row["id"], name: $row["name"]);
    }

    public function save(): bool
    {
        if ($this->isValid()) {
            $pdo = Database::getDatabaseConn();
            if ($this->newRecord()) {
                $sql = "INSERT INTO cars (name) VALUES (:name)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":name", $this->name);

                $stmt->execute();
            } else {
                //for update Car
                $sql = "UPDATE cars set name = :name WHERE id = :id";
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

    public function destroy(): bool
    {
        $pdo = Database::getDatabaseConn();
        $sql = "DELETE FROM cars WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        return ($stmt->rowCount() !== 0);
    }

    //private

    private function addErro(string $text): void
    {
        $this->errors[] = $text;
    }

    private function isValid(): bool
    {
        $this->errors = [];

        if (empty($this->getName())) {
            $this->addErro("Nome do Carro Não pode ser Vazio");
        }
        return empty($this->errors);
    }

    public static function paginate(int $page, int $per_page): Paginator
    {
        return new Paginator(
            class:Car::class,
            page:$page,
            per_page:$per_page,
            table:'cars',
            attributes:["name"]
        );
    }
}
