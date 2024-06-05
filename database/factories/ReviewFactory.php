<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'book_id' => null,
            'stars' => fake()->numberBetween(1,5),
            'content' => fake()->paragraph(),
            'created_at' => fake()->dateTimeBetween('-2 years'), 
            'updated_at' => function (array $attributes) {
                return fake()->dateTimeBetween($attributes['created_at'], 'now');
            }
        ];
    }

    public function good() 
    {
        return $this->state(function(array $attribute){
            return [
                'stars' => fake()->numberBetween(3,5)
            ];
        });
    }
    
    public function average() 
    {
        return $this->state(function(array $attribute){
            return [
                'stars' => fake()->numberBetween(3,5)
            ];
        });
    }

    public function bad() 
    {
        return $this->state(function(array $attribute){
            return [
                'stars' => fake()->numberBetween(1,3)
            ];
        });
    }
}
