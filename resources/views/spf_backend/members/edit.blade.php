@include('includes.backend.header')

<style>
    h1 { margin-bottom: 20px; font-size: 22px; color: #1a237e; }
    .breadcrumb { font-size: 13px; color: #888; margin-bottom: 16px; }
    .breadcrumb a { color: #1a237e; text-decoration: none; }
    .breadcrumb a:hover { text-decoration: underline; }

    .section-card { background:#fff; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,.09); margin-bottom:16px; overflow:hidden; border:1px solid #e8eaf6; }
    .section-card-header { display:flex; align-items:center; gap:8px; padding:11px 18px; background:linear-gradient(90deg,#1a237e,#283593); }
    .section-card-header span { color:#fff; font-size:13px; font-weight:600; }
    .section-card-body { padding:18px 20px; }

    .form-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:14px 18px; }
    .form-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:14px 18px; }
    .form-group { display:flex; flex-direction:column; gap:5px; }
    .form-group label { font-size:11px; font-weight:700; color:#555; text-transform:uppercase; letter-spacing:.4px; }
    .form-group input[type=text],
    .form-group input[type=tel],
    .form-group input[type=email],
    .form-group input[type=number],
    .form-group input[type=date],
    .form-group select,
    .form-group textarea {
        border:1px solid #dde1f0; border-radius:5px; padding:7px 10px; font-size:13px; color:#333;
        outline:none; transition:border-color .2s, box-shadow .2s; background:#fafbff; width:100%;
    }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
        border-color:#3949ab; box-shadow:0 0 0 2px rgba(57,73,171,.12);
    }
    .form-group input[readonly] { background:#f0f2f8; color:#777; cursor:not-allowed; }
    .form-group textarea { resize:vertical; min-height:72px; }
    .span-full { grid-column: 1 / -1; }

    /* Radio / Checkbox groups */
    .radio-group, .checkbox-group { display:flex; flex-wrap:wrap; gap:8px 18px; margin-top:4px; }
    .radio-item, .checkbox-item { display:flex; align-items:center; gap:6px; font-size:13px; color:#444; }
    .radio-item input, .checkbox-item input { accent-color:#1a237e; width:15px; height:15px; cursor:pointer; }

    .three-col-section { display:grid; grid-template-columns:1fr 1fr 1fr; gap:18px; }
    .col-title { font-size:12px; font-weight:700; color:#1a237e; margin-bottom:8px; text-transform:uppercase; letter-spacing:.3px; }

    /* Local sangh search */
    .field-wrapper { position:relative; }
    .suggestions-list { position:absolute; top:100%; left:0; right:0; background:#fff; border:1px solid #dde1f0; border-radius:0 0 6px 6px; box-shadow:0 4px 12px rgba(0,0,0,.1); z-index:100; list-style:none; max-height:220px; overflow-y:auto; }
    .suggestions-list li { padding:8px 12px; font-size:13px; cursor:pointer; color:#333; border-bottom:1px solid #f5f5f5; }
    .suggestions-list li:hover { background:#edf2ff; color:#1a237e; }
    .suggestions-list.hidden { display:none; }

    .form-actions-card { background:#fff; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,.09); padding:16px 20px; display:flex; gap:10px; align-items:center; border:1px solid #e8eaf6; }
    .btn-save { background:#1a237e; color:#fff; border:none; padding:9px 26px; border-radius:5px; font-size:13px; font-weight:600; cursor:pointer; transition:background .2s; }
    .btn-save:hover { background:#283593; }
    .btn-cancel { background:#f5f5f5; color:#555; border:1px solid #ddd; padding:9px 18px; border-radius:5px; font-size:13px; cursor:pointer; text-decoration:none; display:inline-block; }
    .btn-cancel:hover { background:#ebebeb; }

    .alert-success { background:#e8f5e9; color:#2e7d32; padding:10px 16px; border-radius:6px; margin-bottom:16px; font-size:13px; }
    .alert-error   { background:#ffebee; color:#c62828; padding:10px 16px; border-radius:6px; margin-bottom:16px; font-size:13px; }

    .member-status-bar { display:flex; align-items:center; gap:10px; background:#fff; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,.09); padding:12px 20px; margin-bottom:16px; border:1px solid #e8eaf6; font-size:13px; flex-wrap:wrap; }
    .status-pill { display:inline-block; padding:3px 12px; border-radius:12px; font-size:12px; font-weight:600; }
    .status-pill.approved { background:#e8f5e9; color:#2e7d32; }
    .status-pill.pending  { background:#fff8e1; color:#e65100; }
    .status-pill.rejected { background:#ffebee; color:#c62828; }

    /* File input */
    .file-input-label { display:inline-block; background:#e8eaf6; color:#1a237e; border:1px solid #c5cae9; border-radius:5px; padding:6px 14px; font-size:12px; font-weight:600; cursor:pointer; transition:background .2s; }
    .file-input-label:hover { background:#c5cae9; }
    input[type=file] { display:none; }
    .file-name { font-size:12px; color:#555; margin-top:4px; display:block; }
    .file-error { color:#c62828; font-size:12px; margin-top:2px; }

    @media(max-width:768px) {
        .form-grid { grid-template-columns:1fr 1fr; }
        .three-col-section { grid-template-columns:1fr; }
    }
    @media(max-width:540px) {
        .form-grid, .form-grid-2 { grid-template-columns:1fr; }
    }
</style>

<div>
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a> &rsaquo;
        <a href="{{ url()->previous() }}">Members</a> &rsaquo;
        Edit
    </div>

    <h1>Edit Member &mdash; {{ $registration->full_name }}</h1>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert-error">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
    @endif

    {{-- Quick info bar --}}
    <div class="member-status-bar">
        <span style="color:#888;">ID:</span><strong style="color:#1a237e;">#{{ $registration->id }}</strong>
        @if($registration->mid)
            <span style="color:#888;margin-left:8px;">MID:</span><strong style="color:#1a237e;">{{ $registration->mid }}</strong>
        @endif
        <span style="color:#888;margin-left:8px;">Status:</span>
        <span class="status-pill {{ $registration->status }}">{{ ucfirst($registration->status) }}</span>
        <span style="color:#888;margin-left:8px;">Registered:</span>
        <span style="color:#555;">{{ $registration->created_at->format('d M Y') }}</span>
    </div>

    <form method="POST" action="{{ route('admin.members.update', $registration->id) }}" enctype="multipart/form-data">
        @csrf

        {{-- ── Personal Information ── --}}
        <div class="section-card">
            <div class="section-card-header"><span>&#128100; Personal Information</span></div>
            <div class="section-card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Full Name <span style="color:#c62828;">*</span></label>
                        <input type="text" name="full_name" id="fullName" value="{{ old('full_name', $registration->full_name) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Father's / Husband's Name</label>
                        <input type="text" name="father_name" value="{{ old('father_name', $registration->father_name) }}">
                    </div>
                    <div class="form-group">
                        <label>Mobile <span style="color:#c62828;">*</span></label>
                        <input type="tel" name="mobile" value="{{ old('mobile', $registration->mobile) }}" required maxlength="10">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email', $registration->email) }}">
                    </div>
                    <div class="form-group">
                        <label>Date of Birth <span style="color:#c62828;">*</span></label>
                        <input type="date" id="dob" name="dob" value="{{ old('dob', $registration->dob ? $registration->dob->format('Y-m-d') : '') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Age <span style="color:#c62828;">*</span></label>
                        <input type="number" id="age" name="age" value="{{ old('age', $registration->age) }}" required min="0" max="120">
                    </div>
                    <div class="form-group">
                        <label>Gender <span style="color:#c62828;">*</span></label>
                        <select name="gender" required>
                            <option value="">-- Select --</option>
                            <option value="male"   {{ strtolower(old('gender', $registration->gender)) === 'male'   ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ strtolower(old('gender', $registration->gender)) === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other"  {{ strtolower(old('gender', $registration->gender)) === 'other'  ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>MID</label>
                        <input type="text" name="mid" value="{{ old('mid', $registration->mid) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Professional Information ── --}}
        <div class="section-card">
            <div class="section-card-header"><span>&#128188; Professional Information</span></div>
            <div class="section-card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Profession</label>
                        <select name="profession" id="profession">
                            <option value="">-- Select Profession --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ (string)old('profession', $registration->profession) === (string)$cat->id ? 'selected' : '' }}>
                                    {{ $cat->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Professional Category</label>
                        <select name="professional_category" id="professionalCategory">
                            <option value="">-- Select Category --</option>
                            {{-- Sub-cats loaded from DB server-side for current profession --}}
                            @foreach($subCategories as $sub)
                                <option value="{{ $sub->sub_category_name }}" {{ old('professional_category', $registration->professional_category) === $sub->sub_category_name ? 'selected' : '' }}>
                                    {{ $sub->sub_category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Document Type</label>
                        <select name="document_type">
                            <option value="">-- Select --</option>
                            <option value="Professional degree" {{ old('document_type', $registration->document_type ?? '') === 'Professional degree' ? 'selected' : '' }}>Professional degree</option>
                            <option value="LinkedIn profile"    {{ old('document_type', $registration->document_type ?? '') === 'LinkedIn profile'    ? 'selected' : '' }}>LinkedIn profile</option>
                            <option value="Business card"       {{ old('document_type', $registration->document_type ?? '') === 'Business card'       ? 'selected' : '' }}>Business card</option>
                        </select>
                    </div>
                </div>

                {{-- Working Status (radio) --}}
                <div style="margin-top:16px;">
                    <div class="col-title">Current Working Status</div>
                    <div class="radio-group">
                        @php $ws = strtolower(old('working_status', $registration->working_status ?? '')); @endphp
                        <div class="radio-item"><input type="radio" name="working_status" value="working"                  {{ $ws === 'working'                  ? 'checked' : '' }}><label>Working</label></div>
                        <div class="radio-item"><input type="radio" name="working_status" value="non_working"              {{ $ws === 'non_working'              ? 'checked' : '' }}><label>Non-working</label></div>
                        <div class="radio-item"><input type="radio" name="working_status" value="self_employed"            {{ $ws === 'self_employed'            ? 'checked' : '' }}><label>Self-employed</label></div>
                        <div class="radio-item"><input type="radio" name="working_status" value="service"                  {{ $ws === 'service'                  ? 'checked' : '' }}><label>Service</label></div>
                        <div class="radio-item"><input type="radio" name="working_status" value="looking_for_opportunities" {{ $ws === 'looking_for_opportunities' ? 'checked' : '' }}><label>Looking for opportunities</label></div>
                        <div class="radio-item"><input type="radio" name="working_status" value="other"                    {{ $ws === 'other'                    ? 'checked' : '' }}><label>Other</label></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Location ── --}}
        <div class="section-card">
            <div class="section-card-header"><span>&#127759; Location</span></div>
            <div class="section-card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label>State <span style="color:#c62828;">*</span></label>
                        <select name="state" id="state">
                            <option value="">Loading states...</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>City <span style="color:#c62828;">*</span></label>
                        <select name="city" id="city">
                            <option value="">Select City</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Anchal</label>
                        <input type="text" id="anchalDisplay" readonly placeholder="Auto-filled from city">
                        <input type="hidden" name="anchal" id="anchalHidden">
                    </div>
                    <div class="form-group field-wrapper" style="position:relative;">
                        <label>Local Sangh / Branch</label>
                        <input type="text" id="localSanghSearch" autocomplete="off" placeholder="Search branch in selected city...">
                        <input type="hidden" name="local_sangh_id" id="localSangh">
                        <ul id="branchSuggestions" class="suggestions-list hidden"></ul>
                    </div>
                    <div class="form-group">
                        <label>Sadhumargi Family?</label>
                        <div class="radio-group" style="margin-top:8px;">
                            @php $sm = strtolower(old('sadhumargi', $registration->sadhumargi ?? '')); @endphp
                            <div class="radio-item"><input type="radio" id="sadhumargiYes" name="sadhumargi" value="yes" {{ $sm === 'yes' ? 'checked' : '' }}><label for="sadhumargiYes">Yes</label></div>
                            <div class="radio-item"><input type="radio" id="sadhumargiNo"  name="sadhumargi" value="no"  {{ $sm === 'no'  ? 'checked' : '' }}><label for="sadhumargiNo">No</label></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Objectives / Source / Working Status ── --}}
        <div class="section-card">
            <div class="section-card-header"><span>&#127919; Objectives &amp; Background</span></div>
            <div class="section-card-body">
                <div class="three-col-section">
                    {{-- Objectives --}}
                    <div>
                        <div class="col-title">Objectives behind joining SPF</div>
                        @php $objs = old('objectives', $registration->objectives ?? []); @endphp
                        <div class="checkbox-group" style="flex-direction:column;">
                            <div class="checkbox-item"><input type="checkbox" name="objectives[]" value="spiritual_development"   {{ in_array('spiritual_development',   $objs) ? 'checked' : '' }}><label>Spiritual Development</label></div>
                            <div class="checkbox-item"><input type="checkbox" name="objectives[]" value="social_development"      {{ in_array('social_development',      $objs) ? 'checked' : '' }}><label>Social Development</label></div>
                            <div class="checkbox-item"><input type="checkbox" name="objectives[]" value="personality_development" {{ in_array('personality_development', $objs) ? 'checked' : '' }}><label>Personality Development</label></div>
                            <div class="checkbox-item"><input type="checkbox" name="objectives[]" value="professional_development"{{ in_array('professional_development',$objs) ? 'checked' : '' }}><label>Professional Development</label></div>
                            <div class="checkbox-item"><input type="checkbox" name="objectives[]" value="skill_development"       {{ in_array('skill_development',       $objs) ? 'checked' : '' }}><label>Skill Development</label></div>
                            <div class="checkbox-item"><input type="checkbox" name="objectives[]" value="other"                   {{ in_array('other',                   $objs) ? 'checked' : '' }}><label>Other</label></div>
                        </div>
                    </div>

                    {{-- Source --}}
                    <div>
                        <div class="col-title">How did you hear about SPF?</div>
                        @php $src = old('source', $registration->source ?? ''); @endphp
                        <div class="radio-group" style="flex-direction:column;">
                            <div class="radio-item"><input type="radio" name="source" value="stall_counter"       {{ $src === 'stall_counter'       ? 'checked' : '' }}><label>Stall/Counter at Chaturmas</label></div>
                            <div class="radio-item"><input type="radio" name="source" value="social_media"        {{ $src === 'social_media'        ? 'checked' : '' }}><label>Social Media</label></div>
                            <div class="radio-item"><input type="radio" name="source" value="friend_acquaintance" {{ $src === 'friend_acquaintance' ? 'checked' : '' }}><label>Friend/Acquaintance</label></div>
                            <div class="radio-item"><input type="radio" name="source" value="shramnopasak"        {{ $src === 'shramnopasak'        ? 'checked' : '' }}><label>Shramnopasak</label></div>
                            <div class="radio-item"><input type="radio" name="source" value="other"               {{ $src === 'other'               ? 'checked' : '' }}><label>Other</label></div>
                        </div>
                    </div>

                    {{-- Hobbies & Referral --}}
                    <div>
                        <div class="col-title">Other Details</div>
                        <div class="form-group">
                            <label>Hobbies / Areas of Interest</label>
                            <textarea name="hobbies">{{ old('hobbies', $registration->hobbies) }}</textarea>
                        </div>
                        <div class="form-group" style="margin-top:10px;">
                            <label>Referral's Name</label>
                            <textarea name="referral">{{ old('referral', $registration->referral) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Document Upload ── --}}
        <div class="section-card">
            <div class="section-card-header"><span>&#128196; Document</span></div>
            <div class="section-card-body">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Current File</label>
                        @if($registration->file)
                            <a href="{{ asset('uploads/' . $registration->file) }}" target="_blank" style="color:#1565c0;font-size:13px;">View Current PDF</a>
                        @else
                            <span style="color:#999;font-size:13px;">No file uploaded</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Replace PDF (optional, max 5MB)</label>
                        <label class="file-input-label" for="fileUpload">Choose PDF File</label>
                        <input type="file" id="fileUpload" name="file" accept=".pdf">
                        <span class="file-name" id="fileNameDisplay">No file chosen</span>
                        <span class="file-error" id="fileError"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Actions ── --}}
        <div class="form-actions-card">
            <button type="submit" class="btn-save">&#10003; Save Changes</button>
            @if($registration->status !== 'approved')
                <form method="POST" action="{{ route('admin.members.updateStatus', $registration->id) }}" style="display:inline;margin-left:10px;">
                    @csrf
                    <input type="hidden" name="status" value="approved">
                    <button type="button" onclick="confirmSubmit(this.form, 'Approve this member?', '#2e7d32')" class="btn-save" style="background:#2e7d32;">&#10003; Approve</button>
                </form>
            @endif
            @if($registration->status !== 'rejected')
                <form method="POST" action="{{ route('admin.members.updateStatus', $registration->id) }}" style="display:inline;margin-left:10px;">
                    @csrf
                    <input type="hidden" name="status" value="rejected">
                    <button type="button" onclick="confirmSubmit(this.form, 'Reject this member?', '#c62828')" class="btn-save" style="background:#c62828;">&#10005; Reject</button>
                </form>
            @endif
            <a href="{{ url()->previous() }}" class="btn-cancel">Cancel</a>
        </div>

    </form>
</div>

<script>
// ── Stored values to pre-select after API loads ─────────────────────────
const currentStateId  = '{{ $registration->state }}';
const currentCityId   = '{{ $registration->city }}';
const currentAnchalId = '{{ $registration->anchal }}';
const currentLocalSanghId = '{{ $registration->local_sangh_id }}';

// ── DOB → Auto-calculate Age ─────────────────────────────────────────────
document.getElementById('dob').addEventListener('change', function () {
    const dob = new Date(this.value);
    if (isNaN(dob)) return;
    const today = new Date();
    let age = today.getFullYear() - dob.getFullYear();
    const m = today.getMonth() - dob.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
    document.getElementById('age').value = age >= 0 ? age : '';
});

// ── Location: State / City / Anchal ─────────────────────────────────────
let locationData = {};
let allBranches  = [];

function buildLocationData(cities) {
    const stateSelect = document.getElementById('state');
    cities.forEach(c => {
        if (!locationData[c.state_id]) {
            locationData[c.state_id] = { name: c.state_name, cities: {} };
        }
        locationData[c.state_id].cities[c.city_id] = {
            name: c.city_name,
            anchal_name: c.anchal_name,
            anchal_id: c.anchal_id
        };
    });

    const sorted = Object.entries(locationData).sort((a, b) => a[1].name.localeCompare(b[1].name));
    stateSelect.innerHTML = '<option value="">Select State</option>';
    sorted.forEach(([sid, sdata]) => {
        const opt = document.createElement('option');
        opt.value = sid;
        opt.textContent = sdata.name;
        stateSelect.appendChild(opt);
    });

    // Pre-select current state
    if (currentStateId) {
        stateSelect.value = currentStateId;
        populateCities(true);
    }
}

function populateCities(preSelect) {
    const stateId = document.getElementById('state').value;
    const citySelect = document.getElementById('city');
    const anchalDisplay = document.getElementById('anchalDisplay');
    const anchalHidden  = document.getElementById('anchalHidden');

    citySelect.innerHTML = '<option value="">Select City</option>';
    anchalDisplay.value  = '';
    anchalHidden.value   = '';
    document.getElementById('localSanghSearch').value = '';
    document.getElementById('localSangh').value = '';

    if (!stateId || !locationData[stateId]) return;

    const cities = Object.entries(locationData[stateId].cities).sort((a, b) => a[1].name.localeCompare(b[1].name));
    cities.forEach(([cid, cdata]) => {
        const opt = document.createElement('option');
        opt.value = cid;
        opt.textContent = cdata.name;
        citySelect.appendChild(opt);
    });

    if (preSelect && currentCityId) {
        citySelect.value = currentCityId;
        populateAnchal(true);
    }
}

function populateAnchal(preSelect) {
    const stateId = document.getElementById('state').value;
    const cityId  = document.getElementById('city').value;
    const anchalDisplay = document.getElementById('anchalDisplay');
    const anchalHidden  = document.getElementById('anchalHidden');

    anchalDisplay.value = '';
    anchalHidden.value  = '';
    document.getElementById('localSanghSearch').value = '';
    document.getElementById('localSangh').value = '';

    if (!stateId || !cityId || !locationData[stateId]?.cities?.[cityId]) return;

    const cityData = locationData[stateId].cities[cityId];
    anchalDisplay.value = cityData.anchal_name;
    anchalHidden.value  = cityData.anchal_id;

    // Pre-fill local sangh if available
    if (preSelect && currentLocalSanghId) {
        const branch = allBranches.find(b => String(b.id) === String(currentLocalSanghId));
        if (branch) selectBranch(branch, false);
    }
}

document.getElementById('state').addEventListener('change', () => populateCities(false));
document.getElementById('city').addEventListener('change',  () => populateAnchal(false));

// ── Branch / Local Sangh Search ──────────────────────────────────────────
function selectBranch(branch, clearIfMissing = true) {
    document.getElementById('localSangh').value       = branch.id;
    document.getElementById('localSanghSearch').value = `${branch.branch_name} (${branch.city})`;
    document.getElementById('branchSuggestions').classList.add('hidden');
}

function renderBranchSuggestions(query) {
    const list    = document.getElementById('branchSuggestions');
    const stateId = document.getElementById('state').value;
    const cityId  = document.getElementById('city').value;

    if (!stateId || !cityId) {
        list.innerHTML = '<li style="padding:8px 12px;color:#999;font-size:13px;">Select State &amp; City first</li>';
        list.classList.remove('hidden');
        return;
    }

    const cityName  = locationData[stateId]?.cities?.[cityId]?.name ?? '';
    const stateName = locationData[stateId]?.name ?? '';
    const q = query.trim().toLowerCase();
    const cityBranches = allBranches.filter(b => b.city === cityName && b.state_name === stateName);
    const filtered = q ? cityBranches.filter(b => b.branch_name.toLowerCase().includes(q)) : cityBranches;

    list.innerHTML = '';
    if (filtered.length === 0) {
        list.innerHTML = '<li style="padding:8px 12px;color:#999;font-size:13px;">No branches found</li>';
        list.classList.remove('hidden');
        return;
    }
    filtered.slice(0, 30).forEach(branch => {
        const li = document.createElement('li');
        li.textContent = branch.branch_name;
        li.addEventListener('mousedown', e => { e.preventDefault(); selectBranch(branch); });
        list.appendChild(li);
    });
    list.classList.remove('hidden');
}

const lsSearch = document.getElementById('localSanghSearch');
lsSearch.addEventListener('input',  () => { document.getElementById('localSangh').value = ''; renderBranchSuggestions(lsSearch.value); });
lsSearch.addEventListener('focus',  () => renderBranchSuggestions(lsSearch.value));
lsSearch.addEventListener('blur',   () => setTimeout(() => document.getElementById('branchSuggestions').classList.add('hidden'), 150));
document.addEventListener('click', e => {
    const list = document.getElementById('branchSuggestions');
    if (!list.contains(e.target) && e.target !== lsSearch) list.classList.add('hidden');
});

// ── Profession → Sub-categories dynamic load ─────────────────────────────
const currentProfCategory = '{{ $registration->professional_category }}';

document.getElementById('profession').addEventListener('change', function () {
    const categoryId = this.value;
    const subSelect = document.getElementById('professionalCategory');
    subSelect.innerHTML = '<option value="">-- Select Category --</option>';
    if (!categoryId) return;

    fetch(`/api/sub-categories/${categoryId}`, { headers: { 'Accept': 'application/json' } })
        .then(res => res.json())
        .then(subs => {
            subs.forEach(sub => {
                const opt = document.createElement('option');
                opt.value = sub.sub_category_name;
                opt.textContent = sub.sub_category_name;
                if (sub.sub_category_name === currentProfCategory) opt.selected = true;
                subSelect.appendChild(opt);
            });
        })
        .catch(() => {});
});

// ── File validation ───────────────────────────────────────────────────────
document.getElementById('fileUpload').addEventListener('change', function () {
    const file    = this.files[0];
    const errEl   = document.getElementById('fileError');
    const nameEl  = document.getElementById('fileNameDisplay');
    errEl.textContent = '';

    if (!file) { nameEl.textContent = 'No file chosen'; return; }

    if (file.type !== 'application/pdf') {
        errEl.textContent = 'Only PDF files are allowed.';
        this.value = '';
        nameEl.textContent = 'No file chosen';
        return;
    }
    const sizeMB = file.size / (1024 * 1024);
    if (sizeMB > 5) {
        errEl.textContent = 'File size must not exceed 5MB.';
        this.value = '';
        nameEl.textContent = 'No file chosen';
        return;
    }
    nameEl.textContent = file.name;
});

// ── Fetch cities & branches from external API ────────────────────────────
fetch('https://mrm.sadhumargi.org/api/cities', { headers: { 'Accept': 'application/json' } })
    .then(res => res.json())
    .then(data => buildLocationData(data.cities ?? []))
    .catch(() => console.warn('Could not load cities.'));

fetch('https://mrm.sadhumargi.org/api/branches', { headers: { 'Accept': 'application/json' } })
    .then(res => res.json())
    .then(data => { allBranches = data.branches ?? []; })
    .catch(() => console.warn('Could not load branches.'));
</script>

@include('includes.backend.footer')
