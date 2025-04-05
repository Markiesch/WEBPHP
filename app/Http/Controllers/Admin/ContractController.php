<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelPdf\Facades\Pdf;

class ContractController extends Controller
{

    public function index(Request $request)
    {
        $contracts = Contract::with('business')
            ->sortable($request)
            ->latest()
            ->paginate(10);

        return view('admin.contracts.index', compact('contracts'));
    }



    public function showUploadForm()
    {
        $businesses = Business::all();

        if (auth()->user()->hasRole('super_admin')) {
            return view('admin.contracts.upload', compact('businesses'));
        }

        return view('seller.contracts.upload', compact('businesses'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'description' => 'nullable|string|max:1000',
            'contract' => 'required|file|mimes:pdf|max:10240', // 10MB max
        ]);

        $file = $request->file('contract');
        $path = $file->store('contracts');

        $contract = new Contract();
        $contract->business_id = $request->business_id;
        $contract->description = $request->description;
        $contract->file_path = $path;
        $contract->status = auth()->user()->hasRole('super_admin') ? $request->status : 'pending';

        if (auth()->user()->hasRole('super_admin')) {
            $contract->reviewed_by = auth()->id();
            $contract->reviewed_at = now();
        }

        $contract->save();

        $redirectRoute = auth()->user()->hasRole('super_admin')
            ? 'admin.contracts.index'
            : 'contracts.index';

        return redirect()->route($redirectRoute)
            ->with('success', __('contracts.upload_success'));
    }

    public function exportPdf($id)
    {
        $contract = Contract::findOrFail($id);
        $business = $contract->business;

        $data = [
            'business' => $business,
            'contract' => $contract,
            'date' => now()->format('d M Y'),
            'contract_number' => 'CNT-' . $business->id . '-' . time(),
            'generated_by' => auth()->user()->name
        ];

        return Pdf::view('contracts.templates.export', $data)
            ->format('a4')
            ->download("contract_export_{$business->id}.pdf");
    }

    // Admin-only methods
    public function review(Contract $contract)
    {
        $this->authorize('review-contracts');
        return view('admin.contracts.review', compact('contract'));
    }

    public function updateStatus(Contract $contract, Request $request)
    {
        $this->authorize('review-contracts');

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'feedback' => 'required_if:status,rejected|nullable|string|max:1000'
        ]);

        $contract->update([
            'status' => $request->status,
            'feedback' => $request->feedback,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now()
        ]);

        return redirect()->route('admin.contracts.index')
            ->with('success', __('contracts.status_updated'));
    }

    public function generatePdf(Business $business)
    {
        $this->authorize('generate-contract-pdf');

        $data = [
            'business' => $business,
            'date' => now()->format('d M Y'),
            'contract_number' => 'CNT-' . $business->id . '-' . time(),
            'generated_by' => auth()->user()->name
        ];

        return Pdf::view('admin.contracts.templates.business', $data)
            ->format('a4')
            ->download("contract_business_{$business->id}.pdf");
    }

    public function download(Contract $contract)
    {
        if (!Storage::exists($contract->file_path)) {
            return back()->with('error', __('contracts.file_not_found'));
        }

        return Storage::download(
            $contract->file_path,
            "contract_business_{$contract->business_id}.pdf"
        );
    }

    public function destroy(Contract $contract)
    {
        $this->authorize('delete-contracts');

        if (Storage::exists($contract->file_path)) {
            Storage::delete($contract->file_path);
        }

        $contract->delete();

        return redirect()->route('admin.contracts.index')
            ->with('success', __('contracts.deleted'));
    }
}
