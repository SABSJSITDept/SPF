<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'operator', 'anchal_operator'])
                  ->default('operator')
                  ->after('password');
            $table->string('anchal')->nullable()->after('role'); // only for anchal_operator
        });
    }

    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['role', 'anchal']);
        });
    }
};
