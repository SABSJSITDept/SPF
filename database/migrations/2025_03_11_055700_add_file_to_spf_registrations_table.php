<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    if (!Schema::hasTable('spf_registrations')) {
    Schema::table('spf_registrations', function (Blueprint $table) {
        $table->string('file')->nullable()->after('referral');
    });
}
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
{
    Schema::table('spf_registrations', function (Blueprint $table) {
        $table->dropColumn('file');
    });
}
};
