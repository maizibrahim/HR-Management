<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class PublicHoliday extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date',
        'description',
        'year',
        'is_recurring'
    ];

    protected $casts = [
        'date' => 'date',
        'is_recurring' => 'boolean',
    ];

    /**
     * Get all public holidays for a specific year
     */
    public static function getHolidaysForYear($year)
    {
        return self::where('year', $year)
            ->orderBy('date')
            ->get();
    }

    /**
     * Get public holidays between two dates
     */
    public static function getHolidaysBetweenDates($startDate, $endDate)
    {
        return self::whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get();
    }

    /**
     * Check if a specific date is a public holiday
     */
    public static function isPublicHoliday($date)
    {
        return self::whereDate('date', $date)->exists();
    }

    /**
     * Create recurring holidays for next year
     */
    public static function createRecurringHolidays($targetYear)
    {
        $recurringHolidays = self::where('is_recurring', true)
            ->where('year', $targetYear - 1)
            ->get();

        foreach ($recurringHolidays as $holiday) {
            $newDate = Carbon::parse($holiday->date)->addYear();

            self::updateOrCreate(
                ['date' => $newDate->format('Y-m-d')],
                [
                    'name' => $holiday->name,
                    'description' => $holiday->description,
                    'year' => $targetYear,
                    'is_recurring' => true
                ]
            );
        }
    }
}
