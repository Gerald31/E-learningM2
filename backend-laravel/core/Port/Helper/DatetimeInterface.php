<?php

namespace Core\Port\Helper;

interface DatetimeInterface
{
    /**
     * Get a Datetime instance for the current date and time.
     *
     * @param \DateTimeZone|string|null $timeZone
     * @return \DateTime
     */
    public function now($timeZone = null): \DateTime;

    /**
     * Parse the given datetime
     *
     * @param \DateTimeInterface|string|null $datetime
     * @return \DateTime
     */
    public function parse($datetime): \DateTime;

    /**
     * Extract the year from the given datetime
     *
     * @param \DateTimeInterface|string|null $datetime
     * @return int
     */
    public function getParsedYear($datetime): int;
}
