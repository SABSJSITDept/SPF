<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Members Export</title>
<style>
    @page { size: A4 landscape; margin: 8mm 10mm; }
    body { font-family: dejavusans, sans-serif; margin: 0; }
    h2 { text-align: center; font-size: 11pt; color: #1a237e; margin: 0 0 2pt; }
    .sub { text-align: center; font-size: 7pt; color: #555; margin: 0 0 5pt; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #1a237e; color: #fff; border: 0.5pt solid #555; padding: 2pt 3pt; font-size: 7pt; word-break: break-word; }
    td { border: 0.5pt solid #ccc; padding: 2pt 3pt; font-size: 7pt; word-break: break-word; }
    .idx { width: 14pt; text-align: center; }
    .footer { text-align: center; font-size: 6pt; color: #888; margin-top: 6pt; font-style: italic; }
</style>
</head>
<body>
    <h2>Sadhumargi Professional Forum</h2>
    <div class="sub">Total Records: {{ count($members) }} &nbsp;|&nbsp; </div>

    @php
        $allFields = ['mid','full_name','father_name','mobile','email','gender','age','dob','profession','prof_category','state','city','anchal','local_sangh','sadhumargi','working_status','referral'];
        $fields = $fields ?? $allFields;

        $hasLocation = count(array_intersect($fields, ['state','city','anchal'])) > 0;
        $pdfFields   = array_values(array_diff($fields, ['state','city','anchal']));
        if ($hasLocation) $pdfFields[] = 'location';
        $totalPdfCols = count($pdfFields) + 1;

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
            'location'       => 'Location (State/City/Anchal)',
        ];
    @endphp

    <table>
        <thead repeat="1">
            <tr>
                <th class="idx">#</th>
                @foreach($pdfFields as $col)
                <th>{{ $headings[$col] ?? $col }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($members as $i => $reg)
            @php
                $rowBg   = ($i % 2 === 0) ? '#ffffff' : '#f0f4ff';
                $locText = '-';
                if ($hasLocation) {
                    $lp = [];
                    if (in_array('state',  $fields) && !empty($stateMap[$reg->state]))   $lp[] = $stateMap[$reg->state];
                    if (in_array('city',   $fields) && !empty($cityMap[$reg->city]))     $lp[] = $cityMap[$reg->city];
                    if (in_array('anchal', $fields) && !empty($anchalMap[$reg->anchal])) $lp[] = $anchalMap[$reg->anchal];
                    $locText = implode(' / ', $lp) ?: '-';
                }
            @endphp
            <tr style="background:{{ $rowBg }}">
                <td class="idx" style="background:{{ $rowBg }};text-align:center">{{ $i + 1 }}</td>
                @foreach($pdfFields as $col)
                    @if($col === 'mid')              <td style="background:{{ $rowBg }}">{{ $reg->mid ?? '-' }}</td>
                    @elseif($col === 'full_name')    <td style="background:{{ $rowBg }}">{{ $reg->full_name }}</td>
                    @elseif($col === 'father_name')  <td style="background:{{ $rowBg }}">{{ $reg->father_name }}</td>
                    @elseif($col === 'mobile')       <td style="background:{{ $rowBg }}">{{ $reg->mobile }}</td>
                    @elseif($col === 'email')        <td style="background:{{ $rowBg }}">{{ $reg->email ?? '-' }}</td>
                    @elseif($col === 'gender')       <td style="background:{{ $rowBg }}">{{ $reg->gender }}</td>
                    @elseif($col === 'age')          <td style="background:{{ $rowBg }}">{{ $reg->age }}</td>
                    @elseif($col === 'dob')          <td style="background:{{ $rowBg }}">{{ $reg->dob }}</td>
                    @elseif($col === 'profession')   <td style="background:{{ $rowBg }}">{{ $reg->professional_category ?? '-' }}</td>
                    @elseif($col === 'prof_category')<td style="background:{{ $rowBg }}">{{ $categoryNameMap[$reg->profession] ?? '-' }}</td>
                    @elseif($col === 'local_sangh')  <td style="background:{{ $rowBg }}">{{ $branchMap[$reg->local_sangh_id] ?? '-' }}</td>
                    @elseif($col === 'sadhumargi')   <td style="background:{{ $rowBg }}">{{ $reg->sadhumargi ?? '-' }}</td>
                    @elseif($col === 'working_status')<td style="background:{{ $rowBg }}">{{ $reg->working_status ?? '-' }}</td>
                    @elseif($col === 'referral')     <td style="background:{{ $rowBg }}">{{ $reg->referral ?? '-' }}</td>
                    @elseif($col === 'location')     <td style="background:{{ $rowBg }}">{{ $locText }}</td>
                    @endif
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">Auto generated from the SPF Portal and does not require any verification.</div>
</body>
</html>
