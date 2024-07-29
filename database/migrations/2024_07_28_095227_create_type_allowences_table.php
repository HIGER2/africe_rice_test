<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up(): void
    {
        // Crée la table type_allowences
        Schema::create('type_allowences', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Crée la table staff_category
        Schema::create('staff_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_allowence_id')->nullable()->index();
            $table->string('type_allowences_name');
            $table->string('name');
            $table->bigInteger('montant');
            $table->timestamps();
        });

        // Définit les noms d'allowance
        $allowences = [
            'Travel with Family',
            'Personal effect Transportation',
            'Family initial accommodation',
            'Unforseen',
            'Paliative for change in allowance',
        ];

        // Insère les données dans la table type_allowences et récupère les IDs
        foreach ($allowences as $allowenceName) {
            $typeAllowenceId = DB::table('type_allowences')->insertGetId([
                'name' => $allowenceName,
            ]);

            // Insère les données dans la table staff_category
            $this->insertStaffCategories($typeAllowenceId, $allowenceName);
        }
    }

    protected function insertStaffCategories($typeAllowenceId, $allowenceName)
    {
        $categories = ['GS_1_5', 'GS_6_9', 'APS', 'PS'];

        foreach ($categories as $categoryName) {
            DB::table('staff_categories')->insert([
                'type_allowence_id' => $typeAllowenceId,
                'type_allowences_name' => $allowenceName,
                'montant' => 10000,
                'name' => $categoryName,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_allowences');
    }
};
