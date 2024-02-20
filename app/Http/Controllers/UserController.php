<?php

namespace App\Http\Controllers;

use App\Repositories\TicketRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     *  Returns a paginated list of all tickets that belong to the user with the corresponding email address.  
     */
    public function tickets(string $email) {
        $ticketRepo = new TicketRepository();

        $tickets = [
            'tickets' => $ticketRepo->getTicketsByUserEmail($email)
        ];

        return response()->json($tickets);
    }
}
