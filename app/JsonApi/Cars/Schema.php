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
     * @param Car $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'brand'=>$resource->brand,
            'slug' => $resource->slug,
            'year' => $resource->year->toAtomString(),
            'description' => $resource->description,
            'createdAt' => $resource->created_at->toAtomString(),
            'updatedAt' => $resource->updated_at->toAtomString(),
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
