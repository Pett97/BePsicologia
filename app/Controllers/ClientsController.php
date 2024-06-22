<?php

namespace App\Controllers;

use App\Models\Client;
use Core\Http\Request;
use Lib\FlashMessage;
use App\Models\User;
use Lib\Authentication\Auth;

class ClientsController
{
    private string $layout = "application";
    private ?User $currentUser = null;


    private function currentUser(): ?User
    {
        if ($this->currentUser === null) {
            $this->currentUser = Auth::user();
        }
        return $this->currentUser;
    }


    public function authenticated(): void
    {
        if (!Auth::check()) {
            FlashMessage::danger("erro");
            $this->redirectTo("/login");
        }
    }

    public function index(Request $request): void
    {
        $page = $request->getParam('page', 1);
        $itemsPerPage = $request->getParam('items_per_page', 5);
        $paginator = Client::paginate($page, $itemsPerPage);
        $clients = $paginator->registers();

        //dd($clients);
        $title = "Lista De Clientes";

        if ($request->acceptJson()) {
            $this->renderJson('index', compact("paginator", 'clients', 'title'));
        } else {
            $this->render('list_clients', compact("paginator", 'clients', 'title'));
        }
    }

    public function new(): void
    {
        $title = "Novo Cliente";
        $client = new Client();
        $this->render("new_client", compact("client", "title"));
        $view = "/var/www/app/views/clients/.phtml";
    }

    public function create(Request $request): void
    {
        $params = $request->getParams();

        $client = new Client(
            name: $params["client_name"],
            phone: $params["client_phone"],
            insurance_id: $params["client_insurance"],
            streetName: $params["client_street"],
            numberHouse: $params["number_house"],
            city_id: $params["city_id"]
        );

        if ($client->save()) {
            FlashMessage::success("Cliente Salvo Com Sucesso");
            $this->redirectTo(route("clients.list"));
        } else {
            $title = "Novo Client";
            $this->render("list_client", compact("client", "title"));
        }
    }

    public function show(Request $request): void
    {
        $params = $request->getParams();
        $client = Client::findByID($params["id"]);

        if ($client !== null) {
            $title = $client->getName();
            $this->render("detail_client", compact("client", "title"));
        } else {
            $this->redirectTo(route("client.list"));
        }
    }

    public function edit(Request $request): void
    {
        $params = $request->getParams();
        $client = Client::findByID($params["id"]);
        $title = "Editar:  {$client->getName()}";
        $this->render("edit_client", compact("client", "title"));
    }

    public function update(Request $request): void
    {
        $params = $request->getParams();

        $client = Client::findByID($params["id"]);

        $newNameClient = $params["newClientName"];
        $newPhoneClient = $params["newClientePhone"];
        $newInsuranceClient = $params["newClientInsuranceID"];
        $newStreetName = $params["newClientStreetName"];
        $newHouseNumber = $params["newClientNumberHouse"];
        $newCityID = $params["newClientCity"];
        $client->setName($newNameClient);
        $client->setPhone($newPhoneClient);
        $client->setInsuranceID($newInsuranceClient);
        $client->setStreetName($newStreetName);
        $client->setNumberHouse($newHouseNumber);
        $client->setCityId($newCityID);

        $client->save();
        FlashMessage::success("Cliente Atualizado Com Sucesso");
        $this->redirectTo(route("clients.list"));

        $title = "Editar Client ";
        $this->render("edit_client", compact("client", "title"));
    }

    public function delete(Request $request): void
    {
        $params = $request->getParams();
        $client = Client::findByID($params["id"]);
        $client->destroy();
        FlashMessage::success("Client Removido Com Sucesso");
        $this->redirectTo(route("clients.list"));
    }


    private function redirectTo(string $path): void
    {
        header("Location:" . $path);
        exit;
    }
    /**
     * @param array<string, mixed> $data
     */
    private function render(string $view, array $data = []): void
    {
        extract($data);
        $view = "/var/www/app/views/clients/" . $view . ".phtml";
        require "/var/www/app/views/layouts/" . $this->layout . ".phtml";
    }

    /**
     * @param array<string, mixed> $data
     */
    private function renderJSON(string $view, array $data = []): void
    {
        extract($data);
        $view = "/var/www/app/views/client/" . $view . "json.php";
        $json = [];
        include "/var/www/app/views/client/client.json.php";
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($json);
        return;
    }

    //private function isJsonRequest(): bool
    //{
    //  return (isset($_SERVER['HTTP_ACCEPT']) && $_SERVER['HTTP_ACCEPT'] === ///'application/json');
    // }
}