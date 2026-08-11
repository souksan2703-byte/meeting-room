<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{


    public function up(): void
    {

        Schema::create('bookings', function (Blueprint $table) {

    $table->id();

    $table->foreignId('room_id')
          ->constrained()
          ->cascadeOnDelete();

    $table->string('booker_name');

    $table->string('department');

    $table->string('meeting_title');

    $table->date('booking_date');

    $table->time('start_time');

    $table->time('end_time');

    $table->integer('attendees');

    $table->text('note')->nullable();

    $table->string('status')
          ->default('Pending');

    $table->timestamps();

});

    }




    public function down(): void
    {

        Schema::dropIfExists('bookings');

    }


};