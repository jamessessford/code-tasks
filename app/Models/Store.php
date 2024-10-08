<?php

declare(strict_types=1);

namespace App\Models;

use App\StoreType;
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
}
