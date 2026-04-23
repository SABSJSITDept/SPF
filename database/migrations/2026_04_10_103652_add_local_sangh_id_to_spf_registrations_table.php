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
        Schema::table('spf_registrations', function (Blueprint $table) {
            $table->unsignedBigInteger('local_sangh_id')->nullable()->after('sadhumargi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spf_registrations', function (Blueprint $table) {
            $table->dropColumn('local_sangh_id');
        });
    }
};
