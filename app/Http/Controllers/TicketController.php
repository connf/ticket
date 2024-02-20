<?php

namespace App\Http\Controllers;

use App\Repositories\TicketRepository;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    private $ticketRepo;

    public function __construct()
    {
        $this->ticketRepo = new TicketRepository();
    }

    /**
     * Returns a paginated list of all unprocessed tickets (i.e. all tickets with status set to false) 
     */
    public function open() {
        $tickets = [
            'tickets' => $this->ticketRepo->getOpenTickets()
        ];

        return response()->json($tickets);
    }

    /**
     * Returns a paginated list of all processed tickets (i.e. all tickets with status set to true) 
     */
    public function closed() {
        $tickets = [
            'tickets' => $this->ticketRepo->getClosedTickets()
        ];

        return response()->json($tickets);
    }

    public function view(int $id) {
        $ticket = [
            'ticket' => $this->ticketRepo->find($id)
        ];

        return response()->json($ticket);
    }
}
