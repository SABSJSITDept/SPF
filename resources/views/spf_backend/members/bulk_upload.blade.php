@include('includes.backend.header')

<style>
    .bulk-upload-wrapper {
        padding: 24px;
        font-family: 'Inter', sans-serif;
    }
    .breadcrumb {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .breadcrumb a {
        color: #1a237e;
        text-decoration: none;
        font-weight: 500;
    }
    .breadcrumb a:hover {
        text-decoration: underline;
    }
    
    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 24px;
    }

    .upload-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 32px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        margin-bottom: 32px;
        border: 1px solid #e5e7eb;
        text-align: center;
    }
    
    .upload-icon {
        font-size: 48px;
        color: #1a237e;
        margin-bottom: 16px;
    }
    
    .upload-heading {
        font-size: 18px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }
    
    .upload-desc {
        color: #6b7280;
        font-size: 14px;
        margin-bottom: 24px;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }

    .file-input-wrapper {
        position: relative;
        display: inline-block;
        margin-bottom: 24px;
    }
    
    .file-input {
        display: block;
        width: 100%;
        max-width: 350px;
        margin: 0 auto;
        padding: 12px;
        border: 2px dashed #cbd5e1;
        border-radius: 8px;
        background: #f8fafc;
        cursor: pointer;
        color: #475569;
        transition: all 0.2s;
    }
    
    .file-input:hover {
        border-color: #1a237e;
        background: #f1f5f9;
    }

    .btn-submit {
        background: #1a237e;
        color: white;
        border: none;
        padding: 12px 32px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: background 0.2s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .btn-submit:hover {
        background: #283593;
    }
    
    .btn-download-sample {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f3f4f6;
        color: #374151;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        border: 1px solid #d1d5db;
        transition: all 0.2s;
        margin-bottom: 32px;
    }
    
    .btn-download-sample:hover {
        background: #e5e7eb;
        color: #111827;
    }

    .format-section-title {
        font-size: 18px;
        font-weight: 600;
        color: #111827;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .table-container {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid #e5e7eb;
        overflow-x: auto;
    }

    .excel-format-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        white-space: nowrap;
    }

    .excel-format-table th {
        background: #10b981; /* Excel green */
        color: white;
        font-weight: 600;
        padding: 12px 16px;
        text-align: left;
        border-right: 1px solid #059669;
        border-bottom: 2px solid #047857;
    }
    
    .excel-format-table th:last-child {
        border-right: none;
    }

    .excel-format-table td {
        padding: 12px 16px;
        color: #4b5563;
        border-bottom: 1px solid #e5e7eb;
        border-right: 1px solid #e5e7eb;
    }
    
    .excel-format-table tr:last-child td {
        border-bottom: none;
    }
    
    .excel-format-table tr:hover {
        background: #f9fafb;
    }
    
    .alert-success {
        background: #d1fae5;
        border-left: 4px solid #10b981;
        color: #065f46;
        padding: 16px;
        border-radius: 4px;
        margin-bottom: 24px;
        font-weight: 500;
    }
</style>

<div class="bulk-upload-wrapper">
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a> 
        <span>&rsaquo;</span> 
        <span>Members</span> 
        <span>&rsaquo;</span> 
        <span style="color: #4b5563;">Bulk Upload</span>
    </div>

    <h1 class="page-title">Bulk Member Upload</h1>

    @if(session('success'))
        <div class="alert-success">
            &#10004; {{ session('success') }}
        </div>
    @endif
    
    @if($errors->any())
        <div style="background: #fee2e2; border-left: 4px solid #ef4444; color: #b91c1c; padding: 16px; border-radius: 4px; margin-bottom: 24px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="upload-card">
        <div class="upload-icon">&#128194;</div>
        <h2 class="upload-heading">Upload Excel File</h2>
        <p class="upload-desc">Please upload your member data using a valid Excel (.xlsx, .xls) or CSV file. Ensure the headers exactly match the format specified below.</p>
        
        <form action="{{ route('admin.members.bulk_upload.post') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="file-input-wrapper">
                <input type="file" name="excel_file" id="excel_file" class="file-input" accept=".xlsx, .xls, .csv" required>
            </div>
            <br>
            <button type="submit" class="btn-submit">Process &amp; Upload</button>
        </form>
    </div>

    <div class="format-section-title">
        <span>&#128202;</span> Expected Excel Format 
    </div>
    
    <a href="#" class="btn-download-sample" onclick="showToast('Sample file generation will be implemented with Excel export library.', 'info'); return false;">
        <span>&#11015;</span> Download Sample Template
    </a>

    <div class="table-container">
        <table class="excel-format-table">
            <thead>
                <tr>
                    <th>MOBILE</th>
                    <th>MID</th>
                    <th>FULL NAME</th>
                    <th>FATHERS NAME</th>
                    <th>DOB</th>
                    <th>AGE</th>
                    <th>GENDER</th>
                    <th>PROFESSION</th>
                    <th>PROFESSIONAL CATEGORY</th>
                    <th>LOCAL SANGH ID</th>
                    <th>CITY</th>
                    <th>STATE</th>
                    <th>ANCHAL ID</th>
                    <th>ANCHAL</th>
                    <th>SADHUMARGI</th>
                    <th>HOBBIES</th>
                    <th>REFERRAL</th>
                    <th>OBJECTIVES</th>
                    <th>SOURCE</th>
                    <th>WORKING STATUS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>9876543210</td>
                    <td>MID12345</td>
                    <td>John Doe</td>
                    <td>Dev Doe</td>
                    <td>1990-01-15</td>
                    <td>34</td>
                    <td>Male</td>
                    <td>1</td>
                    <td>Doctor</td>
                    <td>10</td>
                    <td>1</td>
                    <td>1</td>
                    <td>2</td>
                    <td>Mumbai Anchal</td>
                    <td>Yes</td>
                    <td>Reading, Traveling</td>
                    <td>Self</td>
                    <td>Networking</td>
                    <td>Website</td>
                    <td>Working</td>
                </tr>
                <tr>
                    <td>9876543211</td>
                    <td>MID12346</td>
                    <td>Jane Smith</td>
                    <td>Robert Smith</td>
                    <td>1992-05-20</td>
                    <td>31</td>
                    <td>Female</td>
                    <td>2</td>
                    <td>Engineer</td>
                    <td>12</td>
                    <td>2</td>
                    <td>2</td>
                    <td>3</td>
                    <td>Delhi Anchal</td>
                    <td>No</td>
                    <td>Music</td>
                    <td>Referral</td>
                    <td>Connect</td>
                    <td>App</td>
                    <td>Student</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@include('includes.backend.footer')
