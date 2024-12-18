<?php

namespace App\Helpers;

use Carbon\Carbon;
use DateTimeInterface;

class DateHelper
{
    /**
     * Get an array of dates between two dates.
     *
     * @param string|DateTimeInterface $startDate
     * @param string|DateTimeInterface $endDate
     * @return array<string>
     */
    public static function getDatesFromRange($startDate, $endDate): array
    {
        $dates = [];
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }
} 