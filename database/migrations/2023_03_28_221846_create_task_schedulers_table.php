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
        Schema::create('task_schedulers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('details');
            $table->timestamp("time");
            $table->integer("repeat")->range(0,1);
            $table->integer("repeats_per_day");
            $table->integer("times_of_repeat");
            $table->foreignId("patient_id")->constrained('patients')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_schedulers');
    }
};
