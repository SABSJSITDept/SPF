<?php

namespace App\Http\Controllers;

use App\Models\SpfRegistration;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class SpfRegistrationController extends Controller
{

    public function dashboard()
    {
        $admin = Auth::guard('admin')->user();

        if ($admin && $admin->isAnchalOperator()) {
            $query = SpfRegistration::where('anchal', $admin->anchal);
        } else {
            $query = SpfRegistration::query();
        }

        $totalMembers  = (clone $query)->count();
        $approvedCount = (clone $query)->where('status', 'approved')->count();
        $pendingCount  = (clone $query)->where('status', 'pending')->count();
        $rejectedCount = (clone $query)->where('status', 'rejected')->count();

        $anchalStats = (clone $query)
            ->select('anchal', DB::raw('count(*) as total'))
            ->groupBy('anchal')
            ->orderByRaw('CAST(anchal AS UNSIGNED) ASC')
            ->get();

        // Fetch anchal_id => anchal_name map from external API (cached 1 hour)
        $anchalMap = Cache::remember('anchal_name_map', 3600, function () {
            try {
                $response = Http::timeout(10)->get('https://mrm.sadhumargi.org/api/branches');
                if ($response->successful()) {
                    $branches = $response->json('branches', []);
                    $map = [];
                    foreach ($branches as $branch) {
                        $map[(string) $branch['anchal_id']] = $branch['anchal_name'];
                    }
                    return $map;
                }
            } catch (\Exception $e) {
                // silently fall back to raw IDs
            }
            return [];
        });

        // Attach human-readable anchal name to each stat row
        $anchalStats->transform(function ($row) use ($anchalMap) {
            $row->anchal_label = $anchalMap[(string) $row->anchal] ?? $row->anchal;
            return $row;
        });

        return view('spf_backend.dashboard', compact(
            'totalMembers', 'approvedCount', 'pendingCount', 'rejectedCount', 'anchalStats'
        ));
    }

    public function index()
    {
        $admin = Auth::guard('admin')->user();

        // Anchal Operator: only approved records from their assigned anchal
        if ($admin && $admin->isAnchalOperator()) {
            $registrations = SpfRegistration::where('anchal', $admin->anchal)
                ->where('status', 'approved')
                ->latest()->get();
        } else {
            // Super Admin & Operator: all records
            $registrations = SpfRegistration::latest()->get();
        }

        return view('spf_backend.index', compact('registrations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mobile' => 'required|min:10',
            'full_name' => 'required',
            'father_name' => 'required',
            'dob' => 'required|date',
            'age' => 'required|integer',
            'gender' => 'required',
            'profession' => 'required',
            'state' => 'required',
            'city' => 'required',
            'anchal' => 'required',
            'sadhumargi' => 'required',
            'local_sangh_id' => 'nullable|integer',
            'objectives' => 'nullable|array',
            'source' => 'nullable|string',
            'working_status' => 'nullable|string',
            'hobbies' => 'nullable|string',
            'referral' => 'nullable|string',
            'mid' => 'nullable|string', // MID can be null
            'email' => 'nullable|email',
            'professional_category' => 'nullable|string',
            'document_type' => 'required|string|in:Professional degree,LinkedIn profile,Business card', // ✅ New Field
            'file' => 'nullable|file|mimes:pdf|max:5120', // ✅ Validate PDF file up to 5MB
        ]);

        // ✅ Check if the same Mobile Number & MID combination already exists
        $existingEntry = SpfRegistration::where('mobile', $validated['mobile'])
            ->where('mid', $validated['mid'])
            ->first();

        if ($existingEntry) {
            return response()->json([
                'status' => false,
                'message' => 'This Mobile No is already registered with this MID. Duplicate entry not allowed.'
            ], 400);
        }

        // ✅ Handle File Upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $validated['file'] = $filename; // Store filename in DB
        } else {
            $validated['file'] = null;
        }

        // ✅ Ensure MID is set as NULL if empty
        if (empty($validated['mid'])) {
            $validated['mid'] = null;
        }

        // ✅ Save the new registration
        $registration = SpfRegistration::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Registration successful',
            'data' => $registration
        ], 201);
    }




    private function getCityLookups(): array
    {
        return Cache::remember('city_lookups', 3600, function () {
            $response = Http::timeout(10)->get('https://mrm.sadhumargi.org/api/cities');
            $cities = $response->successful() ? $response->json('cities', []) : [];

            $cityMap   = [];
            $stateMap  = [];
            $anchalMap = [];

            foreach ($cities as $city) {
                $cityMap[$city['city_id']]     = $city['city_name'];
                $stateMap[$city['state_id']]   = $city['state_name'];
                $anchalMap[$city['anchal_id']] = $city['anchal_name'];
            }

            return compact('cityMap', 'stateMap', 'anchalMap', 'cities');
        });
    }

    private function getBranches(): array
    {
        return Cache::remember('branch_list', 3600, function () {
            try {
                $response = Http::timeout(10)->get('https://mrm.sadhumargi.org/api/branches');
                if ($response->successful()) {
                    return $response->json('branches', []);
                }
            } catch (\Exception $e) {}
            return [];
        });
    }


    private function applyMemberFilters($query, Request $request)
    {
        if ($request->filled('search_name')) {
            $query->where('full_name', 'like', '%' . $request->search_name . '%');
        }
        if ($request->filled('search_mid')) {
            $query->where('mid', 'like', '%' . $request->search_mid . '%');
        }
        if ($request->filled('search_phone')) {
            $query->where('mobile', 'like', '%' . $request->search_phone . '%');
        }
        if ($request->filled('age_from')) {
            $query->where('age', '>=', (int) $request->age_from);
        }
        if ($request->filled('age_to')) {
            $query->where('age', '<=', (int) $request->age_to);
        }
        if ($request->filled('prof_category')) {
            // profession column stores category ID — pass ID directly in URL
            $query->where('profession', $request->prof_category);
        }
        if ($request->filled('sub_category') && !is_numeric($request->sub_category)) {
            // sub_category sends sub-category name; professional_category column stores sub-cat name
            // Guard: ignore old stale numeric IDs from before the name-based change
            $query->where('professional_category', $request->sub_category);
        }
        if ($request->filled('city') && $request->city != '0') {
            $query->where('city', $request->city);
        }
        if ($request->filled('state') && $request->state != '0') {
            $query->where('state', $request->state);
        }
        if ($request->filled('local_sangh') && $request->local_sangh != '0') {
            $query->where('local_sangh_id', $request->local_sangh);
        }
        if ($request->filled('anchal') && $request->anchal != '0') {
            $query->where('anchal', $request->anchal);
        }
        if ($request->filled('referral')) {
            $query->where('referral', 'like', '%' . $request->referral . '%');
        }
        if ($request->filled('reg_from')) {
            $query->whereDate('created_at', '>=', $request->reg_from);
        }
        if ($request->filled('reg_to')) {
            $query->whereDate('created_at', '<=', $request->reg_to);
        }
        if ($request->filled('mid_status')) {
            if ($request->mid_status == 'with_mid') {
                $query->whereNotNull('mid')->where('mid', '!=', '')->where('mid', '!=', '0')->where('mid', '!=', '-');
            } elseif ($request->mid_status == 'without_mid') {
                $query->where(function($q) {
                    $q->whereNull('mid')
                      ->orWhere('mid', '')
                      ->orWhere('mid', '0')
                      ->orWhere('mid', '-');
                });
            }
        }
        return $query;
    }

    private function getCategoryMap(): array
    {
        return Category::pluck('category_name', 'id')->toArray();
    }

    private function getSubCategoryMap(): array
    {
        return SubCategory::pluck('sub_category_name', 'id')->toArray();
    }

    private function exportData($query, $request, $cityMap, $stateMap, $anchalMap, $categoryNameMap, $branches)
    {
        if (!$request->has('export')) {
            return null;
        }

        $members = $query->get();

        $branchMap = [];
        foreach ($branches as $b) {
            $branchMap[$b['id']] = $b['branch_name'];
        }

        $fields = $request->input('export_fields', \App\Exports\MembersExport::ALL_FIELDS);

        if ($request->export === 'excel') {
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\MembersExport($members, $cityMap, $stateMap, $anchalMap, $categoryNameMap, $branchMap, $fields),
                'members_export.xlsx'
            );
        }

        if ($request->export === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('spf_backend.members.pdf', compact('members', 'cityMap', 'stateMap', 'anchalMap', 'categoryNameMap', 'branchMap', 'fields'))->setPaper('a4', 'landscape');
            return $pdf->download('members_export.pdf');
        }

        return null;
    }

    public function approvedMembers(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $query = SpfRegistration::where('status', 'approved');

        if ($admin && $admin->isAnchalOperator()) {
            $query->where('anchal', $admin->anchal);
        }

        $this->applyMemberFilters($query, $request);

        $lookups  = $this->getCityLookups();
        $cityMap   = $lookups['cityMap']   ?? [];
        $stateMap  = $lookups['stateMap']  ?? [];
        $anchalMap = $lookups['anchalMap'] ?? [];
        $citiesRaw = $lookups['cities']    ?? [];
        $branches      = $this->getBranches();
        $categoryNameMap = $this->getCategoryMap();

        if ($exportResponse = $this->exportData(clone $query, $request, $cityMap, $stateMap, $anchalMap, $categoryNameMap, $branches)) {
            return $exportResponse;
        }

        $registrations = $query->latest()->paginate(25)->appends($request->query());
        $categories    = Category::where('status', 'Active')->orderBy('category_name')->get();
        $subCategories = SubCategory::where('status', 'Active')->orderBy('sub_category_name')->get();
        return view('spf_backend.members.approved', compact('registrations', 'cityMap', 'stateMap', 'anchalMap', 'categories', 'subCategories', 'citiesRaw', 'branches', 'categoryNameMap'));
    }

    public function rejectedMembers(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $query = SpfRegistration::where('status', 'rejected');

        if ($admin && $admin->isAnchalOperator()) {
            $query->where('anchal', $admin->anchal);
        }

        $this->applyMemberFilters($query, $request);

        $lookups  = $this->getCityLookups();
        $cityMap   = $lookups['cityMap']   ?? [];
        $stateMap  = $lookups['stateMap']  ?? [];
        $anchalMap = $lookups['anchalMap'] ?? [];
        $citiesRaw = $lookups['cities']    ?? [];
        $branches      = $this->getBranches();
        $categoryNameMap = $this->getCategoryMap();

        if ($exportResponse = $this->exportData(clone $query, $request, $cityMap, $stateMap, $anchalMap, $categoryNameMap, $branches)) {
            return $exportResponse;
        }

        $registrations = $query->latest()->paginate(25)->appends($request->query());
        $categories    = Category::where('status', 'Active')->orderBy('category_name')->get();
        $subCategories = SubCategory::where('status', 'Active')->orderBy('sub_category_name')->get();
        return view('spf_backend.members.rejected', compact('registrations', 'cityMap', 'stateMap', 'anchalMap', 'categories', 'subCategories', 'citiesRaw', 'branches', 'categoryNameMap'));
    }

    public function pendingMembers(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $query = SpfRegistration::where('status', 'pending');

        if ($admin && $admin->isAnchalOperator()) {
            $query->where('anchal', $admin->anchal);
        }

        $this->applyMemberFilters($query, $request);

        $lookups  = $this->getCityLookups();
        $cityMap   = $lookups['cityMap']   ?? [];
        $stateMap  = $lookups['stateMap']  ?? [];
        $anchalMap = $lookups['anchalMap'] ?? [];
        $citiesRaw = $lookups['cities']    ?? [];
        $branches      = $this->getBranches();
        $categoryNameMap = $this->getCategoryMap();

        if ($exportResponse = $this->exportData(clone $query, $request, $cityMap, $stateMap, $anchalMap, $categoryNameMap, $branches)) {
            return $exportResponse;
        }

        $registrations = $query->latest()->paginate(25)->appends($request->query());
        $categories    = Category::where('status', 'Active')->orderBy('category_name')->get();
        $subCategories = SubCategory::where('status', 'Active')->orderBy('sub_category_name')->get();
        return view('spf_backend.members.pending', compact('registrations', 'cityMap', 'stateMap', 'anchalMap', 'categories', 'subCategories', 'citiesRaw', 'branches', 'categoryNameMap'));
    }

    public function updateStatus(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        $request->validate(['status' => 'required|in:pending,approved,rejected']);
        $registration = SpfRegistration::findOrFail($id);

        if ($admin && !$admin->isSuperAdmin()) {
            abort(403, 'Only Super Admin can change status.');
        }

        $registration->update(['status' => $request->status]);

        // Make API call after status update
        if ($registration->mid) {
            $action = $request->status === 'approved' ? 'accept' : 'reject';
            $apiData = [
                'member_id' => $registration->mid,
                'action' => $action
            ];

            // Call external API
            try {
                Http::post('https://mrm.sadhumargi.org/api/member/update-membership-spf', $apiData);
            } catch (\Exception $e) {
                // Log error if needed
            }
        }

        return back()->with('success', 'Status updated successfully.');
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'ids' => 'required|string'
        ]);

        $ids = explode(',', $request->ids);
        if (empty($ids)) {
            return back()->with('error', 'No members selected.');
        }

        $admin = Auth::guard('admin')->user();
        if ($admin && !$admin->isSuperAdmin()) {
            abort(403, 'Only Super Admin can change status.');
        }
        $query = SpfRegistration::whereIn('id', $ids);
        $registrations = $query->get();

        foreach ($registrations as $registration) {
            $registration->update(['status' => $request->status]);

            // Make API call after status update
            if ($registration->mid) {
                $action = $request->status === 'approved' ? 'accept' : 'reject';
                $apiData = [
                    'member_id' => $registration->mid,
                    'action' => $action
                ];

                try {
                    \Illuminate\Support\Facades\Http::post('https://mrm.sadhumargi.org/api/member/update-membership-spf', $apiData);
                } catch (\Exception $e) {
                    // Log error if needed
                }
            }
        }

        return back()->with('success', count($registrations) . ' members have been ' . $request->status . '.');
    }

    public function approveAll(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $query = SpfRegistration::where('status', 'pending');

        if ($admin && !$admin->isSuperAdmin()) {
            abort(403, 'Only Super Admin can change status.');
        }

        $registrations = $query->get();

        if ($registrations->isEmpty()) {
            return back()->with('error', 'No pending members found to approve.');
        }

        foreach ($registrations as $registration) {
            $registration->update(['status' => 'approved']);

            // Make API call after status update
            if ($registration->mid) {
                $apiData = [
                    'member_id' => $registration->mid,
                    'action' => 'accept'
                ];

                try {
                    \Illuminate\Support\Facades\Http::post('https://mrm.sadhumargi.org/api/member/update-membership-spf', $apiData);
                } catch (\Exception $e) {
                    // Log error if needed
                }
            }
        }

        return back()->with('success', count($registrations) . ' pending members have been approved in bulk.');
    }

    public function memberAction(Request $request)
    {
        $request->validate([
            'member_id' => 'required|integer',
            'action' => 'required|in:accept,reject'
        ]);

        // Here you can add logic for what to do with the action
        // For example, log it or send to external API

        return response()->json(['message' => 'Action processed successfully']);
    }

    public function edit($id)
    {
        $admin = Auth::guard('admin')->user();
        $registration  = SpfRegistration::findOrFail($id);

        if ($admin && $admin->isAnchalOperator() && $registration->anchal != $admin->anchal) {
            abort(403, 'Unauthorized access to this member.');
        }
        $categories    = Category::where('status', 'Active')->orderBy('category_name')->get();
        $subCategories = SubCategory::where('status', 'Active')->orderBy('sub_category_name')->get();
        $branches      = $this->getBranches();
        return view('spf_backend.members.edit', compact('registration', 'categories', 'subCategories', 'branches'));
    }

    public function updateMember(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        $registration = SpfRegistration::findOrFail($id);

        if ($admin && $admin->isAnchalOperator() && $registration->anchal != $admin->anchal) {
            abort(403, 'Unauthorized access to this member.');
        }

        $validated = $request->validate([
            'mobile'                => 'required|min:10',
            'full_name'             => 'required|string|max:255',
            'father_name'           => 'required|string|max:255',
            'dob'                   => 'required|date',
            'age'                   => 'required|integer',
            'gender'                => 'required|string',
            'email'                 => 'nullable|email|max:255',
            'profession'            => 'nullable|string',
            'professional_category' => 'nullable|string|max:255',
            'state'                 => 'nullable|string',
            'city'                  => 'nullable|string',
            'anchal'                => 'nullable|string',
            'sadhumargi'            => 'nullable|string',
            'local_sangh_id'        => 'nullable|integer',
            'working_status'        => 'nullable|string|max:255',
            'hobbies'               => 'nullable|string',
            'referral'              => 'nullable|string|max:255',
            'mid'                   => 'nullable|string|max:100',
            'objectives'            => 'nullable|array',
            'source'                => 'nullable|string|max:100',
            'document_type'         => 'nullable|string|in:Professional degree,LinkedIn profile,Business card',
        ]);

        // Handle file upload if a new file is provided
        if ($request->hasFile('file')) {
            $file     = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $validated['file'] = $filename;
        }

        $registration->update($validated);

        return redirect()->back()->with('success', 'Member updated successfully.');
    }

    public function getUsers()
    {
        $users = SpfRegistration::select(
            'spf_registrations.*',
            DB::connection('sabsjs_member')->raw('(SELECT state_name FROM `sabsjs_member`.states WHERE states.state_id = spf_registrations.state) AS state_name'),
            DB::connection('sabsjs_member')->raw('(SELECT city_name FROM `sabsjs_member`.cities WHERE cities.city_id = spf_registrations.city) AS city_name'),
            DB::connection('sabsjs_member')->raw('(SELECT name FROM `sabsjs_member`.anchal WHERE anchal.anchal_id = spf_registrations.anchal) AS anchal_name')
        )->get();

        return response()->json($users);
    }
}
