<?php

namespace App\Models;

use App\Models\Model as CarModel;
use Illuminate\Database\Eloquent\Builder;
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

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];


    public function model()
    {
        return $this->belongsTo(CarModel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeBrand(Builder $query,$value)
    {
        $query->where('brand', 'LIKE', "%{$value}%");
    }

    public function scopeYear(Builder $query,$value)
    {
        $query->where('year',$value);
    }

    public function scopeVin(Builder $query,$value)
    {
        $query->where('vin', 'LIKE', "%{$value}%");
    }

    public function scopeDescription(Builder $query,$value)
    {
        $query->where('description', 'LIKE', "%{$value}%");
    }
}
