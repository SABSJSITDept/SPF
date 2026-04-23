@include('includes.backend.header')

<style>
    .page-title { font-size: 21px; font-weight: 700; color: #1a237e; margin-bottom: 24px; display: flex; align-items: center; gap: 10px; }
    .page-title span.count { background: #1a237e; color: #fff; font-size: 12px; padding: 2px 10px; border-radius: 12px; font-weight: 500; }

    /* ── ALERT ── */
    .alert { padding: 11px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 22px; }
    .alert-success { background: #e8f5e9; border: 1px solid #a5d6a7; color: #2e7d32; }
    .alert-error   { background: #fdecea; border: 1px solid #f5c6c6; color: #c62828; }

    /* ── GRID ── */
    .layout-grid { display: grid; grid-template-columns: 1fr 380px; gap: 28px; align-items: start; }
    @media(max-width:900px){ .layout-grid { grid-template-columns: 1fr; } }

    /* ── TABLE CARD ── */
    .card { background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,.08); overflow: hidden; }
    .card-header { padding: 16px 20px; border-bottom: 1px solid #eef0f4; background: #f7f8fc; display: flex; align-items: center; justify-content: space-between; }
    .card-header h2 { font-size: 14px; font-weight: 700; color: #1a237e; margin: 0; }

    table { width: 100%; border-collapse: collapse; font-size: 13px; }
    thead th { padding: 11px 14px; text-align: left; background: #1a237e; color: #fff; white-space: nowrap; font-weight: 500; }
    tbody td { padding: 10px 14px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
    tbody tr:hover { background: #f5f7ff; }
    tbody tr:last-child td { border-bottom: none; }

    .role-badge { display: inline-block; padding: 2px 10px; border-radius: 10px; font-size: 11.5px; font-weight: 600; }
    .role-super  { background: #e8eaf6; color: #3949ab; }
    .role-operator { background: #e3f2fd; color: #1565c0; }
    .role-anchal { background: #e8f5e9; color: #2e7d32; }

    .btn-del { background: none; border: 1.5px solid #ef9a9a; color: #c62828; padding: 4px 12px; border-radius: 6px; font-size: 12px; cursor: pointer; transition: background .15s; }
    .btn-del:hover { background: #fdecea; }
    .no-records { text-align: center; padding: 36px; color: #aaa; font-size: 13px; }

    /* ── CREATE FORM CARD ── */
    .form-card { background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,.08); padding: 24px; position: sticky; top: 80px; }
    .form-card h2 { font-size: 14px; font-weight: 700; color: #1a237e; margin: 0 0 20px 0; padding-bottom: 12px; border-bottom: 1px solid #eef0f4; }

    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; font-size: 12.5px; font-weight: 600; color: #555; margin-bottom: 5px; }
    .form-group label span.req { color: #e53935; margin-left: 2px; }
    .form-control {
        width: 100%; padding: 9px 12px; border: 1.5px solid #ddd; border-radius: 7px;
        font-size: 13px; color: #333; outline: none; transition: border-color .2s;
        font-family: inherit;
    }
    .form-control:focus { border-color: #1a237e; }
    .form-control.is-invalid { border-color: #e53935; }
    .invalid-msg { color: #e53935; font-size: 11.5px; margin-top: 4px; }

    .anchal-row { display: none; }
    .anchal-row.visible { display: block; }

    .btn-create { width: 100%; padding: 11px; background: linear-gradient(135deg, #1a237e, #1565c0); color: #fff; font-size: 14px; font-weight: 600; border: none; border-radius: 8px; cursor: pointer; transition: opacity .2s; margin-top: 4px; }
    .btn-create:hover { opacity: .9; }

    .pw-hint { font-size: 11px; color: #aaa; margin-top: 4px; }
</style>

<div>
    <div class="page-title">
        &#128105; Manage Admin Users
        <span class="count">{{ $admins->count() }}</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success">&#10003; {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">&#10006; {{ session('error') }}</div>
    @endif

    <div class="layout-grid">

        {{-- ── LEFT: Existing Admins Table ── --}}
        <div class="card">
            <div class="card-header">
                <h2>&#128101; Existing Admin Users</h2>
            </div>

            @if($admins->isEmpty())
                <p class="no-records">No admin users found.</p>
            @else
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Anchal</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $i => $admin)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><strong>{{ $admin->name }}</strong></td>
                        <td>{{ $admin->email }}</td>
                        <td>
                            @if($admin->isSuperAdmin())
                                <span class="role-badge role-super">Super Admin</span>
                            @elseif($admin->isOperator())
                                <span class="role-badge role-operator">Operator</span>
                            @else
                                <span class="role-badge role-anchal">Anchal Operator</span>
                            @endif
                        </td>
                        <td>{{ $admin->anchal ?? '—' }}</td>
                        <td>{{ $admin->created_at->format('d-m-Y') }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.users.destroy', $admin->id) }}"
                                  onsubmit="return confirm('Delete {{ addslashes($admin->name) }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-del">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>

        {{-- ── RIGHT: Create Form ── --}}
        <div class="form-card">
            <h2>&#43; Create New Admin User</h2>

            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <div class="form-group">
                    <label>Full Name <span class="req">*</span></label>
                    <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                           value="{{ old('name') }}" placeholder="e.g. Rahul Sharma">
                    @error('name') <div class="invalid-msg">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Email Address <span class="req">*</span></label>
                    <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           value="{{ old('email') }}" placeholder="user@example.com">
                    @error('email') <div class="invalid-msg">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Password <span class="req">*</span></label>
                    <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                           placeholder="Min. 6 characters">
                    <div class="pw-hint">Minimum 6 characters</div>
                    @error('password') <div class="invalid-msg">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Confirm Password <span class="req">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Re-enter password">
                </div>

                <div class="form-group">
                    <label>Role <span class="req">*</span></label>
                    <select name="role" id="roleSelect" class="form-control {{ $errors->has('role') ? 'is-invalid' : '' }}"
                            onchange="toggleAnchal(this.value)">
                        <option value="">-- Select Role --</option>
                        <option value="operator" {{ old('role') === 'operator' ? 'selected' : '' }}>
                            Operator (View All Records)
                        </option>
                        <option value="anchal_operator" {{ old('role') === 'anchal_operator' ? 'selected' : '' }}>
                            Anchal Operator (Specific Anchal)
                        </option>
                    </select>
                    @error('role') <div class="invalid-msg">{{ $message }}</div> @enderror
                </div>

                <div class="form-group anchal-row {{ old('role') === 'anchal_operator' || $errors->has('anchal') ? 'visible' : '' }}"
                     id="anchalRow">
                    <label>Assigned Anchal <span class="req">*</span></label>
                    <select name="anchal" class="form-control {{ $errors->has('anchal') ? 'is-invalid' : '' }}">
                        <option value="">-- Select Anchal --</option>
                        @foreach($anchalList as $anchal)
                            <option value="{{ $anchal }}" {{ old('anchal') == $anchal ? 'selected' : '' }}>
                                {{ $anchal }}
                            </option>
                        @endforeach
                    </select>
                    @error('anchal') <div class="invalid-msg">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn-create">&#10003; Create Admin User</button>
            </form>
        </div>

    </div>
</div>

<script>
    function toggleAnchal(role) {
        const row = document.getElementById('anchalRow');
        if (role === 'anchal_operator') {
            row.classList.add('visible');
        } else {
            row.classList.remove('visible');
        }
    }
</script>

@include('includes.backend.footer')
