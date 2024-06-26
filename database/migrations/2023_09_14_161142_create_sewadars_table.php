<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sewadars', function (Blueprint $table) {
            $table->id();

            $table->foreignId('group_id')->constrained('groups')->nullable();
            $table->foreignId('list_in_charge_id')->constrained('list_in_charges')->nullable();

            $table->string('badge_number');
            $table->string('photo')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('father_name');

            $table->date('dob');
            $table->string('mobile');
            $table->string('alt_mobile')->nullable();
            $table->string('address');
            $table->string('city');

            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']);
            $table->string('occupation');

            $table->string('education');

            $table->boolean('naamdan')->default(false);

            $table->date('date_of_naamdan')->nullable();
            $table->string('place_of_naamdan')->nullable();
            $table->string('naamdan_by')->nullable();

            $table->string('address_at_time_of_naamdan')->nullable();
            $table->boolean('address_at_time_of_naamdan_same_as_present')->default(false);

            $table->boolean('mobile_permission')->default(false);
            $table->boolean('car_permission')->default(false);

            $table->string('car_number')->nullable();
            $table->string('car_name')->nullable();
            $table->integer('car_seats')->nullable();

            $table->text('reason_of_deletion')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sewadars');
    }
};
