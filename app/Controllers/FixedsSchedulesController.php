<?php

namespace App\Controllers;

use App\Models\FixedSchedule;
use Core\Http\Request;
use Lib\FlashMessage;
use Core\Http\Controllers\Controller;
use DateTime;

class FixedsSchedulesController extends Controller
{
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
            $this->render('fixeds_schedules/schedules.list', compact("paginator", 'fixedSchedules', 'title'));
        }
    }

    public function new(): void
    {
        $title = "Novo Horário";
        $fixedSchedule = new FixedSchedule();
        $this->render("fixeds_schedules/new_schedule", compact("fixedSchedule", "title"));
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
            $this->redirectTo(route("schedules.list"));
        } else {
            $title = "Novo Horário";
            $this->render("fixeds_schedules/schedules.list", compact("fixedSchedule", "title"));
        }
    }

    public function show(Request $request): void
    {
        $params = $request->getParams();
        $fixedSchedule = FixedSchedule::findByID($params["id"]);

        if ($fixedSchedule !== null) {
            $title = "Horário Usuário:" . $fixedSchedule->getUserID();
            $this->render("fixeds_schedules/detail_schedule", compact("fixedSchedule", "title"));
        } else {
            $this->redirectTo(route("client.list"));
        }
    }



    public function edit(Request $request): void
    {
        $params = $request->getParams();
        $fixedSchedule = FixedSchedule::findByID($params["id"]);
        $title = "Editar:  {$fixedSchedule->getID()}";
        $this->render("fixeds_schedules/edit_schedule", compact("fixedSchedule", "title"));
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
        $this->render("fixeds_schedules/edit_schedule", compact("fixedSchedule", "title"));
    }

    public function delete(Request $request): void
    {

        $params = $request->getParams();
        $fixedSchedule = FixedSchedule::findByID($params["id"]);
        $fixedSchedule->destroy();
        FlashMessage::success("Horário  Removido Com Sucesso");
        $this->redirectTo(route("schedules.list"));
    }
}
