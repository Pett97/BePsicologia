<?php

namespace App\Controllers;

use Core\Http\Request;
use Lib\FlashMessage;
use App\Models\Client;
use Core\Http\Controllers\Controller;

class ClientsController extends Controller
{
    public function index(Request $request): void
    {
        $page = $request->getParam('page', 1);
        $itemsPerPage = $request->getParam('items_per_page', 5);
        $paginator = Client::paginate($page, $itemsPerPage);
        $clients = $paginator->registers();
        $title = "Lista De Clientes";

        if ($request->acceptJson()) {
            $this->renderJson('clients/index', compact("paginator", 'clients', 'title'));
        } else {
            $this->render('clients/list_clients', compact("paginator", 'clients', 'title'));
        }
    }

    public function new(): void
    {
        $title = "Novo Cliente";
        $client = new Client();
        $this->render("clients/new_client", compact("client", "title"));
    }

    public function create(Request $request): void
    {
        $params = $request->getParams();
        //dd($params);

        $newClient = [
            'name' => $params["client_name"],
            'phone' => $params["client_phone"],
            'insurance_id' => $params["client_insurance"],
            'street_name' => $params["client_street"],
            'number' => $params["number_house"],
            'city_id' => $params["city_id"],
        ];

        $client = new Client($newClient);
        if ($client->save()) {
            FlashMessage::danger("Cliente Salvo!");
            $this->redirectTo(route("clients.list"));
        } else {
            $this->render("clients/new_client", compact("client", "title"));
        }
    }

    public function show(Request $request): void
    {
        $params = $request->getParams();
        $client = Client::findByID($params["id"]);

        if ($client !== null) {
            $title = $client->name;
            $this->render("clients/detail_client", compact("client", "title"));
        } else {
            $this->redirectTo(route("clients.list"));
        }
    }

    public function edit(Request $request): void
    {
        $params = $request->getParams();
        $client = Client::findByID($params["id"]);

        if ($client !== null) {
            $title = "Editar: " . $client->name;
            $this->render("clients/edit_client", compact("client", "title"));
        } else {
            FlashMessage::danger("Cliente não encontrado.");
            $this->redirectTo(route("clients.list"));
        }
    }

    public function update(Request $request): void
    {
        $id = $request->getParam("id");
        $params = $request->getParam("client");
        $client = Client::findById($id);

        if ($client === null) {
            FlashMessage::danger("Cliente não encontrado.");
            $this->redirectTo(route("clients.list"));
            return;
        }

        $client->name = $params["new_client_name"];
        $client->phone = $params["new_client_phone"];
        $client->insurance_id = $params["new_client_insurance"];
        $client->street_name = $params["new_client_street"];
        $client->number = $params["new_client_number_house"];
        $client->city_id = $params["new_client_city_id"];

        if ($client->save()) {
            FlashMessage::success("Cliente Atualizado Com Sucesso");
            $this->redirectTo(route("clients.list"));
        } else {
            FlashMessage::danger("Erro ao atualizar cliente.");
            $title = "Editar Cliente: " . $client->name;
            $this->render("clients/edit_client", compact("client", "title"));
        }
    }

    public function delete(Request $request): void
    {
        $params = $request->getParams();
        $id = $request->getParams(["id"]);
        $client = Client::findByID((int)$id);

        if ($client === null) {
            FlashMessage::danger("Cliente não encontrado.");
            $this->redirectTo(route("clients.list"));
            return;
        }

        $client->destroy();
        FlashMessage::success("Cliente Removido Com Sucesso");
        $this->redirectTo(route("clients.list"));
    }
}
