<?php

namespace App\Controllers;

use App\Models\Appointment;
use Core\Http\Request;
use Lib\FlashMessage;
use App\Models\User;
use Lib\Authentication\Auth;

class AppointmentsController
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
        $paginator = Appointment::paginate($page, $itemsPerPage);
        $appointments = $paginator->registers();

        //dd($clients);
        $title = "Agendamentos";

        if ($request->acceptJson()) {
            $this->renderJson('index', compact("paginator", 'appointments', 'title'));
        } else {
            $this->render('list_appointments', compact("paginator", 'appointments', 'title'));
        }
    }

    public function new(): void
    {
        $title = "Novo Agendamento";
        $appointment = new Appointment();
        $this->render("new_client", compact("appointment", "title"));
        $view = "/var/www/app/views/appointments/.phtml";
    }

    public function create(Request $request): void
    {
        $params = $request->getParams();

        $appointment = new Appointment(
            userID: $params["userID"],
            date: $params["date"],
            startHour: $params["startHour"],
            periodHours: $params["endHour"],
            clientID: $params["clientID"]
        );

        if ($appointment->save()) {
            FlashMessage::success("Agendamento Salvo Com Sucesso");
            $this->redirectTo(route("clients.list"));
        } else {
            $title = "Novo Agendamento";
            $this->render("list_appointment", compact("appointment", "title"));
        }
    }

    public function show(Request $request): void
    {
        $params = $request->getParams();
        $appointment = Appointment::findByID($params["id"]);

        if ($appointment !== null) {
            $title = $appointment->getID();
            $this->render("appointment_detail", compact("appointment", "title"));
        } else {
            $this->redirectTo(route("appointment.list"));
        }
    }

    public function edit(Request $request): void
    {
        $params = $request->getParams();
        $appointment = Appointment::findByID($params["id"]);
        $title = "Editar Agendamento " . $appointment->getID() . "}";
        $this->render("edit_appointment", compact("appointment", "title"));
    }

    public function update(Request $request): void
    {
        $params = $request->getParams();

        $appointment = Appointment::findByID($params["id"]);

        $newUserID = $params["userID"];
        $newDate = $params["date"];
        $newStarHour = $params["startHour"];
        $newPeriodHours = $params["periodHours"];
        $newClientID = $params["clientID"];

        $appointment->save();
        FlashMessage::success("Agendamento Atualizado Com Sucesso");
        $this->redirectTo(route("appointments.list"));

        $title = "Editar Agentamento ";
        $this->render("edit_appointment", compact("appointment", "title"));
    }

    public function delete(Request $request): void
    {
        $params = $request->getParams();
        $appointment = Appointment::findByID($params["id"]);
        $appointment->destroy();
        FlashMessage::success("Agendamento Removido Com Sucesso");
        $this->redirectTo(route("list_appointment"));
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
        $view = "/var/www/app/views/appointments/" . $view . ".phtml";
        require "/var/www/app/views/layouts/" . $this->layout . ".phtml";
    }

    /**
     * @param array<string, mixed> $data
     */
    private function renderJSON(string $view, array $data = []): void
    {
        extract($data);
        $view = "/var/www/app/views/appointments/" . $view . "json.php";
        $json = [];
        include "/var/www/app/views/appointments/appointment.json.php";
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($json);
        return;
    }

    //private function isJsonRequest(): bool
    //{
    //  return (isset($_SERVER['HTTP_ACCEPT']) && $_SERVER['HTTP_ACCEPT'] === ///'application/json');
    // }
}
