<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MembersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $members;
    protected $cityMap;
    protected $stateMap;
    protected $anchalMap;
    protected $categoryNameMap;
    protected $branchMap;
    protected $fields;

    // All available field keys in display order
    const ALL_FIELDS = [
        'mid', 'full_name', 'father_name', 'mobile', 'email',
        'gender', 'age', 'dob', 'profession', 'prof_category',
        'state', 'city', 'anchal', 'local_sangh',
        'sadhumargi', 'working_status', 'referral',
    ];

    const HEADINGS_MAP = [
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
        'state'          => 'State',
        'city'           => 'City',
        'anchal'         => 'Anchal',
        'local_sangh'    => 'Local Sangh',
        'sadhumargi'     => 'Sadhumargi',
        'working_status' => 'Working Status',
        'referral'       => 'Referral',
    ];

    public function __construct($members, $cityMap, $stateMap, $anchalMap, $categoryNameMap, $branchMap, $fields = null)
    {
        $this->members         = $members;
        $this->cityMap         = $cityMap;
        $this->stateMap        = $stateMap;
        $this->anchalMap       = $anchalMap;
        $this->categoryNameMap = $categoryNameMap;
        $this->branchMap       = $branchMap;
        // keep only valid keys, preserve display order
        $this->fields = $fields
            ? array_values(array_intersect(self::ALL_FIELDS, $fields))
            : self::ALL_FIELDS;
    }

    public function collection()
    {
        return $this->members;
    }

    public function headings(): array
    {
        return array_values(array_map(fn($f) => self::HEADINGS_MAP[$f], $this->fields));
    }

    public function map($member): array
    {
        $all = [
            'mid'            => $member->mid,
            'full_name'      => $member->full_name,
            'father_name'    => $member->father_name,
            'mobile'         => $member->mobile,
            'email'          => $member->email,
            'gender'         => $member->gender,
            'age'            => $member->age,
            'dob'            => $member->dob,
            'profession'     => $member->professional_category ?? '-',
            'prof_category'  => $this->categoryNameMap[$member->profession] ?? '-',
            'state'          => $this->stateMap[$member->state]             ?? $member->state,
            'city'           => $this->cityMap[$member->city]               ?? $member->city,
            'anchal'         => $this->anchalMap[$member->anchal]           ?? $member->anchal,
            'local_sangh'    => $this->branchMap[$member->local_sangh_id]   ?? $member->local_sangh_id,
            'sadhumargi'     => $member->sadhumargi,
            'working_status' => $member->working_status,
            'referral'       => $member->referral,
        ];

        return array_values(array_map(fn($f) => $all[$f] ?? '', $this->fields));
    }
}
