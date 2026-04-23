<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpfRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'mobile', 'mid', 'full_name', 'father_name', 'dob', 'age', 'email',
        'gender', 'profession', 'professional_category', 'state', 'city', 'anchal',
        'sadhumargi', 'local_sangh_id', 'hobbies', 'referral', 'objectives', 'source', 'working_status', 'file', 'status',
        'document_type',
    ];
    
    protected $casts = [
        'objectives' => 'array',  // ✅ Ensure objectives is stored as JSON array
        'dob' => 'date',  // ✅ Ensure correct date format
    ];
}
