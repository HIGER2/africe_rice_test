<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::table('employees', function (Blueprint $table) {
        //     //
        // });

        DB::table('employees')->insert([
            'role' => 'admin_test',
            'email' => 'admintest@example.com', // Remplacez par l'email souhaité
            'supervisorId' => 208, // Mettre à jour selon vos besoins
            'jobTitle' => 'Administrator',
            'personalEmail' => null, // ou une adresse email personnelle si nécessaire
            'phone2' => null,
            'phone' => null,
            'address' => '123 Admin St', // Remplacez par une adresse fictive si nécessaire
            'firstName' => 'Admin',
            'lastName' => 'Test',
            'password' => Hash::make('1234'), // Hash du mot de passe '1234'
            'category' => null,
            'grade' => null,
            'bgLevel' => null,
            'matricule' => null,
            'deletedAt' => null,
            'secretKey' => null,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('employee')
            ->where('email', 'admintest@example.com')
            ->delete();
        // Schema::table('employees', function (Blueprint $table) {
        //     //
        // });
    }
};
