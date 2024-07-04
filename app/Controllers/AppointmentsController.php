<?php

namespace App\Controllers;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use Core\Http\Request;
use Lib\FlashMessage;
use Core\Http\Controllers\Controller;
use Lib\Authentication\Auth;

class AppointmentsController extends Controller
{
    public function index(Request $request): void
    {
        $page = $request->getParam('page', 1);
        $itemsPerPage = $request->getParam('items_per_page', 10);

        if (Auth::isMaster()) {
            $isMaster = true;
            $paginator = Appointment::paginate($page, $itemsPerPage);
        } else {
            $isMaster = false;
            $paginator = $this->current_user->appointments()->paginate(page: $request->getParam('page', 1));
            $itemsPerPage = $request->getParam('items_per_page', 10);
        }

        $appointments = $paginator->registers();
        $title = 'Agendamentos Registrados';

        if ($request->acceptJson()) {
            $this->renderJson(
                'appointments/list_appointments',
                compact('paginator', 'appointments', 'title', "isMaster")
            );
        } else {
            $this->render('appointments/list_appointments', compact('paginator', 'appointments', 'title', "isMaster"));
        }
    }

    public function new(): void
    {
        if (Auth::isMaster()) {
            $isMaster = true;
            $appointment = null;
            $clients = Client::all();
            $users = User::all();
            $title = 'Novo Agendamento';
            $this->render(
                'appointments/new_appointment',
                compact(
                    'appointment',
                    "clients",
                    "users",
                    'title',
                    "isMaster"
                )
            );
        } else {
            $isMaster = false;
            $appointment = $this->current_user->appointments()->new();
            $clients = Client::all();
            $user = $this->current_user;
            $title = 'Novo Agendamento';
            $this->render(
                'appointments/new_appointment',
                compact(
                    'appointment',
                    "clients",
                    "user",
                    'title',
                    "isMaster"
                )
            );
        }
    }

    public function create(Request $request): void
    {
        $params = $request->getParams();
        //dd($params);
        $testAppointament = [
            'psychologist_id' => $params["appointment"]["psychologist_id"],
            'date' => $params["appointment"]["date"],
            'start_time' => $params["appointment"]["start_time"],
            'end_time' => $params["appointment"]["end_time"],
            'client_id' => $params["appointment"]["client_id"]
        ];

        $appointment = new Appointment($testAppointament);

        FlashMessage::success("Agendamento Salvo Com Sucesso");
        $this->redirectTo(route("list.appointaments"));

        $users = User::all();
        $title = "Novo Agendamento";
        $this->render("appointments/new_appointment", compact("appointment", "title", "users"));
    }


    public function show(Request $request): void
    {
        $id = $request->getParam("id");
        $appointment = $this->current_user->appointments()->findById((int)$id);

        if ($appointment !== null) {
            $title = "Agendamento: " . $appointment->id;
            $this->render("appointments/appointment_detail", compact("appointment", "title"));
        } else {
            $this->redirectTo(route("list.appointaments"));
        }
    }

    public function edit(Request $request): void
    {
        $clients = Client::all();
        $users = User::all();
        $id = $request->getParam("id");
        $params = $request->getParams();
        $appointment = $this->current_user->appointments()->findById((int)$id);

        $title = "Editar Agendamento #{$appointment->id}";
        $this->render('appointments/edit_appointment', compact('appointment', "clients", "users", 'title'));
    }

    public function update(Request $request): void
    {
        $id = $request->getParam("id");
        $params = $request->getParam("appointment");


        $appointment = $this->current_user->appointments()->findById($id);
        $appointment->psychologist_id = $params['psychologist_id'];
        $date = \DateTime::createFromFormat('d/m/Y', $params['new_date']);
        $appointment->date = $date->format('Y-m-d');
        $appointment->start_time = $params['start_time'];
        $appointment->end_time = $params['end_time'];
        $appointment->client_id = $params['client_id'];

        if ($appointment->save()) {
            FlashMessage::success("Agendamento Atualizado");
            $this->redirectTo(route("list.appointaments"));
        } else {
            FlashMessage::danger("Erro ao atualizar agendamento.");
        }
    }


    public function delete(Request $request): void
    {
        $params = $request->getParams();

        $appointment = $this->current_user->appointments()->findById($params['id']);
        $appointment->destroy();

        FlashMessage::success('Agendamento removido com sucesso!');
        $this->redirectTo(route('list.appointaments'));
    }
}
