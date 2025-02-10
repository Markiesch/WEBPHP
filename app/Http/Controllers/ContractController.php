<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function showUploadForm()
    {
        return view('contract');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'contract' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $path = $request->file('contract')->store('contracts');

        // Save contract details to the database (if needed)

        return redirect()->route('contracts.showUploadForm')->with('success', 'Contract uploaded successfully');
    }

    public function approve($id)
    {
        $contract = Contract::find($id);
        if (!$contract) {
            return response()->json(['message' => 'Contract not found'], 404);
        }

        $contract->status = 'approved';
        $contract->save();

        return response()->json(['message' => 'Contract approved successfully']);
    }
}
