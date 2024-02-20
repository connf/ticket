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

    public function find(int $id)
    {
        $ticket = Ticket::find($id)
            ->with('user')
            ->first();

        return $ticket;
    }

    public function findBy(string $search, string $by = "subject")
    {
        return Ticket::where($by, $search)->with('user')->get();
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
            ->with('user')
            ->paginate($perPage);
    }

    public function getClosedTickets($perPage = 10)
    {
        return Ticket::where('status', true)
            ->with('user')
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

    public function getLastProcessedTicketTime($humanReadable = false) {
        $ticket = Ticket::where('status', true)
            ->orderByDesc('updated_at')
            ->limit(1)
            ->first();

        $dateTime = $ticket->updated_at;

        if ($humanReadable) $dateTime = $dateTime->format('l jS \o\f F Y H:i');

        return $dateTime;
    }

    public function getNextUnprocessedTicket()
    {
        return Ticket::where('status', false)
            ->orderBy('id')
            ->limit(1)
            ->first();
    }
}
