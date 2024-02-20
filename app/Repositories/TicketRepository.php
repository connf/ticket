<?php

namespace App\Repositories;

use App\Models\User;
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

    public function getOpenTickets($perPage = 10)
    {
        return Ticket::where('status', false)
            ->paginate($perPage);
    }

    public function getClosedTickets($perPage = 10)
    {
        return Ticket::where('status', true)
            ->paginate($perPage);
    }

    public function getTicketsByUserEmail($email, $perPage = 10)
    {
        return User::where('email', $email)
            ->first()
            ->tickets()
            ->paginate($perPage);
    }

    public function getUserWithMostTickets() {
        $ticket = Ticket::select('user_id')
            ->groupBy('user_id')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(1)
            ->first();

        return User::find($ticket->user_id);
    }

    public function getLastProcessedTicketTime() {
        $ticket = Ticket::where('status', true)
            ->orderByDesc('updated_at')
            ->limit(1)
            ->first();

        return $ticket->updated_at->format('l jS \o\f F Y H:i');
    }

    public function getNextUnprocessedTicket()
    {
        return Ticket::where('status', false)
            ->orderBy('id')
            ->limit(1)
            ->first();
    }
}
