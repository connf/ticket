<?php

namespace App\Console\Commands;

use App\Repositories\TicketRepository;
use Illuminate\Console\Command;

class TicketsProcessCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process pending tickets in order every 5 minutes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ticketRepo = new TicketRepository();
        $ticket = $ticketRepo->getNextUnprocessedTicket();

        $ticket->status = true;

        if($ticket->update()) {
            return false;
        }
        return true;
    }
}
