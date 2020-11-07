<?php

namespace App\Models;

use App\Models\Model as CarModels;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'model_id' => 'integer',
        'user_id' => 'string',
    ];


    public function model()
    {
        return $this->belongsTo(CarModels::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
