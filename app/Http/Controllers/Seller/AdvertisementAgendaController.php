<?php
//
//namespace App\Http\Controllers\Seller;
//
//use App\Http\Controllers\Controller;
//use App\Http\Services\Seller\AdvertisementAgendaService;
//use Illuminate\Http\Request;
//use Illuminate\View\View;
//
//class AdvertisementAgendaController extends Controller
//{
//    private AdvertisementAgendaService $agendaService;
//
//    public function __construct(AdvertisementAgendaService $agendaService)
//    {
//        $this->agendaService = $agendaService;
//    }
//
//    public function index(Request $request): View
//    {
//        $month = $request->get('month');
//        $data = $this->agendaService->getCalendarData($month);
//
//        return view('advertisementagenda.advertisementagenda', $data);
//    }
//}
