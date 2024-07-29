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
            $table->string('device');
            $table->timestamps();
        });

        // Définit les noms d'allowance
        $allowences = [
            (object)[
                "value" => 'Travel with Family',
                "cate" => [
                    (object)[
                        "value" => "GSS1",
                        "montant" => 10000,
                        "device" => "XOF"
                    ],
                    (object)[
                        "value" => "GSS2",
                        "montant" => 10000,
                        "device" => "XOF"

                    ],
                    (object)[
                        "value" => "GSS3",
                        "montant" => 10000,
                        "device" => "XOF"

                    ],
                    (object)[
                        "value" => "GSS4",
                        "montant" => 10000,
                        "device" => "XOF"

                    ],
                    (object)[
                        "value" => "GSS5",
                        "montant" => 10000,
                        "device" => "XOF"

                    ],
                    (object)[
                        "value" => "GSS6",
                        "montant" => 10000,
                        "device" => "XOF"

                    ],
                    (object)[
                        "value" => "GSS7",
                        "montant" => 10000,
                        "device" => "XOF"

                    ],
                    (object)[
                        "value" => "GSS8",
                        "montant" => 10000,
                        "device" => "XOF"

                    ],
                    (object)[
                        "value" => "GSS9",
                        "montant" => 10000,
                        "device" => "XOF"

                    ],
                    (object)[
                        "value" => "APS",
                        "montant" => 10000,
                        "device" => "XOF"

                    ],
                    (object)[
                        "value" => "PS",
                        "montant" => 10000,
                        "device" => "XOF"

                    ],
                ]
            ],
            (object)[
                "value" => 'Personal effect Transportation',
                "cate" => [
                    (object)[
                        "value" => "GSS1",
                        "montant" => 30000,
                        "device" => "XOF"
                    ],
                    (object)[
                        "value" => "GSS2",
                        "montant" => 30000,
                        "device" => "XOF"
                    ],
                    (object)[
                        "value" => "GSS3",
                        "montant" => 30000,
                        "device" => "XOF"
                    ],
                    (object)[
                        "value" => "GSS4",
                        "montant" => 30000,
                        "device" => "XOF"
                    ],
                    (object)[
                        "value" => "GSS5",
                        "montant" => 30000,
                        "device" => "XOF"
                    ],
                    (object)[
                        "value" => "GSS6",
                        "montant" => 100,
                        "device" => "$"
                    ],
                    (object)[
                        "value" => "GSS7",
                        "montant" => 100,
                        "device" => "$"
                    ],
                    (object)[
                        "value" => "GSS8",
                        "montant" => 100,
                        "device" => "$"
                    ],

                    (object)[
                        "value" => "GSS9",
                        "montant" => 100,
                        "device" => "$"
                    ],
                    (object)[
                        "value" => "APS",
                        "montant" => 250,
                        "device" => "$"
                    ],
                    (object)[
                        "value" => "PS",
                        "montant" => 350,
                        "device" => "$"
                    ],
                ]
            ],
            (object)[
                "value" => 'Family initial accommodation',
                "cate" => [
                    (object)[
                        "value" => "GSS1",
                        "montant" => 15000,
                        "device" => "XOF"
                    ],
                    (object)[
                        "value" => "GSS2",
                        "montant" => 15000,
                        "device" => "XOF"
                    ],
                    (object)[
                        "value" => "GSS3",
                        "montant" => 15000,
                        "device" => "XOF"
                    ],
                    (object)[
                        "value" => "GSS4",
                        "montant" => 15000,
                        "device" => "XOF"
                    ],
                    (object)[
                        "value" => "GSS5",
                        "montant" => 15000,
                        "device" => "XOF"
                    ],
                    (object)[
                        "value" => "GSS6",
                        "montant" => 15000,
                        "device" => "XOF"
                    ],
                    (object)[
                        "value" => "GSS7",
                        "montant" => 15000,
                        "device" => "XOF"
                    ],
                    (object)[
                        "value" => "GSS8",
                        "montant" => 15000,
                        "device" => "XOF"
                    ],

                    (object)[
                        "value" => "GSS9",
                        "montant" => 15000,
                        "device" => "XOF"
                    ],
                    (object)[
                        "value" => "APS",
                        "montant" => 15000,
                        "device" => "XOF"
                    ],
                    (object)[
                        "value" => "PS",
                        "montant" => 15000,
                        "device" => "XOF"
                    ],
                ]
            ],
            (object)[
                "value" => 'Unforseen',
                "cate" => [
                    (object)[
                        "value" => "GSS1",
                        "montant" => 10000,
                        "device" => "XOF"
                    ],
                    (object)[
                        "value" => "GSS2",
                        "montant" => 10000,
                        "device" => "XOF"
                    ],
                    (object)[
                        "value" => "GSS3",
                        "montant" => 10000,
                        "device" => "XOF"
                    ],
                    (object)[
                        "value" => "GSS4",
                        "montant" => 10000,
                        "device" => "XOF"
                    ],
                    (object)[
                        "value" => "GSS5",
                        "montant" => 10000,
                        "device" => "XOF"
                    ],
                    (object)[
                        "value" => "GSS6",
                        "montant" => 15000,
                        "device" => "XOF"
                    ],
                    (object)[
                        "value" => "GSS7",
                        "montant" => 15000,
                        "device" => "XOF"
                    ],
                    (object)[
                        "value" => "GSS8",
                        "montant" => 15000,
                        "device" => "XOF"
                    ],

                    (object)[
                        "value" => "GSS9",
                        "montant" => 15000,
                        "device" => "XOF"
                    ],
                    (object)[
                        "value" => "APS",
                        "montant" => 35,
                        "device" => "$"
                    ],
                    (object)[
                        "value" => "PS",
                        "montant" => 40,
                        "device" => "$"
                    ],
                ]
            ],
            (object)[
                "value" => 'Paliative for change in allowance',
                "cate" => [
                    (object)[
                        "value" => "GSS1",
                        "montant" => 0,
                        "device" => "XOF"
                    ],
                    (object)[
                        "value" => "GSS2",
                        "montant" => 0,
                        "device" => "XOF"
                    ],
                    (object)[
                        "value" => "GSS3",
                        "montant" => 0,
                        "device" => "XOF"
                    ],
                    (object)[
                        "value" => "GSS4",
                        "montant" => 0,
                        "device" => "XOF"
                    ],
                    (object)[
                        "value" => "GSS5",
                        "montant" => 0,
                        "device" => "XOF"
                    ],
                    (object)[
                        "value" => "GSS6",
                        "montant" => 350,
                        "device" => "$"
                    ],
                    (object)[
                        "value" => "GSS7",
                        "montant" => 350,
                        "device" => "$"
                    ],
                    (object)[
                        "value" => "GSS8",
                        "montant" => 350,
                        "device" => "$"
                    ],

                    (object)[
                        "value" => "GSS9",
                        "montant" => 350,
                        "device" => "$"
                    ],
                    (object)[
                        "value" => "APS",
                        "montant" => 600,
                        "device" => "$"
                    ],
                    (object)[
                        "value" => "PS",
                        "montant" => 400,
                        "device" => "$"
                    ],
                ]
            ],

        ];

        // Insère les données dans la table type_allowences et récupère les IDs
        foreach ($allowences as $allowenceName) {
            $typeAllowenceId = DB::table('type_allowences')->insertGetId([
                'name' => $allowenceName->value,
            ]);

            // Insère les données dans la table staff_category
            $this->insertStaffCategories($typeAllowenceId, $allowenceName);
        }
    }

    protected function insertStaffCategories($typeAllowenceId, $allowenceName)
    {
        // $categories =;

        foreach ($allowenceName->cate as $categoryName) {
            DB::table('staff_categories')->insert([
                'type_allowence_id' => $typeAllowenceId,
                'type_allowences_name' => $allowenceName->value,
                'montant' => $categoryName->montant,
                'device' => $categoryName->device,
                'name' => $categoryName->value,
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