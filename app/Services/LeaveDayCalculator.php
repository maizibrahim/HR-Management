<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\leave\LeaveType;

class LeaveDayCalculator
{
    /**
     * Calculate the number of leave days based on leave type configuration
     */
    public static function calculateDays(Carbon $startDate, Carbon $endDate, LeaveType $leaveType): int
    {
        if ($leaveType->count_type === LeaveType::COUNT_TYPE_ALL_DAYS) {
            return self::calculateAllDays($startDate, $endDate);
        }

        return self::calculateWeekdaysOnly($startDate, $endDate);
    }

    /**
     * Calculate all days including weekends
     */
    private static function calculateAllDays(Carbon $startDate, Carbon $endDate): int
    {
        return $startDate->diffInDays($endDate) + 1;
    }

    /**
     * Calculate only weekdays (Monday to Friday)
     */
    private static function calculateWeekdaysOnly(Carbon $startDate, Carbon $endDate): int
    {
        $daysCount = 0;
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            if ($currentDate->isWeekday()) {
                $daysCount++;
            }
            $currentDate->addDay();
        }

        return $daysCount;
    }
}
