<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelPdf\Facades\Pdf;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        $contracts = Contract::where('user_id', Auth::id())
            ->sortable($request)
            ->paginate(6);
        return view('contracts.index', compact('contracts'));
    }

    public function showUploadForm()
    {
        $contracts = Contract::where('user_id', Auth::id())->get();
        return view('contracts.upload', compact('contracts'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'description' => 'string',
            'contract' => 'required|file|mimes:pdf,doc,docx',
        ]);

        $file = $request->file('contract');
        $path = $file->store('contracts');

        $contract = new Contract();
        $contract->name = $request->input('name');
        $contract->email = $request->input('email');
        $contract->description = $request->input('description');
        $contract->file_path = $path;
        $contract->user_id = Auth::id();
        $contract->save();

        return redirect()->route('contracts.index')
            ->with('success', 'Contract uploaded successfully.');
    }

    public function exportPdf($id)
    {
        $contract = Contract::where('user_id', Auth::id())->findOrFail($id);

        return Pdf::view('pdf.pdf', [
            'name' => $contract->name,
            'email' => $contract->email,
            'description' => $contract->description,
            'contract' => $contract,
        ])->format('a4')->download("contract_{$contract->id}.pdf");
    }
}
