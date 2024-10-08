<?php

declare(strict_types=1);

namespace App\Models;

use App\StoreType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    /** @use HasFactory<\Database\Factories\StoreFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'open',
        'type',
        'max_delivery_distance',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'open' => 'boolean',
        'type' => StoreType::class,
    ];

    /**
     * Find stores that are within the given distance of the latitude and longitude supplied
     */
    public function scopeCloseTo(Builder $query, $latitude, $longitude, $distance)
    {
        return $query->whereRaw(<<<sql
        ST_Distance_Sphere(
            point(longitude, latitude),
            point(?, ?)
        ) * .000621371192 < ?
        sql, [
            $longitude,
            $latitude,
            $distance,
        ]);
    }

    /**
     * Find stores that are willing to deliver to the latitude and longitude supplied
     */
    public function scopeWillDeliverTo(Builder $query, $latitude, $longitude)
    {
        return $query->whereRaw(<<<sql
        ST_Distance_Sphere(
            point(longitude, latitude),
            point(?, ?)
        ) * .000621371192 < max_delivery_distance
        sql, [
            $longitude,
            $latitude,
        ]);
    }
}
