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
        Schema::create('employee_informatons', function (Blueprint $table) {
            $table->id();
            $table->integer('employees_id')->index();
            $table->foreign('employees_id')
                ->references('employeeId')
                ->on('employees')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('status')->nullable();
            $table->string('status_input')->default(false);
            $table->integer('room')->nullable()->default(0);
            $table->string('marital_status');

            $table->bigInteger('total_t_w_f')->nullable();
            $table->bigInteger('total_p_e_t')->nullable();
            $table->bigInteger('total_f_i_a')->nullable();
            $table->bigInteger('total_u')->nullable();
            $table->bigInteger('total_p_c_a')->nullable();
            $table->bigInteger('total_amount')->nullable();
            $table->date('depart_date')->nullable();
            $table->date('taking_date')->nullable();
            $table->integer('number_child')->nullable()->default(0);
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
