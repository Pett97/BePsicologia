<?php

namespace App\Controllers;

use App\Models\FixedSchedule;
use Core\Http\Request;
use Lib\FlashMessage;
use App\Models\User;
use DateTime;
use Lib\Authentication\Auth;

class FixedsSchedulesController
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
        $itemsPerPage = $request->getParam('items_per_page', 10);
        $paginator = FixedSchedule::paginate($page, $itemsPerPage);
        $fixedSchedules = $paginator->registers();

        //dd($clients);
        $title = "Horários Fixos De Trabalho";

        if ($request->acceptJson()) {
            $this->renderJson('index', compact("paginator", 'fixedSchedules', 'title'));
        } else {
            $this->render('schedules.list', compact("paginator", 'fixedSchedules', 'title'));
        }
    }

    public function new(): void
    {
        $title = "Novo Horário";
        $fixedSchedule = new FixedSchedule();
        $this->render("new_schedule", compact("fixedSchedule", "title"));
        $view = "/var/www/app/views/fixeds_shedules/.phtml";
    }

    public function create(Request $request): void
    {
        $params = $request->getParams();

        $fixedSchedule = new FixedSchedule(
            userID: $params["psicoID"],
            dayOFWeek: $params["dayOFWeek"],
            startTime: new \DateTime($params["startTime"]),
            endTime: new \DateTime($params["endTime"])
        );

        if ($fixedSchedule->save()) {
            FlashMessage::success("Horário Salvo Com Sucesso");
            $this->redirectTo(route("clients.list"));
        } else {
            $title = "Novo Horário";
            $this->render("list_client", compact("fixedSchedule", "title"));
        }
    }

    public function show(Request $request): void
    {
        $params = $request->getParams();
        $fixedSchedule = FixedSchedule::findByID($params["id"]);

        if ($fixedSchedule !== null) {
            $title = "Horário Usuário:" . $fixedSchedule->getUserID();
            $this->render("detail_schedule", compact("fixedSchedule", "title"));
        } else {
            $this->redirectTo(route("client.list"));
        }
    }



    public function edit(Request $request): void
    {
        $params = $request->getParams();
        $fixedSchedule = FixedSchedule::findByID($params["id"]);
        $title = "Editar:  {$fixedSchedule->getID()}";
        $this->render("edit_schedule", compact("fixedSchedule", "title"));
    }

    public function update(Request $request): void
    {
        $params = $request->getParams();

        $fixedSchedule = FixedSchedule::findByID($params["id"]);
        $newDayOFWeek = $params["newDayOFWeek"];
        $newDateStart = $params["newDateStart"];
        $newEndStart = $params["newDateEnd"];
        $fixedSchedule->setDayOFWeek($newDayOFWeek);
        $fixedSchedule->setStartTime(new DateTime($newDateStart));
        $fixedSchedule->setEndTime(new DateTime($newEndStart));

        $fixedSchedule->save();
        FlashMessage::success("Horário Atualizado Com Sucesso");
        $this->redirectTo(route("schedules.list"));

        $title = "Editar Horário ";
        $this->render("edit_schedule", compact("fixedSchedule", "title"));
    }

    public function delete(Request $request): void
    {

        $params = $request->getParams();
        $fixedSchedule = FixedSchedule::findByID($params["id"]);
        $fixedSchedule->destroy();
        FlashMessage::success("Horário  Removido Com Sucesso");
        $this->redirectTo(route("schedules.list"));
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
        $view = "/var/www/app/views/fixeds_schedules/" . $view . ".phtml";
        require "/var/www/app/views/layouts/" . $this->layout . ".phtml";
    }

    /**
     * @param array<string, mixed> $data
     */
    private function renderJSON(string $view, array $data = []): void
    {
        extract($data);
        $view = "/var/www/app/views/fixeds_schedules/" . $view . "json.php";
        $json = [];
        include "/var/www/app/views/fixeds_schedules/client.json.php";
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($json);
        return;
    }

    //private function isJsonRequest(): bool
    //{
    //  return (isset($_SERVER['HTTP_ACCEPT']) && $_SERVER['HTTP_ACCEPT'] === ///'application/json');
    // }
}
