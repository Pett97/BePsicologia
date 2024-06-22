<?php

namespace App\Controllers;

use App\Models\Appointment;
use Core\Http\Request;
use Lib\FlashMessage;
use App\Models\User;
use DateTime;
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


    //public function authenticated(): void
    //{
    //    if (!Auth::check()) {
    //        FlashMessage::danger("erro");
    //        $this->redirectTo("/login");
    //    }
    //}

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
        $this->render("new_appointment", compact("appointment", "title"));
        $view = "/var/www/app/views/appointments/.phtml";
    }

    public function create(Request $request): void
    {
        $params = $request->getParams();

        $userID = intval($params["userID"]);
        $clientID = intval($params["clientID"]);
        $date = DateTime::createFromFormat('d/m/Y', $params["date"]);
        $startHour = DateTime::createFromFormat('H:i:s', $params["startHour"]);
        $hours = intval($params["hours"]);



        $appointment = new Appointment(
            userID: $userID,
            date: $date,
            startHour: $startHour,
            periodHours: $hours,
            clientID: $clientID
        );

        if ($appointment->save()) {
            FlashMessage::success("Agendamento Salvo Com Sucesso");
            $this->redirectTo(route("list.appointaments"));
        } else {
            $title = "Novo Agendamento ";
            $this->render("new_appointment", compact("appointment", "title"));
        }
    }

    public function show(Request $request): void
    {
        $params = $request->getParams();
        $appointment = Appointment::findByID($params["id"]);

        if ($appointment !== null) {
            $title = "Agendamento: " . $appointment->getID();
            $this->render("appointment_detail", compact("appointment", "title"));
        } else {
            $this->redirectTo(route("list.appointaments"));
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
        $userID = $params["newUserID"];
        $date = DateTime::createFromFormat('d/m/Y', $params["newDate"]);
        $startHour = DateTime::createFromFormat('H:i:s', $params["newStartHour"]);
        $endHour = DateTime::createFromFormat('H:i:s', $params["newEndHours"]);
        $interval = $startHour->diff($endHour);
        $hours = $interval->h;
        $clientID = $params["newClientID"];


        $appointment->setUserID($userID);
        $appointment->setDate($date);
        $appointment->setStartHour($startHour);
        $appointment->setPeriodHours($hours);
        $appointment->setClientID($clientID);

        $appointment->save();
        FlashMessage::success("Agendamento Atualizado Com Sucesso");
        $this->redirectTo(route("list.appointaments"));

        $title = "Editar Agentamento ";
        $this->render("edit_appointment", compact("appointment", "title"));
    }

    public function delete(Request $request): void
    {
        $params = $request->getParams();
        $appointment = Appointment::findByID($params["id"]);
        $appointment->destroy();
        FlashMessage::success("Agendamento Removido Com Sucesso");
        $this->redirectTo(route("list.appointaments"));
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
