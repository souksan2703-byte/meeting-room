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


            // ผู้จอง
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();


            // ห้องประชุม
            $table->foreignId('room_id')
                  ->constrained()
                  ->cascadeOnDelete();



            $table->string('title');


            $table->dateTime('start_time');


            $table->dateTime('end_time');



            $table->text('description')
                  ->nullable();



            $table->enum('status',[

                'pending',
                'approved',
                'cancelled'

            ])
            ->default('pending');



            $table->timestamps();

        });

    }




    public function down(): void
    {

        Schema::dropIfExists('bookings');

    }


};