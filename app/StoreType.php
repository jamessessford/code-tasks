<?php

declare(strict_types=1);

namespace App;

enum StoreType: int
{
    case Takeaway = 1;
    case Shop = 2;
    case Restaurant = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::Takeaway => 'Takeaway',
            self::Shop => 'Shop',
            self::Restaurant => 'Restaurant',
        };
    }
}
