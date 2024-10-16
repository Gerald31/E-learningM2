<?php

namespace Core\Port\Framework;

interface CollectionFactoryInterface
{
    /**
     * @param $value
     * @return CollectionInterface
     */
    public function create($value = []) : CollectionInterface;
}