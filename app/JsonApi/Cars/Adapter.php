<?php

namespace App\JsonApi\Cars;

use CloudCreativity\LaravelJsonApi\Eloquent\AbstractAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class Adapter extends AbstractAdapter
{
    protected $guarded=['id'];

    protected $includePaths =[
        'authors' =>'user',
        'models' => 'model'
    ];

    /**
     * Mapping of JSON API attribute field names to model keys.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Mapping of JSON API filter names to model scopes.
     *
     * @var array
     */
    protected $filterScopes = [];

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new \App\Models\Car(), $paging);
    }

    /**
     * @param Builder $query
     * @param Collection $filters
     * @return void
     */
    protected function filter($query, Collection $filters)
    {
        $this->filterWithScopes($query, $filters);
    }

    protected function fillAttributes($car, Collection $attributes)
    {
        $car->fill($attributes->toArray());
        $car->user_id = auth()->id();
    }

    public function users()
    {
        return $this->belongsTo('user');
    }

    public function models()
    {
        return $this->belongsTo('model');
    }

}
