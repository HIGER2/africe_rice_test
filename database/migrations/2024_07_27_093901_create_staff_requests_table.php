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
        Schema::create('staff_requests', function (Blueprint $table) {
            $table->id();
            $table->Integer('employees_id')->index();
            $table->foreign('employees_id')
                ->references('employeeId')
                ->on('employees')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('status')->nullable();
            $table->boolean('status_input')->default(false);
            $table->integer('room')->nullable()->default(0);
            $table->string('marital_status')->nullable();

            $table->bigInteger('total_t_w_f')->default(0);
            $table->bigInteger('total_p_e_t')->default(0);
            $table->bigInteger('total_f_i_a')->default(0);
            $table->bigInteger('total_u')->default(0);
            $table->bigInteger('total_p_c_a')->default(0);
            $table->bigInteger('total_amount')->default(0);
            $table->date('depart_date')->nullable();
            $table->date('taking_date')->nullable();
            $table->integer('number_child')->nullable();
            $table->string('category')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_informatons');
    }
};