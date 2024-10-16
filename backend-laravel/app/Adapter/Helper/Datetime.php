<?php

namespace App\Adapter\Helper;

use Carbon\Carbon;
use Core\Port\Helper\DatetimeInterface;

class Datetime implements DatetimeInterface
{
    /**
     * {@inheritDoc}
     */
    public function now($timeZone = null): \DateTime
    {
        return Carbon::now($timeZone);
    }

    /**
     * {@inheritDoc}
     */
    public function parse($datetime): \DateTime
    {
        return Carbon::parse($datetime);
    }

    /**
     * {@inheritDoc}
     */
    public function getParsedYear($datetime): int
    {
        return Carbon::parse($datetime)->year;
    }
}
