<?php

namespace App\Repositories;

use App\Models\Ticket;
use Carbon\Carbon;

class TicketRepository
{
    public function create(array $data)
    {
        return Ticket::create($data);
    }

    public function find(int $id): ?Ticket
    {
        $ticket = Ticket::find($id);

        return $ticket;
    }

    public function findBy(string $search, string $by = "subject")
    {
        return Ticket::where($by, $search)->get();
    }

    public function countTotalTickets()
    {
        return Ticket::all()->count();
    }

    public function countOpenTickets()
    {
        return Ticket::where('status', false)->count();
    }
}
