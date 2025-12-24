<?php

namespace Radlinger\Mealplan\Api;

use Radlinger\Mealplan\Seeder\MealSeeder;

/**
 * Class GetMeals
 *
 * Provides meal data as JSON.
 * Used by the API endpoint.
 */
class GetMeals
{
    /**
     * Return a meal matching the given ID as JSON.
     *
     * @param int $id Meal plan ID
     *
     * @return string JSON encoded meal plan or error
     */
    public static function getMealWithId(int $id): string
    {
        $mealPlans = MealSeeder::generate();
        foreach ($mealPlans as $mealPlan) {
            if (!isset($mealPlan->meals) || !is_array($mealPlan->meals)) {
                continue;
            }
            foreach ($mealPlan->meals as $meal) {
                if (isset($meal->id) && $meal->id === $id) {
                    return json_encode([
                        "id" => $meal->id,
                        "name" => $meal->name,
                        "allergens" => $meal->allergens,
                        "nutritionalInfo" => $meal->nutritionalInfo,
                        "price" => $meal->price
                    ]);
                }
            }
        }
        return json_encode(["error" => "No meal with ID found!"]);
    }
}
