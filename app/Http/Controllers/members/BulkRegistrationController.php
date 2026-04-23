<?php

namespace App\Http\Controllers\members;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MembersImport;

class BulkRegistrationController extends Controller
{
    /**
     * Show the bulk upload form.
     */
    public function showForm()
    {
        return view('spf_backend.members.bulk_upload');
    }

    /**
     * Process the uploaded Excel file.
     */
    public function processUpload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // Max 10MB
        ]);

        try {
            Excel::import(new \App\Imports\MembersImport(auth()->guard('admin')->user()), $request->file('excel_file'));
            return redirect()->back()->with('success', 'Bulk upload processed successfully! Data has been saved to the database.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'There was an error processing the file: ' . $e->getMessage()]);
        }
    }
}
