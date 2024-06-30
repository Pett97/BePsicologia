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
        //passa a array form
        $client = new Client($params["client"]);

        if ($client->save()) {
            FlashMessage::success("Cliente Salvo!");
            $this->redirectTo(route("clients.list"));
        } else {
            $title = "Novo Cliente";
            $this->render("clients/new_client", compact("client", "title"));
        }
    }

    public function show(Request $request): void
    {
        $params = $request->getParams();
        $client = Client::findByID($params["id"]);

        $title = "Detalhes Cliente";
        $this->render("clients/detail_client", compact("client", "title"));
    }

    public function edit(Request $request): void
    {
        $params = $request->getParams();
        $client = Client::findByID($params["id"]);

        if ($client !== null) {
            $title = "Editar Client ";
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

        //fill no Model
        $client->fill($params);

        if ($client->save()) {
            FlashMessage::success("Cliente Atualizado Com Sucesso");
            $this->redirectTo(route("clients.list"));
        } else {
            FlashMessage::danger("Erro ao atualizar cliente.");
            $title = "Editar Cliente: ";
            $this->render("clients/edit_client", compact("client", "title"));
        }
    }

    public function delete(Request $request): void
    {
        $params = $request->getParams();
        $client = Client::findByID($params["id"]);

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
