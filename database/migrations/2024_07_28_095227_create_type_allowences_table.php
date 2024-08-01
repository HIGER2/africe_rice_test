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
        Schema::create('type_allowances', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Crée la table staff_category
        Schema::create('staff_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_allowance_id')->nullable()->index();
            $table->string('type_allowances_name');
            $table->string('name');
            $table->bigInteger('amount');
            $table->string('currency');
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
                        "device" => "USD"
                    ],
                    (object)[
                        "value" => "GSS7",
                        "montant" => 100,
                        "device" => "USD"
                    ],
                    (object)[
                        "value" => "GSS8",
                        "montant" => 100,
                        "device" => "USD"
                    ],

                    (object)[
                        "value" => "GSS9",
                        "montant" => 100,
                        "device" => "USD"
                    ],
                    (object)[
                        "value" => "APS",
                        "montant" => 250,
                        "device" => "USD"
                    ],
                    (object)[
                        "value" => "PS",
                        "montant" => 350,
                        "device" => "USD"
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
                        "device" => "USD"
                    ],
                    (object)[
                        "value" => "PS",
                        "montant" => 40,
                        "device" => "USD"
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
                        "device" => "USD"
                    ],
                    (object)[
                        "value" => "GSS7",
                        "montant" => 350,
                        "device" => "USD"
                    ],
                    (object)[
                        "value" => "GSS8",
                        "montant" => 350,
                        "device" => "USD"
                    ],

                    (object)[
                        "value" => "GSS9",
                        "montant" => 350,
                        "device" => "USD"
                    ],
                    (object)[
                        "value" => "APS",
                        "montant" => 600,
                        "device" => "USD"
                    ],
                    (object)[
                        "value" => "PS",
                        "montant" => 400,
                        "device" => "USD"
                    ],
                ]
            ],

        ];

        // Insère les données dans la table type_allowences et récupère les IDs
        foreach ($allowences as $allowenceName) {
            $typeAllowenceId = DB::table('type_allowances')->insertGetId([
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
                'type_allowance_id' => $typeAllowenceId,
                'type_allowances_name' => $allowenceName->value,
                'amount' => $categoryName->montant,
                'currency' => $categoryName->device,
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