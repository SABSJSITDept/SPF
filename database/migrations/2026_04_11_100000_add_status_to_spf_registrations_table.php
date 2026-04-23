<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('spf_registrations', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('file');
        });

        // Set all existing records to 'approved'
        DB::table('spf_registrations')->update(['status' => 'approved']);
    }

    public function down()
    {
        Schema::table('spf_registrations', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
