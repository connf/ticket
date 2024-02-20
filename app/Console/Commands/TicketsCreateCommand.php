<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TicketsCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new ticket every minute';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (\App\Models\Ticket::factory()->create()) {
            return false;
        }
        return true;
    }
}
