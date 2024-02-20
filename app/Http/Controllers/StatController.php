<?php

namespace App\Http\Controllers;

use App\Repositories\TicketRepository;
use Illuminate\Http\Request;

class StatController extends Controller
{
    public function view() {
        $ticketRepo = new TicketRepository();

        $total = $ticketRepo->countTotalTickets();
        $unprocessed = $ticketRepo->countOpenTickets();
        $user = $ticketRepo->getUserWithMostTickets();
        $userWithMost = $user->first_name . " " . $user->last_name;
        $lastProcessed = $ticketRepo->getLastProcessedTicketTime(false);

        $stats = [
            'total_tickets' => $total,
            'unprocessed_tickets' => $unprocessed,
            'user_with_most_tickets' => $userWithMost,
            'last_processed' => $lastProcessed
        ];

        return response()->json($stats);
    }
}
