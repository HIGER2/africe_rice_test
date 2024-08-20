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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('staff_requests_id')
                ->constrained('staff_requests')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->Integer('finance_id')->index()->nullable();
            $table->foreign('finance_id')
                ->references('employeeId')
                ->on('employees')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->Integer('staff_id')->index()->nullable();
            $table->foreign('staff_id')
                ->references('employeeId')
                ->on('employees')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->string('status_payment')->default('pending');
            $table->bigInteger('amount')->nullable();
            $table->datetime('date_payment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};