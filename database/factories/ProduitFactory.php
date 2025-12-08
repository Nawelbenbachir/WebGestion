<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produit>
 */
class ProduitFactory extends Factory
{
    public function definition(): array
    {
        $codeNumber = $this->faker->unique()->numberBetween(100, 9999);
        return [
            'id_societe'   => rand(1, 3),
            'code_produit' => 'PRD-' . str_pad($codeNumber, 4, '0', STR_PAD_LEFT),
            'code_comptable' => $this->faker->numerify('7###'),
            'description'    => $this->faker->words(10, true),
            'prix_ht'        => $this->faker->randomFloat(2, 5, 500), // entre 5€ et 500€
            'tva'            => $this->faker->randomElement([5.5, 10, 20]),
            'qt_stock'       => $this->faker->numberBetween(1, 200),
            'categorie'      => $this->faker->randomElement(['Matériel', 'Service', 'Accessoire']),
        ];
    }
}

