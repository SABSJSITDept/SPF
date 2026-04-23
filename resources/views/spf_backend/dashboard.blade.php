@include('includes.backend.header')

<style>
    .dash-title {
        font-size: 22px;
        font-weight: 700;
        color: #1a237e;
        margin-bottom: 24px;
    }

    /* ── SUMMARY CARDS ─────────────────────────── */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 18px;
        margin-bottom: 36px;
    }

    .summary-card {
        background: #fff;
        border-radius: 12px;
        padding: 22px 20px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        display: flex;
        align-items: center;
        gap: 16px;
        border-left: 5px solid transparent;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .summary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.13);
    }

    .summary-card.total   { border-color: #1a237e; }
    .summary-card.approved { border-color: #2e7d32; }
    .summary-card.pending  { border-color: #f57f17; }
    .summary-card.rejected { border-color: #c62828; }

    .summary-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .summary-card.total   .summary-icon { background: #e8eaf6; color: #1a237e; }
    .summary-card.approved .summary-icon { background: #e8f5e9; color: #2e7d32; }
    .summary-card.pending  .summary-icon { background: #fff8e1; color: #f57f17; }
    .summary-card.rejected .summary-icon { background: #ffebee; color: #c62828; }

    .summary-info .count {
        font-size: 28px;
        font-weight: 800;
        line-height: 1;
        color: #1a1a2e;
    }

    .summary-info .label {
        font-size: 12.5px;
        color: #777;
        margin-top: 4px;
    }

    /* ── SECTION HEADING ───────────────────────── */
    .section-heading {
        font-size: 17px;
        font-weight: 700;
        color: #1a237e;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-heading::after {
        content: '';
        flex: 1;
        height: 2px;
        background: linear-gradient(90deg, #1a237e22, transparent);
        border-radius: 2px;
    }

    /* ── ANCHAL CARDS ──────────────────────────── */
    .anchal-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 16px;
    }

    .anchal-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px 18px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.07);
        display: flex;
        align-items: center;
        gap: 14px;
        transition: transform 0.2s, box-shadow 0.2s;
        border-top: 4px solid;
    }

    .anchal-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.12);
    }

    .anchal-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
        background: rgba(26,35,126,0.08);
        color: #1a237e;
    }

    .anchal-info .anchal-name {
        font-size: 13px;
        font-weight: 600;
        color: #222;
        white-space: normal;
        word-break: break-word;
        line-height: 1.35;
    }

    .anchal-info .anchal-count {
        font-size: 26px;
        font-weight: 800;
        color: #1a237e;
        line-height: 1.1;
    }

    .anchal-info .anchal-sub {
        font-size: 11px;
        color: #999;
    }

    .no-data {
        text-align: center;
        padding: 50px;
        color: #aaa;
        font-size: 15px;
    }

    /* Color rotation for anchal cards */
    .anchal-card:nth-child(6n+1) { border-color: #1a237e; }
    .anchal-card:nth-child(6n+2) { border-color: #00695c; }
    .anchal-card:nth-child(6n+3) { border-color: #6a1b9a; }
    .anchal-card:nth-child(6n+4) { border-color: #e65100; }
    .anchal-card:nth-child(6n+5) { border-color: #c62828; }
    .anchal-card:nth-child(6n+0) { border-color: #0277bd; }

    .anchal-card:nth-child(6n+1) .anchal-info .anchal-count { color: #1a237e; }
    .anchal-card:nth-child(6n+2) .anchal-info .anchal-count { color: #00695c; }
    .anchal-card:nth-child(6n+3) .anchal-info .anchal-count { color: #6a1b9a; }
    .anchal-card:nth-child(6n+4) .anchal-info .anchal-count { color: #e65100; }
    .anchal-card:nth-child(6n+5) .anchal-info .anchal-count { color: #c62828; }
    .anchal-card:nth-child(6n+0) .anchal-info .anchal-count { color: #0277bd; }
</style>

<h1 class="dash-title">&#127968;&nbsp; Dashboard</h1>

{{-- ── SUMMARY CARDS ─────────────────────────────────────── --}}
<div class="summary-grid">
    <div class="summary-card total">
        <div class="summary-icon">&#128101;</div>
        <div class="summary-info">
            <div class="count">{{ $totalMembers }}</div>
            <div class="label">Total Members</div>
        </div>
    </div>
    <div class="summary-card approved">
        <div class="summary-icon">&#10003;</div>
        <div class="summary-info">
            <div class="count">{{ $approvedCount }}</div>
            <div class="label">Approved</div>
        </div>
    </div>
    <div class="summary-card pending">
        <div class="summary-icon">&#8987;</div>
        <div class="summary-info">
            <div class="count">{{ $pendingCount }}</div>
            <div class="label">Pending</div>
        </div>
    </div>
    <div class="summary-card rejected">
        <div class="summary-icon">&#10007;</div>
        <div class="summary-info">
            <div class="count">{{ $rejectedCount }}</div>
            <div class="label">Rejected</div>
        </div>
    </div>
</div>

{{-- ── ANCHAL-WISE CARDS ─────────────────────────────────── --}}
<div class="section-heading">&#127757;&nbsp; Anchal-wise Members</div>

@if($anchalStats->isEmpty())
    <div class="no-data">No registration data found.</div>
@else
<div class="anchal-grid">
    @foreach($anchalStats as $stat)
    <div class="anchal-card">
        <div class="anchal-icon">&#127759;</div>
        <div class="anchal-info">
            <div class="anchal-name" title="{{ $stat->anchal_label }}">{{ $stat->anchal_label }}</div>
            <div class="anchal-count">{{ $stat->total }}</div>
            <div class="anchal-sub">Members</div>
        </div>
    </div>
    @endforeach
</div>
@endif

@include('includes.backend.footer')
