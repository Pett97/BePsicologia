<?php

namespace App\Models;

use Core\Constants\Constants;
use Core\Database\Database;
use Lib\Paginator;

class Client
{
    //cons dbPath  = '/var/www/database/brand.txt';

    private string $name = "";
    private string $phone = "";
    private int    $insurance_id = 0;
    private string $streetName = "";
    private int    $numberHouse = 0;
    private int    $city_id = 0;

    /**
     * @var array<string, string>
     */
    private array $errors = [];

    public function __construct(
        string $name = "",
        string $phone = "",
        int $insurance_id = 0,
        int $numberHouse = 0,
        string $streetName = "",
        int $city_id = 0,
        private int $id = -1
    ) {
        $this->name = trim(strtoupper($name));
        $this->phone = trim($phone);
        $this->insurance_id = $insurance_id;
        $this->numberHouse = $numberHouse;
        $this->streetName = $streetName;
        $this->city_id = $city_id;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setInsuranceID(int $valor): void
    {
        $this->insurance_id = $valor;
    }

    public function getInsuranceID(): int
    {
        return $this->insurance_id;
    }

    public  function setStreetName(string $streetName): void
    {
        $this->streetName = $streetName;
    }

    public function getStreetName(): string
    {
        return $this->streetName;
    }

    public function setNumberHouse(int $number): void
    {
        $this->numberHouse = $number;
    }

    public function getNumberHouse(): int
    {
        return $this->numberHouse;
    }

    public function setCityId(int $cityId): void
    {
        $this->city_id = $cityId;
    }

    public function getCityId(): int
    {
        return $this->city_id;
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
                $sql = "INSERT INTO clients (name, phone, insurance_id, street_name,number,city_id) VALUES (:name,:phone, :insurance_id, :street_name,:number,:city_id)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":name", $this->name);
                $stmt->bindParam(":phone", $this->phone);
                $stmt->bindParam(":insurance_id", $this->insurance_id);
                $stmt->bindParam(":street_name", $this->streetName);
                $stmt->bindParam(":number", $this->numberHouse);
                $stmt->bindParam(":city_id", $this->city_id);

                $stmt->execute();
            } else {
                $sql = "UPDATE clients set name = :name WHERE id = :id";
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
            $this->addErro("Nome Cliente Não Pode ser Vazio");
        }
        if (empty($this->getPhone())) {
            $this->addErro("Phone Cliente Não Pode ser Vazio");
        }

        if (empty($this->getInsuranceID())) {
            $this->addErro("Convenio Cliente Não Pode ser Vazio, informe ZERO caso não tenha ");
        }

        if (empty($this->getStreetName())) {
            $this->addErro("Endereço Cliente Não Pode ser Vazio, informe ZERO caso não tenha ");
        }

        if (empty($this->getNumberHouse())) {
            $this->addErro(" numero casa Cliente Não Pode ser Vazio, informe ZERO caso não tenha ");
        }

        if (empty($this->getCityId())) {
            $this->addErro("Cidade Cliente Não Pode ser Vazio, informe ZERO caso não tenha ");
        }

        return empty($this->errors);
    }
    /**
     * @return array<int, Client>
     */
    public static function all(): array
    {
        $clients = [];
        $pdo = Database::getDatabaseConn();
        $resp = $pdo->query("SELECT name,id FROM clients");
        foreach ($resp as $row) {
            $clients[] = new Client(name: $row["name"], id: $row["id"]);
        }
        return $clients;
    }


    public static function findByID(int $id): Client|null
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

        return new Client(id: $row["id"], name: $row["name"]);
    }

    public function destroy(): bool
    {
        $pdo = Database::getDatabaseConn();
        $sql = "DELETE FROM clients WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        return ($stmt->rowCount() !== 0);
    }

    public static function paginate(int $page, int $per_page): Paginator
    {
        return new Paginator(
            class: Client::class,
            page: $page,
            per_page: $per_page,
            table: 'clients',
            attributes: ["name"]
        );
    }
}
