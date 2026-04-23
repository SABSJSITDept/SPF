@include('includes.backend.header')

<style>
    .page-title { font-size: 21px; font-weight: 700; color: #1a237e; margin-bottom: 24px; display: flex; align-items: center; gap: 10px; }
    .page-title span.count { background: #1a237e; color: #fff; font-size: 12px; padding: 2px 10px; border-radius: 12px; font-weight: 500; }

    /* ── ALERT ── */
    .alert { padding: 11px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 22px; }
    .alert-success { background: #e8f5e9; border: 1px solid #a5d6a7; color: #2e7d32; }
    .alert-error   { background: #fdecea; border: 1px solid #f5c6c6; color: #c62828; }

    /* ── LAYOUT ── */
    .layout-grid { display: grid; grid-template-columns: 1fr 360px; gap: 28px; align-items: start; }
    @media(max-width: 900px){ .layout-grid { grid-template-columns: 1fr; } }

    /* ── TABLE CARD ── */
    .card { background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,.08); overflow: hidden; }
    .card-header { padding: 16px 20px; border-bottom: 1px solid #eef0f4; background: #f7f8fc; display: flex; align-items: center; justify-content: space-between; }
    .card-header h2 { font-size: 14px; font-weight: 700; color: #1a237e; margin: 0; }

    .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; min-width: 600px; }
    thead th { padding: 11px 14px; text-align: left; background: #1a237e; color: #fff; white-space: nowrap; font-weight: 500; }
    tbody td { padding: 10px 14px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
    tbody tr:hover { background: #f5f7ff; }
    tbody tr:last-child td { border-bottom: none; }

    .sub-badge { display: inline-block; padding: 2px 10px; border-radius: 10px; font-size: 11.5px; font-weight: 600; background: #e8eaf6; color: #3949ab; }
    .no-records { text-align: center; padding: 36px; color: #aaa; font-size: 13px; }

    /* ── STATUS TOGGLE ── */
    .toggle-wrap { display: flex; align-items: center; gap: 8px; }
    .toggle-label { font-size: 12px; font-weight: 600; min-width: 52px; }
    .toggle-label.active  { color: #2e7d32; }
    .toggle-label.inactive { color: #c62828; }

    .toggle-switch { position: relative; display: inline-block; width: 42px; height: 22px; flex-shrink: 0; }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .toggle-slider {
        position: absolute; inset: 0; cursor: pointer;
        background: #ccc; border-radius: 22px; transition: background .25s;
    }
    .toggle-slider::before {
        content: ''; position: absolute;
        width: 16px; height: 16px; left: 3px; bottom: 3px;
        background: #fff; border-radius: 50%; transition: transform .25s;
    }
    .toggle-switch input:checked + .toggle-slider { background: #2e7d32; }
    .toggle-switch input:checked + .toggle-slider::before { transform: translateX(20px); }

    /* ── FORM CARD ── */
    .form-card { background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,.08); padding: 24px; position: sticky; top: 80px; }
    .form-card h2 { font-size: 14px; font-weight: 700; color: #1a237e; margin: 0 0 20px 0; padding-bottom: 12px; border-bottom: 1px solid #eef0f4; }

    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; font-size: 12.5px; font-weight: 600; color: #555; margin-bottom: 5px; }
    .form-group label span.req { color: #e53935; margin-left: 2px; }
    .form-control {
        width: 100%; padding: 9px 12px; border: 1.5px solid #ddd; border-radius: 7px;
        font-size: 13px; color: #333; outline: none; transition: border-color .2s;
        font-family: inherit; background: #fff;
    }
    .form-control:focus { border-color: #1a237e; }
    .form-control.is-invalid { border-color: #e53935; }
    .invalid-msg { color: #e53935; font-size: 11.5px; margin-top: 4px; }

    .status-radio-group { display: flex; gap: 14px; margin-top: 6px; }
    .status-radio-group label { display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 500; color: #444; cursor: pointer; }
    .status-radio-group input[type=radio] { accent-color: #1a237e; width: 15px; height: 15px; cursor: pointer; }

    .btn-create { width: 100%; padding: 11px; background: linear-gradient(135deg, #1a237e, #1565c0); color: #fff; font-size: 14px; font-weight: 600; border: none; border-radius: 8px; cursor: pointer; transition: opacity .2s; margin-top: 4px; }
    .btn-create:hover { opacity: .9; }

    .action-links a { font-size: 12.5px; color: #1a237e; text-decoration: none; font-weight: 500; }
    .action-links a:hover { text-decoration: underline; }

    .actions-cell { display: flex; align-items: center; gap: 8px; }
    
    /* ── PAGINATION ── */
    .pagination-wrap { display: flex; justify-content: center; align-items: center; padding: 16px; gap: 5px; background: #fcfdfe; border-top: 1px solid #eef0f4; }
    .pagination-wrap a, .pagination-wrap span { 
        display: inline-block; min-width: 32px; padding: 6px 10px; border-radius: 6px; 
        font-size: 13px; text-align: center; border: 1px solid #dde1f0; text-decoration: none; color: #1a237e; background: #fff;
    }
    .pagination-wrap span.active { background: #1a237e; color: #fff; border-color: #1a237e; font-weight: 600; }
    .pagination-wrap span.disabled { color: #bbb; border-color: #f0f0f0; cursor: default; }
    .pagination-wrap a:hover { background: #f5f7ff; border-color: #c5cae9; }
</style>

<div>
    <div class="page-title">
        &#9776; Manage Categories
        <span class="count">{{ $categories->count() }}</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success">&#10003; {{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-error">&#10006; {{ $errors->first() }}</div>
    @endif

    <div class="layout-grid">

        {{-- ── LEFT: Existing Categories Table ── --}}
        <div class="card">
            <div class="card-header">
                <h2>&#9632; All Categories</h2>
            </div>
            <div class="table-responsive">
                <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Category Name</th>
                        <th>Sub-Categories</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $index => $category)
                    <tr>
                        <td>{{ $index + 1 + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                        <td>{{ $category->category_name }}</td>
                        <td><span class="sub-badge">{{ $category->sub_categories_count }}</span></td>
                        <td>
                            {{-- Status Toggle --}}
                            <form method="POST" action="{{ route('admin.categories.toggle', $category->id) }}" style="margin:0;">
                                @csrf
                                @method('PATCH')
                                <div class="toggle-wrap">
                                    <label class="toggle-switch">
                                        <input type="checkbox"
                                               {{ $category->status === 'Active' ? 'checked' : '' }}
                                               onchange="this.closest('form').submit()">
                                        <span class="toggle-slider"></span>
                                    </label>
                                    <span class="toggle-label {{ $category->status === 'Active' ? 'active' : 'inactive' }}">
                                        {{ $category->status }}
                                    </span>
                                </div>
                            </form>
                        </td>
                        <td>{{ $category->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="no-records">No categories added yet.</td>
                    </tr>
                    @endforelse
                </tbody>
                </table>
            </div>
            
            @if($categories->hasPages())
            <div class="pagination-wrap">
                @if($categories->onFirstPage())
                    <span class="disabled">&laquo;</span>
                @else
                    <a href="{{ $categories->previousPageUrl() }}">&laquo;</a>
                @endif

                @foreach(range(max(1, $categories->currentPage() - 2), min($categories->lastPage(), $categories->currentPage() + 2)) as $page)
                    @if($page == $categories->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $categories->url($page) }}">{{ $page }}</a>
                    @endif
                @endforeach

                @if($categories->hasMorePages())
                    <a href="{{ $categories->nextPageUrl() }}">&raquo;</a>
                @else
                    <span class="disabled">&raquo;</span>
                @endif
            </div>
            @endif
        </div>

        {{-- ── RIGHT: Add Category Form ── --}}
        <div class="form-card">
            <h2>&#43; Add New Category</h2>
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="form-group">
                    <label for="category_name">Category Name <span class="req">*</span></label>
                    <input type="text" id="category_name" name="category_name"
                           class="form-control {{ $errors->has('category_name') ? 'is-invalid' : '' }}"
                           value="{{ old('category_name') }}"
                           placeholder="e.g. Sports, Education"
                           autocomplete="off">
                    @error('category_name')
                        <div class="invalid-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Status <span class="req">*</span></label>
                    <div class="status-radio-group">
                        <label>
                            <input type="radio" name="status" value="Active"
                                   {{ old('status', 'Active') === 'Active' ? 'checked' : '' }}>
                            Active
                        </label>
                        <label>
                            <input type="radio" name="status" value="Inactive"
                                   {{ old('status') === 'Inactive' ? 'checked' : '' }}>
                            Inactive
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn-create">&#43; Add Category</button>
            </form>

            <div class="action-links" style="margin-top: 20px; padding-top: 16px; border-top: 1px solid #eef0f4;">
                <a href="{{ route('admin.sub-categories.add') }}">&#9656; Manage Sub-Categories &rarr;</a>
            </div>
        </div>

    </div>
</div>

@include('includes.backend.footer')

