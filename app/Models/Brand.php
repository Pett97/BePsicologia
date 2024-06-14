<?php

namespace App\Models;

use Core\Constants\Constants;
use Core\Database\Database;
use Lib\Paginator;

class Brand
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
                $sql = "INSERT INTO brands (name) VALUES (:name)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":name", $this->name);

                $stmt->execute();
            } else {
                $sql = "UPDATE brands set name = :name WHERE id = :id";
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
            $this->addErro("Nome Marca Não Pode ser Vazio");
        }

        return empty($this->errors);
    }
    /**
     * @return array<int, Brand>
     */
    public static function all(): array
    {
        $brands = [];
        $pdo = Database::getDatabaseConn();
        $resp = $pdo->query("SELECT name,id FROM brands");
        foreach ($resp as $row) {
            $brands[] = new Brand(name: $row["name"], id: $row["id"]);
        }
        return $brands;
    }


    public static function findByID(int $id): Brand|null
    {
        $pdo = Database::getDatabaseConn();
        $sql = "SELECT id,name FROM brands WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $id);

        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            return null;
        }

        $row = $stmt->fetch();

        return new Brand(id: $row["id"], name: $row["name"]);
    }

    public function destroy(): bool
    {
        $pdo = Database::getDatabaseConn();
        $sql = "DELETE FROM brands WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        return ($stmt->rowCount() !== 0);
    }

    public static function paginate(int $page, int $per_page): Paginator
    {
        return new Paginator(
            class:Brand::class,
            page:$page,
            per_page:$per_page,
            table:'brands',
            attributes:["name"]
        );
    }
}
