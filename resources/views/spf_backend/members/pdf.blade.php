<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Members Export</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ccc; padding: 5px 7px; text-align: left; vertical-align: top; }
        th { background-color: #1a237e; color: #fff; font-weight: bold; font-size: 10px; }
        tr:nth-child(even) td { background-color: #f5f5f5; }
        h2 { text-align: center; margin-bottom: 4px; font-size: 16px; color: #1a237e; }
        .subtitle { text-align: center; font-size: 11px; color: #555; margin-bottom: 12px; }
        .footer { margin-top: 24px; border-top: 1px solid #ccc; padding-top: 8px; text-align: center; font-size: 9px; color: #888; font-style: italic; }
    </style>
</head>
<body>
    <h2>Members Export</h2>
    <div class="subtitle">Total Records: {{ count($members) }} &nbsp;|&nbsp; Generated: {{ now()->format('d M Y, h:i A') }}</div>

    @php
        $allFields = ['mid','full_name','father_name','mobile','email','gender','age','dob','profession','prof_category','state','city','anchal','local_sangh','sadhumargi','working_status','referral'];
        $fields = $fields ?? $allFields;

        // PDF: merge state/city/anchal into one "Location" column
        $hasLocation = count(array_intersect($fields, ['state','city','anchal'])) > 0;
        $pdfFields   = array_values(array_diff($fields, ['state','city','anchal']));
        if ($hasLocation) $pdfFields[] = 'location';

        $headings = [
            'mid'            => 'MID',
            'full_name'      => 'Full Name',
            'father_name'    => 'Father Name',
            'mobile'         => 'Mobile',
            'email'          => 'Email',
            'gender'         => 'Gender',
            'age'            => 'Age',
            'dob'            => 'DOB',
            'profession'     => 'Profession',
            'prof_category'  => 'Prof. Category',
            'local_sangh'    => 'Local Sangh',
            'sadhumargi'     => 'Sadhumargi',
            'working_status' => 'Working Status',
            'referral'       => 'Referral',
            'location'       => 'Location (State / City / Anchal)',
        ];
    @endphp

    <table>
        <thead>
            <tr>
                <th>#</th>
                @foreach($pdfFields as $col)
                    <th>{{ $headings[$col] ?? $col }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($members as $i => $reg)
            <tr>
                <td>{{ $i + 1 }}</td>
                @foreach($pdfFields as $col)
                    @if($col === 'mid')         <td>{{ $reg->mid ?? '-' }}</td>
                    @elseif($col === 'full_name')    <td>{{ $reg->full_name }}</td>
                    @elseif($col === 'father_name')  <td>{{ $reg->father_name }}</td>
                    @elseif($col === 'mobile')       <td>{{ $reg->mobile }}</td>
                    @elseif($col === 'email')        <td>{{ $reg->email ?? '-' }}</td>
                    @elseif($col === 'gender')       <td>{{ $reg->gender }}</td>
                    @elseif($col === 'age')          <td>{{ $reg->age }}</td>
                    @elseif($col === 'dob')          <td>{{ $reg->dob }}</td>
                    @elseif($col === 'profession')   <td>{{ $reg->professional_category ?? '-' }}</td>
                    @elseif($col === 'prof_category')<td>{{ $categoryNameMap[$reg->profession] ?? '-' }}</td>
                    @elseif($col === 'local_sangh')  <td>{{ $branchMap[$reg->local_sangh_id] ?? '-' }}</td>
                    @elseif($col === 'sadhumargi')   <td>{{ $reg->sadhumargi ?? '-' }}</td>
                    @elseif($col === 'working_status')<td>{{ $reg->working_status ?? '-' }}</td>
                    @elseif($col === 'referral')     <td>{{ $reg->referral ?? '-' }}</td>
                    @elseif($col === 'location')
                        <td>
                            @php
                                $parts = [];
                                if (in_array('state',  $fields) && ($stateMap[$reg->state]   ?? '')) $parts[] = $stateMap[$reg->state];
                                if (in_array('city',   $fields) && ($cityMap[$reg->city]     ?? '')) $parts[] = $cityMap[$reg->city];
                                if (in_array('anchal', $fields) && ($anchalMap[$reg->anchal] ?? '')) $parts[] = $anchalMap[$reg->anchal];
                            @endphp
                            {{ implode(' / ', $parts) ?: '-' }}
                        </td>
                    @endif
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Auto generated from the SPF Portal and does not require any verification.
    </div>
</body>
</html>
