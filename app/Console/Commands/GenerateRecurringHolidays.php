<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PublicHoliday;
use Carbon\Carbon;

class GenerateRecurringHolidays extends Command
{
    protected $signature = 'holidays:generate-recurring {year?}';
    protected $description = 'Generate recurring holidays for the specified year';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $year = $this->argument('year') ?? Carbon::now()->addYear()->year;

        $this->info("Generating recurring holidays for year {$year}...");

        PublicHoliday::createRecurringHolidays($year);

        $count = PublicHoliday::where('year', $year)->where('is_recurring', true)->count();

        $this->info("Generated {$count} recurring holidays for {$year}.");

        return Command::SUCCESS;

    }
}
