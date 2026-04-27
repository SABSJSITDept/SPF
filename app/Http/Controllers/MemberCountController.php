<?php

namespace App\Http\Controllers;

use App\Models\SpfRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class MemberCountController extends Controller
{
    /**
     * Open API – Anchal-wise, Local-Sangh-wise total member count.
     *
     * Query Params (all optional):
     *   status   – filter by member status (approved | pending | rejected)
     *              Default: returns ALL statuses (total count)
     *
     * Response shape:
     * {
     *   "generated_at": "...",
     *   "status_filter": "all | approved | ...",
     *   "grand_total": 1234,
     *   "anchal_wise": [
     *     {
     *       "anchal_id": 1,
     *       "anchal_name": "Mewar",
     *       "anchal_total": 300,
     *       "local_sanghs": [
     *         { "local_sangh_id": 284, "local_sangh_name": "Udaipur", "total": 45 },
     *         ...
     *       ]
     *     },
     *     ...
     *   ]
     * }
     */
    public function memberCountByAnchalAndSangh(Request $request)
    {
        // ── 1. Optional status filter ───────────────────────────────────
        $statusFilter = $request->query('status');
        $allowedStatuses = ['approved', 'pending', 'rejected'];

        $query = SpfRegistration::query();
        if ($statusFilter && in_array($statusFilter, $allowedStatuses)) {
            $query->where('status', $statusFilter);
        }

        // ── 2. Group by anchal + local_sangh_id ─────────────────────────
        $rows = $query
            ->selectRaw('anchal, local_sangh_id, COUNT(*) as total')
            ->groupBy('anchal', 'local_sangh_id')
            ->orderByRaw('CAST(anchal AS UNSIGNED) ASC')
            ->get();

        // ── 3. Fetch branch name map from external API (cached 1 hour) ──
        $branches = Cache::remember('branch_list_api', 3600, function () {
            try {
                $response = Http::timeout(10)->get('https://mrm.sadhumargi.org/api/branches');
                if ($response->successful()) {
                    return $response->json('branches', []);
                }
            } catch (\Exception $e) {
                // Fall back silently
            }
            return [];
        });

        // Build lookup maps
        $branchNameMap  = [];   // branch_id  => branch_name
        $anchalNameMap  = [];   // anchal_id  => anchal_name
        $branchAnchalMap = [];  // branch_id  => anchal_id

        foreach ($branches as $branch) {
            $branchId   = (int) $branch['id'];
            $anchalId   = (int) $branch['anchal_id'];
            $branchNameMap[$branchId]   = $branch['branch_name'];
            $anchalNameMap[$anchalId]   = $branch['anchal_name'];
            $branchAnchalMap[$branchId] = $anchalId;
        }

        // ── 4. Aggregate into anchal → local_sanghs structure ───────────
        $anchalData  = [];   // anchal_id => [ anchal_name, total, local_sanghs[] ]
        $grandTotal  = 0;

        foreach ($rows as $row) {
            $anchalId      = (int) $row->anchal;
            $localSanghId  = (int) $row->local_sangh_id;
            $count         = (int) $row->total;
            $grandTotal   += $count;

            // Resolve anchal name
            $anchalName = $anchalNameMap[$anchalId] ?? "Anchal #{$anchalId}";

            // Resolve local sangh name
            $localSanghName = $localSanghId
                ? ($branchNameMap[$localSanghId] ?? "Local Sangh #{$localSanghId}")
                : 'Not Assigned';

            // Init anchal bucket
            if (!isset($anchalData[$anchalId])) {
                $anchalData[$anchalId] = [
                    'anchal_id'    => $anchalId,
                    'anchal_name'  => $anchalName,
                    'anchal_total' => 0,
                    'local_sanghs' => [],
                ];
            }

            $anchalData[$anchalId]['anchal_total'] += $count;
            $anchalData[$anchalId]['local_sanghs'][] = [
                'local_sangh_id'   => $localSanghId ?: null,
                'local_sangh_name' => $localSanghName,
                'total'            => $count,
            ];
        }

        // Sort local_sanghs within each anchal by name
        foreach ($anchalData as &$anchal) {
            usort($anchal['local_sanghs'], fn ($a, $b) => strcmp($a['local_sangh_name'], $b['local_sangh_name']));
        }
        unset($anchal);

        // Sort anchals by anchal_id ascending
        ksort($anchalData);

        return response()->json([
            'generated_at'  => now()->toIso8601String(),
            'status_filter' => $statusFilter ?: 'all',
            'grand_total'   => $grandTotal,
            'anchal_wise'   => array_values($anchalData),
        ]);
    }
}
