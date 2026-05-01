@include('includes.backend.header')

    <style>
        h1 { margin-bottom: 20px; font-size: 22px; color: #1a237e; }
        .badge { background: #c62828; color: #fff; font-size: 12px; padding: 3px 10px; border-radius: 12px; margin-left: 10px; }
        .table-wrapper { overflow-x: auto; overflow-y: auto; max-height: calc(100vh - 260px); background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        thead { background: #1a237e; color: #fff; }
        thead th { padding: 12px 14px; text-align: left; white-space: nowrap; position: sticky; top: 0; z-index: 2; background: #1a237e; }
        tbody tr:nth-child(even) { background: #f9fafc; }
        tbody tr:hover { background: #edf2ff; }
        tbody td { padding: 10px 14px; vertical-align: middle; white-space: nowrap; border-bottom: 1px solid #eee; }
        .no-records { text-align: center; padding: 40px; color: #888; font-size: 15px; }
        .file-link { color: #1565c0; text-decoration: none; }
        .file-link:hover { text-decoration: underline; }
        .col-profession, .col-profcat, .col-fullname, .col-fathername, .col-objectives, .col-hobbies, .col-referral { 
            white-space: normal !important; 
            min-width: 150px; 
            max-width: 280px; 
            word-break: break-word; 
        }
        .status-badge { display:inline-block; padding:3px 10px; border-radius:12px; font-size:11px; font-weight:600; background:#ffebee; color:#c62828; }
        .breadcrumb { font-size: 13px; color: #888; margin-bottom: 16px; }
        .breadcrumb a { color: #1a237e; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        /* Column filter */
        .col-filter-bar { background:#fff; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,.08); padding:12px 16px; margin-bottom:16px; }
        .col-filter-bar .toggle-btn { background:#1a237e; color:#fff; border:none; padding:6px 14px; border-radius:5px; font-size:12px; cursor:pointer; margin-bottom:0; }
        .col-filter-bar .toggle-btn:hover { background:#283593; }
        /* Portrait modals */
        .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:9999; align-items:flex-start; justify-content:center; padding-top:70px; }
        .modal-overlay.open { display:flex; }
        .modal-box { background:#fff; border-radius:14px; width:340px; max-height:78vh; overflow-y:auto; box-shadow:0 8px 36px rgba(0,0,0,0.25); }
        .modal-header { padding:13px 18px; display:flex; justify-content:space-between; align-items:center; border-radius:14px 14px 0 0; position:sticky; top:0; z-index:1; }
        .modal-title { color:#fff; font-weight:700; font-size:14px; }
        .modal-close-btn { background:rgba(255,255,255,0.2); border:none; color:#fff; width:26px; height:26px; border-radius:50%; cursor:pointer; font-size:18px; line-height:26px; text-align:center; padding:0; }
        .modal-close-btn:hover { background:rgba(255,255,255,0.4); }
        .modal-grid { padding:12px; display:grid; grid-template-columns:1fr 1fr; gap:3px; }
        .modal-row { display:flex; justify-content:space-between; align-items:center; padding:7px 10px; border-radius:7px; font-size:13px; cursor:pointer; color:#333; transition:background .12s; user-select:none; }
        .modal-row:hover { background:#f0f4ff; }
        .modal-row.is-checked { background:#e8eaf6; color:#1a237e; font-weight:600; }
        .modal-row input[type=checkbox] { width:15px; height:15px; accent-color:#1a237e; cursor:pointer; flex-shrink:0; }
        /* Pagination */
        .pagination-wrap { display:flex; justify-content:center; align-items:center; padding:14px 0; gap:4px; }
        .pagination-wrap a, .pagination-wrap span { display:inline-block; min-width:32px; padding:4px 9px; border-radius:4px; font-size:13px; text-align:center; border:1px solid #ddd; text-decoration:none; color:#1a237e; }
        .pagination-wrap span.active { background:#1a237e; color:#fff; border-color:#1a237e; font-weight:600; }
        .pagination-wrap span.disabled { color:#bbb; border-color:#eee; cursor:default; }
        .pagination-wrap a:hover { background:#edf2ff; }

        /* ── Advanced Search Filter Panel ── */
        .filter-card { background:#fff; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,.09); margin-bottom:16px; overflow:hidden; border:1px solid #e8eaf6; }
        .filter-card-header { display:flex; align-items:center; justify-content:space-between; padding:11px 16px; background:linear-gradient(90deg,#1a237e,#283593); cursor:pointer; user-select:none; }
        .filter-card-header span { color:#fff; font-size:13px; font-weight:600; display:flex; align-items:center; gap:7px; }
        .filter-card-header .filter-arrow { color:#fff; font-size:12px; transition:transform .25s; }
        .filter-card-header.open .filter-arrow { transform:rotate(180deg); }
        .filter-card-body { display:none; padding:16px; }
        .filter-card-body.open { display:block; }
        .filter-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:12px 16px; }
        .filter-group { display:flex; flex-direction:column; gap:4px; }
        .filter-group label { font-size:11px; font-weight:600; color:#555; text-transform:uppercase; letter-spacing:.4px; }
        .filter-group input, .filter-group select {
            border:1px solid #dde1f0; border-radius:5px; padding:6px 9px; font-size:12px; color:#333;
            outline:none; transition:border-color .2s, box-shadow .2s; background:#fafbff;
        }
        .filter-group input:focus, .filter-group select:focus { border-color:#3949ab; box-shadow:0 0 0 2px rgba(57,73,171,.12); }
        .filter-row-actions { display:flex; gap:8px; margin-top:14px; align-items:center; flex-wrap:wrap; }
        .btn-filter-apply { background:#1a237e; color:#fff; border:none; padding:7px 20px; border-radius:5px; font-size:12px; font-weight:600; cursor:pointer; transition:background .2s; }
        .btn-filter-apply:hover { background:#283593; }
        .btn-filter-reset { background:#f5f5f5; color:#555; border:1px solid #ddd; padding:7px 16px; border-radius:5px; font-size:12px; cursor:pointer; text-decoration:none; display:inline-block; transition:background .2s; }
        .btn-filter-reset:hover { background:#ebebeb; }
        .filter-card-header span.filter-active-count { background:#e8eaf6; color:#3949ab; border-radius:12px; padding:2px 10px; font-size:11px; font-weight:700; margin-left:4px; }
        .filter-result-bar { display:flex; align-items:center; flex-wrap:wrap; gap:8px; background:#e8f5e9; border:1px solid #a5d6a7; border-radius:7px; padding:10px 16px; margin-bottom:14px; font-size:13px; color:#1b5e20; }
        .filter-result-bar .fbar-count { font-size:18px; font-weight:800; color:#2e7d32; margin-right:4px; }
        .filter-result-bar .fbar-tag { background:#fff; border:1px solid #81c784; border-radius:5px; padding:2px 10px; font-size:12px; color:#2e7d32; font-weight:600; }
        .age-range-group { display:flex; gap:6px; align-items:center; }
        .age-range-group input { flex:1; min-width:0; }
        .age-range-group span { font-size:11px; color:#999; }
    </style>

    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a> &rsaquo; Members &rsaquo; Rejected
        </div>

        <h1>Rejected Members <span class="badge">{{ $registrations->total() }} Records</span></h1>

        @if(session('success'))
            <div style="background:#e8f5e9;color:#2e7d32;padding:10px 16px;border-radius:6px;margin-bottom:16px;font-size:13px;">
                {{ session('success') }}
            </div>
        @endif

        {{-- ── Advanced Search & Filter Panel ── --}}
        @php
            $activeFilters = collect(['search_name','search_mid','search_phone','age_from','age_to','prof_category','city','state','local_sangh','anchal','referral','reg_from','reg_to','mid_status'])->filter(fn($k) => request()->filled($k))->count();
            // sub_category only counts if it's a name (not a stale numeric ID)
            if (request()->filled('sub_category') && !is_numeric(request('sub_category'))) {
                $activeFilters++;
            }
        @endphp
        <div class="filter-card">
            <div class="filter-card-header {{ $activeFilters ? 'open' : '' }}" onclick="toggleFilterPanel()" id="filterHeader">
                <span>
                    &#128269; Search &amp; Filter
                    @if($activeFilters)
                        <span class="filter-active-count">{{ $activeFilters }} active</span>
                    @endif
                </span>
                <span class="filter-arrow">&#9660;</span>
            </div>
            <div class="filter-card-body {{ $activeFilters ? 'open' : '' }}" id="filterBody">
                <form method="GET" action="{{ url()->current() }}" id="filterForm">
                    <div class="filter-grid">

                        <div class="filter-group">
                            <label>MID Status</label>
                            <select name="mid_status">
                                <option value="">— All —</option>
                                <option value="with_mid" {{ request('mid_status') == 'with_mid' ? 'selected' : '' }}>With MID</option>
                                <option value="without_mid" {{ request('mid_status') == 'without_mid' ? 'selected' : '' }}>Without MID</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>Name</label>
                            <input type="text" name="search_name" value="{{ request('search_name') }}" placeholder="Search name…">
                        </div>

                        <div class="filter-group">
                            <label>MID</label>
                            <input type="text" name="search_mid" value="{{ request('search_mid') }}" placeholder="Search MID…">
                        </div>

                        <div class="filter-group">
                            <label>Phone</label>
                            <input type="text" name="search_phone" value="{{ request('search_phone') }}" placeholder="Search phone…">
                        </div>

                        <div class="filter-group">
                            <label>Age Group</label>
                            <div class="age-range-group">
                                <input type="number" name="age_from" value="{{ request('age_from') }}" placeholder="From" min="0" max="150">
                                <span>–</span>
                                <input type="number" name="age_to" value="{{ request('age_to') }}" placeholder="To" min="0" max="150">
                            </div>
                        </div>

                        <div class="filter-group">
                            <label>Prof. Category</label>
                            <select name="prof_category" id="filterProfCat" onchange="filterSubCats(true)">
                                <option value="">— All Categories —</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ request('prof_category') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>Sub Category (Profession)</label>
                            <select name="sub_category" id="filterSubCat">
                                <option value="">— All Sub-Categories —</option>
                                @foreach($subCategories as $sub)
                                    <option value="{{ $sub->id }}"
                                        data-cat-id="{{ $sub->category_id }}"
                                        {{ request('sub_category') == $sub->id ? 'selected' : '' }}>
                                        {{ $sub->sub_category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>State</label>
                            <select name="state" id="filterState" onchange="filterCitiesOnState(true);filterAnchalsOnState(true);filterLocalSanghOnAnchal(true);">
                                <option value="">— All States —</option>
                                @php
                                    $uniqueStates = collect($citiesRaw)->unique('state_id')->sortBy('state_name');
                                @endphp
                                @foreach($uniqueStates as $s)
                                    @if(!empty($s['state_id']))
                                    <option value="{{ $s['state_id'] }}"
                                        {{ request('state') == $s['state_id'] ? 'selected' : '' }}>
                                        {{ $s['state_name'] }}
                                    </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>City</label>
                            <select name="city" id="filterCity">
                                <option value="">— All Cities —</option>
                                @foreach(collect($citiesRaw)->sortBy('city_name') as $c)
                                    @if(!empty($c['city_id']))
                                    <option value="{{ $c['city_id'] }}"
                                        data-state-id="{{ $c['state_id'] }}"
                                        {{ request('city') == $c['city_id'] ? 'selected' : '' }}>
                                        {{ $c['city_name'] }}
                                    </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>Anchal</label>
                            <select name="anchal" id="filterAnchal" onchange="filterLocalSanghOnAnchal(true)">
                                <option value="">— All Anchals —</option>
                                @php
                                    $uniqueAnchals = collect($citiesRaw)->unique('anchal_id')->sortBy('anchal_name');
                                @endphp
                                @foreach($uniqueAnchals as $a)
                                    @if(!empty($a['anchal_id']))
                                    <option value="{{ $a['anchal_id'] }}"
                                        data-state-id="{{ $a['state_id'] }}"
                                        {{ request('anchal') == $a['anchal_id'] ? 'selected' : '' }}>
                                        {{ $a['anchal_name'] }}
                                    </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>Local Sangh</label>
                            <select name="local_sangh" id="filterLocalSangh">
                                <option value="">— All Local Sanghs —</option>
                                @foreach($branches as $b)
                                    @if(!empty($b['id']))
                                    <option value="{{ $b['id'] }}"
                                        data-anchal-id="{{ $b['anchal_id'] ?? '' }}"
                                        data-state-id="{{ $b['state_id'] ?? '' }}"
                                        {{ request('local_sangh') == $b['id'] ? 'selected' : '' }}>
                                        {{ $b['branch_name'] }}
                                    </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>Referral</label>
                            <input type="text" name="referral" value="{{ request('referral') }}" placeholder="Referral…">
                        </div>

                        <div class="filter-group">
                            <label>Registered At — From</label>
                            <input type="date" name="reg_from" value="{{ request('reg_from') }}">
                        </div>

                        <div class="filter-group">
                            <label>Registered At — To</label>
                            <input type="date" name="reg_to" value="{{ request('reg_to') }}">
                        </div>

                    </div>
                    {{-- Export Field Selector Modal (inside form so checkboxes are submitted) --}}
                    <div id="exportFieldsModal" class="modal-overlay" onclick="if(event.target===this)closeExportModal()">
                        <div class="modal-box" style="width:360px;">
                            <div class="modal-header" style="background:#3949ab;">
                                <span class="modal-title">&#9881; Select Fields to Export</span>
                                <button type="button" class="modal-close-btn" onclick="closeExportModal()">&times;</button>
                            </div>
                            <div class="modal-grid">
                                @php $efIdx = 1; @endphp
                                @foreach(['mid'=>'MID','full_name'=>'Full Name','father_name'=>'Father Name','mobile'=>'Mobile','email'=>'Email','gender'=>'Gender','age'=>'Age','dob'=>'DOB','profession'=>'Profession','prof_category'=>'Prof. Category','state'=>'State','city'=>'City','anchal'=>'Anchal','local_sangh'=>'Local Sangh','sadhumargi'=>'Sadhumargi','working_status'=>'Working Status','referral'=>'Referral'] as $fval => $flabel)
                                <label class="modal-row is-checked" id="ef-{{ $fval }}">
                                    <span>{{ $efIdx++ }}: {{ $flabel }}</span>
                                    <input type="checkbox" name="export_fields[]" value="{{ $fval }}" checked>
                                </label>
                                @endforeach
                            </div>
                            <div style="padding:8px 14px 6px;font-size:11px;color:#888;border-top:1px solid #eee;">
                                <strong>PDF:</strong> State, City &amp; Anchal &rarr; &ldquo;Location&rdquo;. &nbsp;<strong>Excel:</strong> Each as separate column.
                            </div>
                            <div style="padding:8px 14px 12px;display:flex;justify-content:flex-end;">
                                <button type="button" onclick="closeExportModal()" style="background:#3949ab;color:#fff;border:none;padding:6px 20px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;">Done</button>
                            </div>
                        </div>
                    </div>

                    <div class="filter-row-actions">
                        <button type="submit" class="btn-filter-apply">&#128269; Apply Filters</button>
                        <a href="{{ url()->current() }}" class="btn-filter-reset">&#10006; Reset</a>
                        <span style="margin-left:auto;display:flex;gap:8px;align-items:center;">
                            <button type="button" onclick="openExportModal()" style="background:#e8eaf6;color:#3949ab;border:1px solid #c5cae9;padding:7px 14px;border-radius:5px;font-size:12px;cursor:pointer;font-weight:600;">&#9881; Export Fields</button>
                            <button type="submit" name="export" value="excel" class="btn-filter-apply" style="background-color:#10b981;">&#128202; Export Excel</button>
                            <button type="submit" name="export" value="pdf" class="btn-filter-apply" style="background-color:#ef4444;">&#128196; Export PDF</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>

        {{-- Column Visibility Filter --}}

        {{-- ── Filter Result Summary Bar ── --}}
        @if($activeFilters)
        @php
            $lookupNames = [];
            if (request()->filled('mid_status')) {
                $lookupNames[] = 'MID Status: ' . (request('mid_status') == 'with_mid' ? 'With MID' : 'Without MID');
            }
            if (request()->filled('prof_category') && request('prof_category') != '0')
                $lookupNames[] = 'Prof. Category: ' . ($categoryNameMap[request('prof_category')] ?? request('prof_category'));
            if (request()->filled('sub_category') && !is_numeric(request('sub_category')))
                $lookupNames[] = 'Sub Category: ' . request('sub_category');
            if (request()->filled('search_name'))   $lookupNames[] = 'Name: "' . request('search_name') . '"';
            if (request()->filled('search_mid'))    $lookupNames[] = 'MID: ' . request('search_mid');
            if (request()->filled('search_phone'))  $lookupNames[] = 'Phone: ' . request('search_phone');
            if (request()->filled('age_from') || request()->filled('age_to'))
                $lookupNames[] = 'Age: ' . (request('age_from') ?: '?') . ' – ' . (request('age_to') ?: '?');
            if (request()->filled('state') && request('state') != '0')
                $lookupNames[] = 'State: ' . ($stateMap[request('state')] ?? request('state'));
            if (request()->filled('city') && request('city') != '0')
                $lookupNames[] = 'City: ' . ($cityMap[request('city')] ?? request('city'));
            if (request()->filled('anchal') && request('anchal') != '0')
                $lookupNames[] = 'Anchal: ' . ($anchalMap[request('anchal')] ?? request('anchal'));
            if (request()->filled('local_sangh') && request('local_sangh') != '0')
                $lookupNames[] = 'Local Sangh: ' . request('local_sangh');
            if (request()->filled('referral'))      $lookupNames[] = 'Referral: ' . request('referral');
            if (request()->filled('reg_from'))      $lookupNames[] = 'From: ' . request('reg_from');
            if (request()->filled('reg_to'))        $lookupNames[] = 'To: ' . request('reg_to');
        @endphp
        <div class="filter-result-bar">
            <span><span class="fbar-count">{{ $registrations->total() }}</span> records found for:</span>
            @foreach($lookupNames as $tag)
                <span class="fbar-tag">{{ $tag }}</span>
            @endforeach
            <a href="{{ url()->current() }}" style="margin-left:auto;font-size:12px;color:#c62828;text-decoration:none;font-weight:600;">&#10006; Clear filters</a>
        </div>
        @endif

        <div class="col-filter-bar">
            <button class="toggle-btn" onclick="openColModal()">&#9881; Show / Hide Columns</button>
        </div>
        {{-- Column Visibility Modal --}}
        <div id="colModal" class="modal-overlay" onclick="if(event.target===this)closeColModal()">
            <div class="modal-box">
                <div class="modal-header" style="background:#1a237e;">
                    <span class="modal-title">Column Visibility Control</span>
                    <button type="button" class="modal-close-btn" onclick="closeColModal()">&times;</button>
                </div>
                <div class="modal-grid">
                    <label class="modal-row" id="cv-col-id"><span>1: ID</span><input type="checkbox" class="col-toggle" data-col="col-id"></label>
                    <label class="modal-row is-checked" id="cv-col-mid"><span>2: MID</span><input type="checkbox" class="col-toggle" data-col="col-mid" checked></label>
                    <label class="modal-row is-checked" id="cv-col-fullname"><span>3: Full Name</span><input type="checkbox" class="col-toggle" data-col="col-fullname" checked></label>
                    <label class="modal-row is-checked" id="cv-col-state"><span>12: State</span><input type="checkbox" class="col-toggle" data-col="col-state" checked></label>
                    <label class="modal-row is-checked" id="cv-col-city"><span>13: City</span><input type="checkbox" class="col-toggle" data-col="col-city" checked></label>
                    <label class="modal-row is-checked" id="cv-col-file"><span>14: Document</span><input type="checkbox" class="col-toggle" data-col="col-file" checked></label>
                    <label class="modal-row" id="cv-col-doctype"><span>15: Document Type</span><input type="checkbox" class="col-toggle" data-col="col-doctype"></label>
                    <label class="modal-row" id="cv-col-anchal"><span>16: Anchal</span><input type="checkbox" class="col-toggle" data-col="col-anchal"></label>
                    <label class="modal-row" id="cv-col-sadhumargi"><span>17: Sadhumargi</span><input type="checkbox" class="col-toggle" data-col="col-sadhumargi"></label>
                    <label class="modal-row" id="cv-col-hobbies"><span>18: Hobbies</span><input type="checkbox" class="col-toggle" data-col="col-hobbies"></label>
                    <label class="modal-row" id="cv-col-referral"><span>19: Referral</span><input type="checkbox" class="col-toggle" data-col="col-referral"></label>
                    <label class="modal-row" id="cv-col-objectives"><span>20: Objectives</span><input type="checkbox" class="col-toggle" data-col="col-objectives"></label>
                    <label class="modal-row" id="cv-col-source"><span>21: Source</span><input type="checkbox" class="col-toggle" data-col="col-source"></label>
                    <label class="modal-row" id="cv-col-workstatus"><span>22: Working Status</span><input type="checkbox" class="col-toggle" data-col="col-workstatus"></label>
                    <label class="modal-row" id="cv-col-status"><span>23: Status</span><input type="checkbox" class="col-toggle" data-col="col-status"></label>
                    <label class="modal-row" id="cv-col-regat"><span>24: Reg. At</span><input type="checkbox" class="col-toggle" data-col="col-regat"></label>
                </div>
            </div>
        </div>

        <div class="table-wrapper">
            @if($registrations->isEmpty())
                <p class="no-records">No rejected registrations found.</p>
            @else
            <table id="membersTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="col-id">ID</th>
                        <th class="col-mid">MID</th>
                        <th class="col-fullname">Full Name</th>
                        <th class="col-file">File</th>
                        <th class="col-doctype">Document Type</th>
                        <th class="col-fathername">Father Name</th>
                        <th class="col-dob">DOB</th>
                        <th class="col-age">Age</th>
                        <th class="col-gender">Gender</th>
                        <th class="col-mobile">Mobile</th>
                        <th class="col-email">Email</th>
                        <th class="col-profession">Profession</th>
                        <th class="col-profcat">Prof. Category</th>
                        <th class="col-state">State</th>
                        <th class="col-city">City</th>
                        <th class="col-file">Document</th>
                        <th class="col-doctype">Document Type</th>
                        <th class="col-anchal">Anchal</th>
                        <th class="col-sadhumargi">Sadhumargi</th>
                        <th class="col-hobbies">Hobbies</th>
                        <th class="col-referral">Referral</th>
                        <th class="col-objectives">Objectives</th>
                        <th class="col-source">Source</th>
                        <th class="col-workstatus">Working Status</th>
                        <th class="col-status">Status</th>
                        <th class="col-regat">Registered At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registrations as $index => $reg)
                    <tr>
                        <td>{{ ($registrations->currentPage() - 1) * $registrations->perPage() + $index + 1 }}</td>
                        <td class="col-id">{{ $reg->id }}</td>
                        <td class="col-mid">{{ $reg->mid ?? '-' }}</td>
                        <td class="col-fullname">{{ $reg->full_name }}</td>
                        <td class="col-file">
                            @if($reg->file)
                                <a class="file-link" href="{{ asset('uploads/' . $reg->file) }}" target="_blank" title="View Document">
                                    <span style="color:#ef4444;font-size:16px;">&#128196;</span> View
                                </a>
                            @else
                                <span style="color:#ccc;">-</span>
                            @endif
                        </td>
                        <td class="col-doctype">{{ $reg->document_type ?? '-' }}</td>
                        <td class="col-fathername">{{ $reg->father_name }}</td>
                        <td class="col-dob">{{ $reg->dob ? \Carbon\Carbon::parse($reg->dob)->format('d-m-Y') : '-' }}</td>
                        <td class="col-age">{{ $reg->age }}</td>
                        <td class="col-gender">{{ $reg->gender }}</td>
                        <td class="col-mobile">{{ $reg->mobile }}</td>
                        <td class="col-email">{{ $reg->email ?? '-' }}</td>
                        <td class="col-profession">{{ $reg->professional_category ?? '-' }}</td>
                        <td class="col-profcat">{{ $categoryNameMap[$reg->profession] ?? '-' }}</td>
                        <td class="col-state">{{ $stateMap[$reg->state] ?? $reg->state }}</td>
                        <td class="col-city">{{ $cityMap[$reg->city] ?? $reg->city }}</td>
                        <td class="col-file">
                            @if($reg->file)
                                <a class="file-link" href="{{ asset('uploads/' . $reg->file) }}" target="_blank" title="View Document">
                                    <span style="color:#ef4444;font-size:16px;">&#128196;</span> View
                                </a>
                            @else
                                <span style="color:#ccc;">-</span>
                            @endif
                        </td>
                        <td class="col-doctype">{{ $reg->document_type ?? '-' }}</td>
                        <td class="col-anchal">{{ $anchalMap[$reg->anchal] ?? $reg->anchal }}</td>
                        <td class="col-sadhumargi">{{ $reg->sadhumargi }}</td>
                        <td class="col-hobbies">{{ $reg->hobbies ?? '-' }}</td>
                        <td class="col-referral">{{ $reg->referral ?? '-' }}</td>
                        <td class="col-objectives">
                            @if($reg->objectives)
                                {{ implode(', ', (array) $reg->objectives) }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="col-source">{{ $reg->source ?? '-' }}</td>
                        <td class="col-workstatus">{{ $reg->working_status ?? '-' }}</td>
                        <td class="col-status"><span class="status-badge">Rejected</span></td>
                        <td class="col-regat">{{ $reg->created_at->format('d-m-Y H:i') }}</td>
                        <td>
                            @if(Auth::guard('admin')->user()->isSuperAdmin())
                            <form method="POST" action="{{ route('admin.members.updateStatus', $reg->id) }}" style="display:inline;">
                                @csrf
                                <input type="hidden" name="status" value="pending">
                                <button type="button" onclick="confirmSubmit(this.form, 'Bring this member back to Pending?', '#e65100')"
                                    style="background:#e65100;color:#fff;border:none;padding:4px 10px;border-radius:4px;font-size:12px;cursor:pointer;">
                                    Bring Back
                                </button>
                            </form>
                            @endif
                            @if(Auth::guard('admin')->user() && !Auth::guard('admin')->user()->isAnchalOperator())
                            <a href="{{ route('admin.members.edit', $reg->id) }}"
                                style="background:#1565c0;color:#fff;border:none;padding:4px 10px;border-radius:4px;font-size:12px;cursor:pointer;text-decoration:none;display:inline-block;margin-left:4px;">
                                Edit
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            @if($registrations->lastPage() > 1)
            <div class="pagination-wrap">
                @if($registrations->onFirstPage())
                    <span class="disabled">&laquo;</span>
                @else
                    <a href="{{ $registrations->previousPageUrl() }}">&laquo;</a>
                @endif

                @php
                    $start = max(1, $registrations->currentPage() - 2);
                    $end = min($registrations->lastPage(), $registrations->currentPage() + 2);
                @endphp

                @if($start > 1)
                    <a href="{{ $registrations->url(1) }}">1</a>
                    @if($start > 2)
                        <span class="disabled">...</span>
                    @endif
                @endif

                @foreach(range($start, $end) as $page)
                    @if($page == $registrations->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $registrations->url($page) }}">{{ $page }}</a>
                    @endif
                @endforeach

                @if($end < $registrations->lastPage())
                    @if($end < $registrations->lastPage() - 1)
                        <span class="disabled">...</span>
                    @endif
                    <a href="{{ $registrations->url($registrations->lastPage()) }}">{{ $registrations->lastPage() }}</a>
                @endif

                @if($registrations->hasMorePages())
                    <a href="{{ $registrations->nextPageUrl() }}">&raquo;</a>
                @else
                    <span class="disabled">&raquo;</span>
                @endif
            </div>
            @endif

            @endif
        </div>
    </div>

    <script type="text/data" id="__catNameToId">{!! json_encode($categories->pluck('id', 'category_name')) !!}</script>
    <script>
        /* ── Category → Sub-Category cascade ── */
        var allSubCatOptions = (function() {
            var opts = [];
            document.querySelectorAll('#filterSubCat option[data-cat-id]').forEach(function(o) {
                opts.push({ val: o.value, catId: o.getAttribute('data-cat-id'), text: o.text });
            });
            return opts;
        })();

        function filterSubCats(reset) {
            var selCatId = document.getElementById('filterProfCat').value; // already the category ID
            var subSel   = document.getElementById('filterSubCat');
            var current  = reset ? '' : subSel.value;

            subSel.innerHTML = '<option value="">— All Sub-Categories —</option>';
            allSubCatOptions.forEach(function(o) {
                if (!selCatId || String(o.catId) === String(selCatId)) {
                    var opt = document.createElement('option');
                    opt.value = o.val;
                    opt.setAttribute('data-cat-id', o.catId);
                    opt.text  = o.text;
                    if (o.val === current) opt.selected = true;
                    subSel.appendChild(opt);
                }
            });
        }

        filterSubCats();

        /* ── State → City cascade ── */
        var allCityOptions = (function() {
            var opts = [];
            document.querySelectorAll('#filterCity option[data-state-id]').forEach(function(o) {
                opts.push({ val: o.value, stateId: o.getAttribute('data-state-id'), text: o.text });
            });
            return opts;
        })();

        function filterCitiesOnState(reset) {
            var selStateId = document.getElementById('filterState').value;
            var citySel    = document.getElementById('filterCity');
            var current    = reset ? '' : citySel.value;

            citySel.innerHTML = '<option value="">— All Cities —</option>';
            allCityOptions.forEach(function(o) {
                if (!selStateId || o.stateId === selStateId) {
                    var opt = document.createElement('option');
                    opt.value = o.val;
                    opt.setAttribute('data-state-id', o.stateId);
                    opt.text  = o.text;
                    if (o.val === current) opt.selected = true;
                    citySel.appendChild(opt);
                }
            });
        }

        /* ── State → Anchal cascade ── */
        var allAnchalOptions = (function() {
            var opts = [];
            document.querySelectorAll('#filterAnchal option[data-state-id]').forEach(function(o) {
                opts.push({ val: o.value, stateId: o.getAttribute('data-state-id'), text: o.text });
            });
            return opts;
        })();

        function filterAnchalsOnState(reset) {
            var selStateId  = document.getElementById('filterState').value;
            var anchalSel   = document.getElementById('filterAnchal');
            var current     = reset ? '' : anchalSel.value;

            anchalSel.innerHTML = '<option value="">— All Anchals —</option>';
            allAnchalOptions.forEach(function(o) {
                if (!selStateId || o.stateId === selStateId) {
                    var opt = document.createElement('option');
                    opt.value = o.val;
                    opt.setAttribute('data-state-id', o.stateId);
                    opt.text  = o.text;
                    if (o.val === current) opt.selected = true;
                    anchalSel.appendChild(opt);
                }
            });
        }

        /* ── Anchal → Local Sangh cascade ── */
        var allLocalSanghOptions = (function() {
            var opts = [];
            document.querySelectorAll('#filterLocalSangh option[data-anchal-id]').forEach(function(o) {
                opts.push({ val: o.value, anchalId: o.getAttribute('data-anchal-id'), stateId: o.getAttribute('data-state-id'), text: o.text });
            });
            return opts;
        })();

        function filterLocalSanghOnAnchal(reset) {
            var selAnchalId = document.getElementById('filterAnchal').value;
            var selStateId  = document.getElementById('filterState').value;
            var lsSel       = document.getElementById('filterLocalSangh');
            var current     = reset ? '' : lsSel.value;

            lsSel.innerHTML = '<option value="">— All Local Sanghs —</option>';
            allLocalSanghOptions.forEach(function(o) {
                var matchAnchal = !selAnchalId || o.anchalId === selAnchalId;
                var matchState  = !selStateId  || o.stateId  === selStateId;
                if (matchAnchal && matchState) {
                    var opt = document.createElement('option');
                    opt.value = o.val;
                    opt.setAttribute('data-anchal-id', o.anchalId);
                    opt.setAttribute('data-state-id', o.stateId);
                    opt.text  = o.text;
                    if (o.val === current) opt.selected = true;
                    lsSel.appendChild(opt);
                }
            });
        }

        // Run on load to restore all cascades
        filterCitiesOnState();
        filterAnchalsOnState();
        filterLocalSanghOnAnchal();

        // Run once on load to sync with any pre-selected category
        filterSubCats();

        /* Filter panel toggle */
        function toggleFilterPanel() {
            var header = document.getElementById('filterHeader');
            var body   = document.getElementById('filterBody');
            header.classList.toggle('open');
            body.classList.toggle('open');
        }

        /* Column visibility modal */
        function openColModal()  { document.getElementById('colModal').classList.add('open'); }
        function closeColModal() { document.getElementById('colModal').classList.remove('open'); }
        /* Export fields modal */
        function openExportModal()  { document.getElementById('exportFieldsModal').classList.add('open'); }
        function closeExportModal() { document.getElementById('exportFieldsModal').classList.remove('open'); }

        function applyColVisibility(save = true) {
            var states = {};
            document.querySelectorAll('.col-toggle').forEach(function(cb) {
                var col = cb.getAttribute('data-col');
                var visible = cb.checked;
                states[col] = visible;
                document.querySelectorAll('.' + col).forEach(function(el) {
                    el.style.display = visible ? '' : 'none';
                });
                var row = cb.closest('.modal-row');
                if (row) { visible ? row.classList.add('is-checked') : row.classList.remove('is-checked'); }
            });
            if (save) {
                localStorage.setItem('member_col_visibility', JSON.stringify(states));
            }
        }

        document.querySelectorAll('.col-toggle').forEach(function(cb) {
            cb.addEventListener('change', function() { applyColVisibility(true); });
        });

        document.querySelectorAll('[name="export_fields[]"]').forEach(function(cb) {
            cb.addEventListener('change', function() {
                var row = cb.closest('.modal-row');
                if (row) { cb.checked ? row.classList.add('is-checked') : row.classList.remove('is-checked'); }
            });
        });

        // Load saved state
        (function() {
            var saved = localStorage.getItem('member_col_visibility');
            if (saved) {
                try {
                    var states = JSON.parse(saved);
                    document.querySelectorAll('.col-toggle').forEach(function(cb) {
                        var col = cb.getAttribute('data-col');
                        if (states.hasOwnProperty(col)) {
                            cb.checked = states[col];
                        }
                    });
                } catch(e) {}
            }
            applyColVisibility(false);
        })();

        // Prevent export_fields[] from polluting filter-only URLs
        document.querySelector('button.btn-filter-apply[type="submit"]:not([name])').addEventListener('click', function() {
            document.querySelectorAll('[name="export_fields[]"]').forEach(function(el) { el.disabled = true; });
        });
    </script>

@include('includes.backend.footer')
