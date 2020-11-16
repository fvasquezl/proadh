<?php

namespace App\JsonApi\Cars;

use App\Models\Car;
use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'cars';

    /**
     * @param Car $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string) $resource->getRouteKey();
    }

    /**
     * @param Car $car
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($car)
    {
        return [
            'brand'=>$car->brand,
            'slug' => $car->slug,
            'year' => $car->year,
            'vin' => $car->vin,
            'description' => $car->description,
            'createdAt' => $car->created_at->toAtomString(),
            'updatedAt' => $car->updated_at->toAtomString(),
        ];
    }

    public function getRelationships($car, $isPrimary, array $includeRelationships)
    {

        return [
            'authors' => [
                self::SHOW_RELATED => true,
                self::SHOW_SELF => true,
                self::SHOW_DATA => isset($includeRelationships['authors']),
                self::DATA => function() use ($car) {
                    return $car->user;
                }
            ],
            'models' => [
                self::SHOW_RELATED => true,
                self::SHOW_SELF => true,
                self::SHOW_DATA => isset($includeRelationships['models']),
                self::DATA => function() use ($car) {
                    return $car->model;
                }
            ],
        ];
    }
}
