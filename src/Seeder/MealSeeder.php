<?php

namespace Radlinger\Mealplan\Seeder;

use Radlinger\Mealplan\Classes\Meal;
use Radlinger\Mealplan\Classes\MealPlan;

/**
 * Class MealSeeder
 *
 * Generates demo meal plans.
 * Acts as a replacement for a database in this prototype.
 */
class MealSeeder
{
    /**
     * Create and return an array of meal plan objects.
     *
     * @return array
     */
    public static function generate(): array
    {
        $plans = [];
        $anz_plans = 3;
        $meal_id = 0;
        for ($plans_index = 0; $plans_index < $anz_plans; $plans_index++) {
            $meals = [];
            $anz_meals = 4;
            for ($meal_index = 0; $meal_index < $anz_meals; $meal_index++) {
                $meals[] = new Meal(
                    $meal_id,
                    "Meal $meal_index",
                    "Gluten, Lactose",
                    "Calories: " . rand(400, 700),
                    rand(5, 10)
                );
                $meal_id++;
            }
            $plans[] = new MealPlan(
                $plans_index,
                "Meal Plan $plans_index",
                "HTL Rennweg",
                "Week $plans_index",
                $meals
            );
        }
        return $plans;
    }
}
