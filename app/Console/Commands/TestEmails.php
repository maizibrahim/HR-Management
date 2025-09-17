<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use App\Notifications\CustomVerifyEmail;

class TestEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::first();

        if ($user) {
            $user->notify(new CustomVerifyEmail());
            $this->info('Test email sent to: ' . $user->email);
        } else {
            $this->error('No users found in database');
        }
    }
}
