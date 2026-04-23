<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('spf_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('mobile');
            $table->string('mid')->nullable();
            $table->string('full_name');
            $table->string('father_name');
            $table->date('dob');
            $table->integer('age');
            $table->string('email')->nullable();
            $table->string('gender');
            $table->string('profession');
            $table->string('professional_category')->nullable();
            $table->string('state');
            $table->string('city');
            $table->string('anchal');
            $table->string('sadhumargi');
            $table->text('hobbies')->nullable();
            $table->text('referral')->nullable();
            $table->json('objectives')->nullable();
            $table->string('source')->nullable();
            $table->string('working_status')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('spf_registrations');
    }
};
