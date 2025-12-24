<?php

namespace Radlinger\Mealplan\Classes;

class MealPlan
{
    public int $id;
    public string $name;
    public string $schoolName;
    public string $weekOfDelivery;
    public array $meals;

    public function __construct(
        int    $id,
        string $name,
        string $schoolName,
        string $weekOfDelivery,
        array  $meals,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->schoolName = $schoolName;
        $this->weekOfDelivery = $weekOfDelivery;
        $this->meals = $meals;
    }
}
