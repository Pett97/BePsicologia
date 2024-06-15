<?php

namespace App\Controllers;

use App\Models\Brand;
use Core\Http\Request;
use Lib\FlashMessage;
use App\Models\User;
use Lib\Authentication\Auth;

class BrandsController
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


   // public function authenticated(): void
   // {
   //     if(!Auth::check()){
   //         FlashMessage::danger("erro");
   //         $this->redirectTo("/login");
   //     }
   // }

    public function index(Request $request): void
    {
        $page = $request->getParam('page', 1);
        $itemsPerPage = $request->getParam('items_per_page', 10);
        $paginator = Brand::paginate($page, $itemsPerPage);
        $brands = $paginator->registers();
        $title = "Lista De Carros";

        if ($request->acceptJson()) {
            $this->renderJson('index', compact("paginator", 'brands', 'title'));
        } else {
            $this->render('list_brand', compact("paginator", 'brands', 'title'));
        }
    }

    public function new(): void
    {
        $title = "Nova Marca";
        $brand = new Brand();
        $this->render("new_brand", compact("brand", "title"));
        $view = "/var/www/app/views/brands/.phtml";
    }

    public function create(Request $request): void
    {
        $params = $request->getParams();
        $brand = new Brand(name: $params["brand_name"]);

        if ($brand->save()) {
            FlashMessage::success("Marca Criada Com Sucesso");
            $this->redirectTo(route("brands.list"));
        } else {
            $title = "Nova Marca";
            $this->render("list_brand", compact("brand", "title"));
        }
    }

    public function show(Request $request): void
    {
        $params = $request->getParams();
        $brand = Brand::findByID($params["id"]);

        if ($brand !== null) {
            $title = $brand->getName();
            $this->render("detail_brand", compact("brand", "title"));
        } else {
            $this->redirectTo(route("brands.list"));
        }
    }

    public function edit(Request $request): void
    {
        $params = $request->getParams();
        $brand = Brand::findByID($params["id"]);

        $title = "Editar {$brand->getName()}";
        $this->render("edit_brand", compact("brand", "title"));
    }

    public function update(Request $request): void
    {
        $params = $request->getParams();

        $brand = Brand::findByID($params["id"]);

        $newNameBrand = $params["newBrandName"];
        $brand->setName($newNameBrand);
        $brand->save();
        FlashMessage::success("Marca Atualizada Com Sucesso");
        $this->redirectTo(route("brands.list"));

        $title = "Editar Marca ";
        $this->render("edit_brand", compact("brand", "title"));
    }

    public function delete(Request $request): void
    {
        $params = $request->getParams();
        $brand = Brand::findByID($params["id"]);
        $brand->destroy();
        FlashMessage::success("Marca Removida Com Sucesso");
        $this->redirectTo(route("brands.list"));
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
        $view = "/var/www/app/views/brands/" . $view . ".phtml";
        require "/var/www/app/views/layouts/" . $this->layout . ".phtml";
    }

    /**
     * @param array<string, mixed> $data
     */
    private function renderJSON(string $view, array $data = []): void
    {
        extract($data);
        $view = "/var/www/app/views/brands/" . $view . "json.php";
        $json = [];
        include "/var/www/app/views/brands/brands.json.php";
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($json);
        return;
    }

    //private function isJsonRequest(): bool
    //{
    //  return (isset($_SERVER['HTTP_ACCEPT']) && $_SERVER['HTTP_ACCEPT'] === ///'application/json');
    // }
}
