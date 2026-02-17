<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="DietPlan",
 *     title="DietPlan",
 *     description="DietPlan Model",
 *     @OA\Property(property="id", type="integer", example=8),
 *     @OA\Property(property="days", type="array", @OA\Items(type="string"), example={"sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"}),
 *     @OA\Property(property="breakfast", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="lunch", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="dinner", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="meal_4", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="meal_5", type="array", @OA\Items(type="string")),
 * )
 */
class DietPlan extends Model
{
    use HasFactory;
    protected $table = 'diet_plans';

    protected $fillable = [
        'id','client_id', 'days', 'breakfast', 'lunch', 'dinner','image','meal_4','meal_5'
    ];

}
