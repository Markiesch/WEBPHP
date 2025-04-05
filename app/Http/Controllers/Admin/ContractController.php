<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;
use Exception;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ContractController extends Controller
{
    public function index(Request $request): View
    {
        $businesses = Business::query()
            ->when($request->filled('sort_by'), function($query) use ($request) {
                $direction = $request->input('direction', 'asc');
                $query->orderBy($request->sort_by, $direction);
            })
            ->paginate(10);

        return view('admin.contracts.index', compact('businesses'));
    }

    public function generatePdf(Request $request, Business $business)
    {
        try {
            $data = [
                'name' => $business->name,
                'email' => $business->email,
                'description' => "This contract confirms the registration of {$business->name} in our system.",
                'contract' => (object)[
                    'file_path' => $business->contract_file ?? 'No file attached',
                    'created_at' => $business->created_at ?? now()
                ],
                'date' => now()->format('d/m/Y'),
                'business' => $business
            ];

            return Pdf::view('pdf.pdf', $data)
                ->format('a4')
                ->download("contract_{$business->id}.pdf");

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Failed to generate PDF: ' . $e->getMessage()]);
        }
    }

    public function upload(Request $request, Business $business): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'contract_file' => 'required|file|mimes:pdf|max:5120',
                'status' => 'required|in:pending,approved,rejected'
            ]);

            $path = $request->file('contract_file')->store('contracts');

            $business->update([
                'contract_status' => $validated['status'],
                'contract_file' => $path,
                'contract_updated_at' => now()
            ]);

            return redirect()
                ->route('admin.contracts.index')
                ->with('success', 'Contract uploaded successfully.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
