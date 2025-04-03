<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Services\Seller\AgendaService;
use Illuminate\View\View;

class AgendaController extends Controller
{
    public function __construct(private readonly AgendaService $service) {}

    public function index(): View
    {
        $data = $this->service->getAgendaList();
        return view('seller/agenda', $data);
    }
}
