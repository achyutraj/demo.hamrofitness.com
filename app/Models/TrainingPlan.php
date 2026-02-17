<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="TrainingPlan",
 *     title="TrainingPlan",
 *     description="TrainingPlan Model",
 *     @OA\Property(property="id", type="integer", example=8),
 *     @OA\Property(property="days", type="array", @OA\Items(type="string"), example={"sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"}),
 *     @OA\Property(property="level", type="string", example="Level One"),
 *     @OA\Property(property="activity", type="array", @OA\Items(type="string"), example="string"),
 *     @OA\Property(property="sets", type="array", @OA\Items(type="string"), example="string"),
 *     @OA\Property(property="repetition", type="array", @OA\Items(type="string"), example="string"),
 *     @OA\Property(property="weights", type="array", @OA\Items(type="string"), example="string "),
 *     @OA\Property(property="restTime", type="array", @OA\Items(type="string"), example=" string"),
 *     @OA\Property(property="startDate", type="string", example="null"),
 *     @OA\Property(property="endDate", type="string", example="null"),
 * )
 */
class TrainingPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'training_plans';

    protected $fillable = [
        'id','client_id','days','level','activity','sets','repetition','weights','restTime','startDate','endDate'
    ];

    public function gymClients(){
        return $this->belongsToMany(GymClient::class,'gym_clients','client_id');
    }
}
