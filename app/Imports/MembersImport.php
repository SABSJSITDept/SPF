<?php

namespace App\Imports;

use App\Models\SpfRegistration;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class MembersImport implements ToModel, WithHeadingRow
{
    protected $admin;

    public function __construct($admin = null)
    {
        $this->admin = $admin;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    /**
     * Helper to return null if string is empty or contains only spaces.
     */
    private function parseValue($value)
    {
        if ($value === null) {
            return null;
        }
        $val = trim((string)$value);
        return $val === '' ? null : $val;
    }

    public function model(array $row)
    {
        \Log::info('Excel Row Keys: ', array_keys($row));
        // Skip if mobile is empty because it's a required field typically
        $mobile = $this->parseValue($row['mobile'] ?? null);
        if (!$mobile) {
            return null;
        }

        // Handle dob parsing (Excel uses numeric values for dates sometimes)
        $dob = null;
        if (!empty($row['dob'])) {
            try {
                if (is_numeric($row['dob'])) {
                    $dob = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['dob'])->format('Y-m-d');
                } else {
                    $dob = Carbon::parse($row['dob'])->format('Y-m-d');
                }
            } catch (\Exception $e) {
                $dob = null;
            }
        }

        // Handle objectives (model casts to array, so we parse comma separated values)
        $objectives = [];
        if (!empty($row['objectives'])) {
            $objectives = array_map('trim', explode(',', $row['objectives']));
        }

        $fatherName = $this->parseValue($row['father_name'] ?? null) ?? $this->parseValue($row['fathers_name'] ?? null);
        $anchal     = $this->parseValue($row['anchal_id'] ?? null) ?? $this->parseValue($row['anchal'] ?? null);
        
        // If current admin is an Anchal Operator, force their anchal ID
        if ($this->admin && $this->admin->isAnchalOperator()) {
            $anchal = $this->admin->anchal;
        }

        // Create the registration model.
        return new SpfRegistration([
            'mobile'                => $mobile,
            'mid'                   => $this->parseValue($row['mid'] ?? null),
            'full_name'             => $this->parseValue($row['full_name'] ?? null) ?? 'N/A',
            'father_name'           => $fatherName ?? 'N/A',
            'dob'                   => $dob ?? '1970-01-01',
            'age'                   => $this->parseValue($row['age'] ?? null) ?? 0,
            'gender'                => $this->parseValue($row['gender'] ?? null) ?? 'N/A',
            'profession'            => $this->parseValue($row['profession'] ?? null) ?? 'N/A',
            'professional_category' => $this->parseValue($row['professional_category'] ?? null),
            'local_sangh_id'        => $this->parseValue($row['local_sangh_id'] ?? null),
            'city'                  => $this->parseValue($row['city'] ?? null) ?? $this->parseValue($row['city_name'] ?? null) ?? $this->parseValue($row['district'] ?? null) ?? 'N/A',
            'state'                 => $this->parseValue($row['state'] ?? null) ?? $this->parseValue($row['state_name'] ?? null) ?? 'N/A',
            'anchal'                => $anchal ?? 'N/A',
            'sadhumargi'            => $this->parseValue($row['sadhumargi'] ?? null) ?? 'N/A',
            'hobbies'               => $this->parseValue($row['hobbies'] ?? null),
            'referral'              => $this->parseValue($row['referral'] ?? null),
            'objectives'            => empty($objectives) ? null : $objectives,
            'source'                => $this->parseValue($row['source'] ?? null),
            'working_status'        => $this->parseValue($row['working_status'] ?? null),
            'status'                => 'pending',
        ]);
    }
}
