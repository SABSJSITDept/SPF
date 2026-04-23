<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SpfRegistration;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;

class ReportController extends Controller
{
    // ✅ Get Users Filtered by Anchal
    public function getFilteredUsers(Request $request)
    {
        $query = SpfRegistration::query();

        if ($request->anchal) {
            $query->where('anchal', $request->anchal);
        }

        return response()->json($query->get());
    }

    public function exportUsers(Request $request)
{
    // ✅ Check if 'anchal' is provided in the request
    if (!$request->anchal) {
        return response()->json(['error' => 'Anchal is required for export'], 400);
    }

    // ✅ Pass 'anchal' ID to the UsersExport class
    return Excel::download(new UsersExport($request->anchal), 'users.xlsx');
}
public function export(Request $request)
{
    // ✅ Ensure 'anchal' is provided
    if (!$request->anchal) {
        return response()->json(['error' => 'Anchal is required for export'], 400);
    }

    $filename = 'SPF_Members_Anchal_' . $request->anchal . '.xlsx';

    // ✅ Pass anchal ID to UsersExport
    return Excel::download(new UsersExport($request->anchal), $filename);
}
}
