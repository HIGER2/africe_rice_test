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
        Schema::create('staff_children', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_requests_id')
                ->constrained('staff_requests')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->integer("age")->default(0);
            $table->string('sex');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_children');
    }
};