<?php

namespace App\Exports;

use App\Models\SpfRegistration;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    protected $anchalId;

    // ✅ Constructor to accept anchal ID
    public function __construct($anchalId)
    {
        $this->anchalId = $anchalId;
    }

    public function collection()
    {
        return SpfRegistration::select(
            'spf_registrations.id',
            'spf_registrations.full_name',
            'spf_registrations.mobile',
            'spf_registrations.mid',
            'spf_registrations.father_name',
            'spf_registrations.dob',
            'spf_registrations.age',
            'spf_registrations.email',
            'spf_registrations.gender',
            'spf_registrations.profession',
            'spf_registrations.professional_category',
            DB::connection('sabsjs_member')->raw('(SELECT state_name FROM `sabsjs_member`.states WHERE states.state_id = spf_registrations.state) AS state_name'),
            DB::connection('sabsjs_member')->raw('(SELECT city_name FROM `sabsjs_member`.cities WHERE cities.city_id = spf_registrations.city) AS city_name'),
            DB::connection('sabsjs_member')->raw('(SELECT name FROM `sabsjs_member`.anchal WHERE anchal.anchal_id = spf_registrations.anchal) AS anchal_name'),
            'spf_registrations.sadhumargi',
            'spf_registrations.hobbies',
            'spf_registrations.referral',
            'spf_registrations.objectives',
            'spf_registrations.source',
            'spf_registrations.working_status',
            'spf_registrations.created_at',
            'spf_registrations.updated_at'
        )
        ->where('spf_registrations.anchal', $this->anchalId) // ✅ Filter by Anchal ID
        ->get();
    }

    public function headings(): array
    {
        return [
            "ID",
            "Full Name",
            "Mobile",
            "MID",
            "Father Name",
            "DOB",
            "Age",
            "Email",
            "Gender",
            "Profession",
            "Professional Category",
            "State Name",
            "City Name",
            "Anchal Name",
            "Sadhumargi",
            "Hobbies",
            "Referral",
            "Objectives",
            "Source",
            "Working Status",
            "Created At",
            "Updated At"
        ];
    }
}
