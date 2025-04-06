<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Services\Seller\AgendaService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AgendaController extends Controller
{
    public function __construct(
        private readonly AgendaService $agendaService
    ) {}

    public function index(Request $request): View
    {
        $data = $this->agendaService->getAgendaList($request->get('month'));
        return view('seller.agenda.index', $data);
    }
}
