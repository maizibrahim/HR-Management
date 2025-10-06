<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PublicHoliday;
use Carbon\Carbon;
use Illuminate\Validation\Rule;


class PublicHolidayController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        $holidays = PublicHoliday::getHolidaysForYear($year);

        // Get available years for the dropdown
        $availableYears = PublicHoliday::selectRaw('DISTINCT year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Add current and next year if not exists
        if (!$availableYears->contains($year)) {
            $availableYears->push($year);
        }
        if (!$availableYears->contains($year + 1)) {
            $availableYears->push($year + 1);
        }

        $availableYears = $availableYears->sort()->values();

        return view('settings.public-holidays.index', compact('holidays', 'year', 'availableYears'));
    }

    public function create()
    {
        return view('settings.public-holidays.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date|unique:public_holidays,date',
            'description' => 'nullable|string',
            'is_recurring' => 'boolean',
        ]);

        $validated['year'] = Carbon::parse($validated['date'])->year;

        PublicHoliday::create($validated);

        return redirect()->route('settings.public-holidays.index')
            ->with('success', 'Public holiday created successfully.');
    }

    public function edit(PublicHoliday $publicHoliday)
    {
        return view('settings.public-holidays.edit', compact('publicHoliday'));
    }

    public function update(Request $request, PublicHoliday $publicHoliday)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => [
                'required',
                'date',
                Rule::unique('public_holidays')->ignore($publicHoliday)
            ],
            'description' => 'nullable|string',
            'is_recurring' => 'boolean',
        ]);

        $validated['year'] = Carbon::parse($validated['date'])->year;

        $publicHoliday->update($validated);

        return redirect()->route('settings.public-holidays.index')
            ->with('success', 'Public holiday updated successfully.');
    }

    public function destroy(PublicHoliday $publicHoliday)
    {
        $publicHoliday->delete();

        return redirect()->route('settings.public-holidays.index')
            ->with('success', 'Public holiday deleted successfully.');
    }


      public function deletepublicholiday($id)
        {
            try {
                PublicHoliday::findOrFail($id)->delete();
                return redirect()->back()
                    ->with('success', 'Public Holiday deleted successfully!');
            } catch (\Exception $e) {
                return redirect()->back()
                    ->with('error', 'Failed to delete public holiday. It may be in use.');
            }
        }

    public function bulkImport(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:2020|max:2050',
            'holidays' => 'required|array|min:1',
            'holidays.*.name' => 'required|string|max:255',
            'holidays.*.date' => 'required|date',
            'holidays.*.description' => 'nullable|string',
            'holidays.*.is_recurring' => 'boolean',
        ]);

        $imported = 0;
        $skipped = 0;

        foreach ($validated['holidays'] as $holidayData) {
            $holidayData['year'] = Carbon::parse($holidayData['date'])->year;

            $existing = PublicHoliday::where('date', $holidayData['date'])->exists();

            if (!$existing) {
                PublicHoliday::create($holidayData);
                $imported++;
            } else {
                $skipped++;
            }
        }

        return redirect()->route('settings.public-holidays.index')
            ->with('success', "Imported {$imported} holidays. Skipped {$skipped} existing holidays.");
    }

    public function generateRecurring(Request $request)
    {
        $validated = $request->validate([
            'target_year' => 'required|integer|min:2020|max:2050',
        ]);

        PublicHoliday::createRecurringHolidays($validated['target_year']);

        return redirect()->route('settings.public-holidays.index', ['year' => $validated['target_year']])
            ->with('success', 'Recurring holidays generated for ' . $validated['target_year']);
    }
}
