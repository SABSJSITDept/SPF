@include('includes.backend.header')

    <style>
        h1 { margin-bottom: 20px; font-size: 22px; color: #1a237e; }
        .badge { background: #1a237e; color: #fff; font-size: 12px; padding: 3px 10px; border-radius: 12px; margin-left: 10px; }
        .table-wrapper { overflow-x: auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        thead { background: #1a237e; color: #fff; }
        thead th { padding: 12px 14px; text-align: left; white-space: nowrap; }
        tbody tr:nth-child(even) { background: #f9fafc; }
        tbody tr:hover { background: #edf2ff; }
        tbody td { padding: 10px 14px; vertical-align: middle; white-space: nowrap; border-bottom: 1px solid #eee; }
        .no-records { text-align: center; padding: 40px; color: #888; font-size: 15px; }
        .file-link { color: #1565c0; text-decoration: none; }
        .file-link:hover { text-decoration: underline; }
    </style>

    <div>
        <h1>SPF Registrations <span class="badge">{{ $registrations->count() }} Records</span></h1>

        <div class="table-wrapper">
            @if($registrations->isEmpty())
                <p class="no-records">No registrations found.</p>
            @else
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>MID</th>
                        <th>Full Name</th>
                        <th>Father Name</th>
                        <th>DOB</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Profession</th>
                        <th>Prof. Category</th>
                        <th>State</th>
                        <th>City</th>
                        <th>Anchal</th>
                        <th>Sadhumargi</th>
                        <th>Hobbies</th>
                        <th>Referral</th>
                        <th>Objectives</th>
                        <th>Source</th>
                        <th>Working Status</th>
                        <th>File</th>
                        <th>Registered At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registrations as $index => $reg)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $reg->id }}</td>
                        <td>{{ $reg->mid ?? '-' }}</td>
                        <td>{{ $reg->full_name }}</td>
                        <td>{{ $reg->father_name }}</td>
                        <td>{{ $reg->dob ? \Carbon\Carbon::parse($reg->dob)->format('d-m-Y') : '-' }}</td>
                        <td>{{ $reg->age }}</td>
                        <td>{{ $reg->gender }}</td>
                        <td>{{ $reg->mobile }}</td>
                        <td>{{ $reg->email ?? '-' }}</td>
                        <td>{{ $reg->profession }}</td>
                        <td>{{ $reg->professional_category ?? '-' }}</td>
                        <td>{{ $reg->state }}</td>
                        <td>{{ $reg->city }}</td>
                        <td>{{ $reg->anchal }}</td>
                        <td>{{ $reg->sadhumargi }}</td>
                        <td>{{ $reg->hobbies ?? '-' }}</td>
                        <td>{{ $reg->referral ?? '-' }}</td>
                        <td>
                            @if($reg->objectives)
                                {{ implode(', ', (array) $reg->objectives) }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $reg->source ?? '-' }}</td>
                        <td>{{ $reg->working_status ?? '-' }}</td>
                        <td>
                            @if($reg->file)
                                <a class="file-link" href="{{ asset('uploads/' . $reg->file) }}" target="_blank">View</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $reg->created_at->format('d-m-Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>

@include('includes.backend.footer')
