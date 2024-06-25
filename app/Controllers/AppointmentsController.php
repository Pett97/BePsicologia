<?php

namespace App\Controllers;

use Core\Http\Request;
use Lib\FlashMessage;

use Core\Http\Controllers\Controller;

class AppointmentsController extends Controller
{

    public function index(Request $request): void
    {
        $paginator = $this->current_user->appointments()->paginate(page: $request->getParam('page', 1));
        $appointments = $paginator->registers();

        $title = 'Agendamentos Registrados';

        if ($request->acceptJson()) {
            $this->renderJson('appointments/list_appointments', compact('paginator', 'appointments', 'title'));
        } else {
            $this->render('appointments/list_appointments', compact('paginator', 'appointments', 'title'));
        }
    }

    public function new(): void
    {
        $problem = $this->current_user->appointments()->new();

        $title = 'Novo Agendamento';
        $this->render('appointments/new', compact('appointment', 'title'));
    }

    public function create(Request $request): void
    {
        $params = $request->getParams();
        $appointment = $this->current_user->appointments()->new($params['appointment']);

        if ($appointment->save()) {
            FlashMessage::success("Agendamento Salvo Com Sucesso");
            $this->redirectTo(route("list.appointaments"));
        } else {
            $title = "Novo Agendamento ";
            $this->render("appointments/new_appointment", compact("appointment", "title"));
        }
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
        $params = $request->getParams();
        $appointment = $this->current_user->appointments()->findById($params['id']);

        $title = "Editar Agendamento #{$appointment->id}";
        $this->render('appointments/edit_appointment', compact('appointment', 'title'));
    }

    public function update(Request $request): void
    {
        $id = $request->getParam("id");
        $params = $request->getParam('appointment');
        dd($params);
        $appointment = $this->current_user->appointments()->findById((int) $id);

        if ($appointment === null) {
            FlashMessage::danger("Agendamento não encontrado.");
            $this->redirectTo(route("list.appointments"));
            return;
        }

        if ($appointment->save()) {
            FlashMessage::success("Agendamento Atualizado Com Sucesso");
            $this->redirectTo(route("list.appointments"));
        } else {
            FlashMessage::danger("Erro ao atualizar agendamento.");
            $title = "Editar Agendamento ";
            $this->render("appointments/edit_appointment", compact("appointment", "id"));
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
