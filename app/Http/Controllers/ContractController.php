<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contract;
use Spatie\Browsershot\Browsershot;

class ContractController extends Controller
{
    public function showUploadForm()
    {
        $contracts = Contract::all();
        return view('contracts.upload', compact('contracts'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'contract' => 'required|file|mimes:pdf,doc,docx',
        ]);

        $file = $request->file('contract');
        $path = $file->store('contracts');

        $contract = new Contract();
        $contract->file_path = $path;
        $contract->save();

        return redirect()->route('contracts.showUploadForm')->with('success', 'Contract uploaded successfully.');
    }

    public function exportPdf($id)
    {
        $contract = Contract::findOrFail($id);
        $data = [
            'name' => 'Example Name', // Replace with actual data
            'email' => 'example@example.com', // Replace with actual data
            'business_name' => 'Example Business', // Replace with actual data
            'registration_date' => now()->toDateString(),
        ];

        $html = view('pdf.contract', $data)->render();
        $pdf = Browsershot::html($html)->pdf();

        return response()->streamDownload(
            fn () => print($pdf),
            'business_registration_contract.pdf'
        );
    }
}
