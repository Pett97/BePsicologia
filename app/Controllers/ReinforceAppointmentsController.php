<?php

namespace App\Controllers;

use App\Models\Appointment;
use Core\Http\Controllers\Controller;
use Core\Http\Request;

class ReinforceAppointmentsController extends Controller
{
    public function index(Request $request): void
    {
        $paginator = Appointment::paginate(
            page: $request->getParam('page', 1),
            route: 'reinforce.appointments.paginate'
        );
        $problems = $paginator->registers();

        $title = 'Todos os Agendamentos';

        $this->render('appointments/list_appointments', compact('paginator', 'appointments', 'title'));
    }
}
