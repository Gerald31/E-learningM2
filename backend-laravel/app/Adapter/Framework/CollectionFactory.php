<?php

namespace App\Adapter\Framework;

use Core\Port\Framework\CollectionInterface;

class CollectionFactory implements \Core\Port\Framework\CollectionFactoryInterface
{
    /**
     * @param $value
     * @return CollectionInterface
     */
    public function create($value = []): CollectionInterface
    {
        return new Collection($value);
    }
}
