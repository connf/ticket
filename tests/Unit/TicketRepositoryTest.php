<?php

namespace Tests\Unit;

use App\Models\Ticket;
use App\Repositories\TicketRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TicketRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testCanAddNewTicket(): void
    {
        // Load the Repo we are testing
        $ticketRepo = new TicketRepository();
        // And the user repo as we need to have a user
        $userRepo = new UserRepository();

        /**
         * Create some demo data
         * We will use this later to check it saved correctly
         */
        $userToSave = [
            "first_name" => "Testing",
            "last_name" => "ThisUser",
            "email" => "email@email.com",
            "password" => bcrypt('password')
        ];
        $user = $userRepo->create($userToSave);

        $ticketToSave = [
            "subject" => "Subject Line",
            "content" => "The content of the ticket goes here",
            "user_id" => $user->id,
        ];

        /** Let's create the record using the repo as we're testing the repo not the model */
        $ticket = $ticketRepo->create($ticketToSave);

        /**
         * Then assert that each of the fields we add is the same or equal to 
         * how they should be when pulled back from the DB / returned by the
         * create method
         */
        $this->assertSame($ticketToSave["subject"], $ticket->subject);
        $this->assertSame($ticketToSave["content"], $ticket->content);
        $this->assertSame($ticketToSave["user_id"], $ticket->user_id);
    }

    public function testCanFindTicket(): void
    {
        $ticketRepo = new TicketRepository();
        $userRepo = new UserRepository();

        $userToSave = [
            "first_name" => "Testing",
            "last_name" => "Find",
            "email" => "email@email.com",
            "password" => bcrypt('password')
        ];
        $user = $userRepo->create($userToSave);

        $ticketToSave = [
            "subject" => "Subject Line For Find Test",
            "content" => "The content of the ticket for the find test goes here",
            "user_id" => $user->id,
        ];
        $ticketToTest = $ticketRepo->create($ticketToSave);
        $ticket = $ticketRepo->find($ticketToTest->id);

        $this->assertSame($ticketToSave["subject"], $ticket->subject);
        $this->assertSame($ticketToSave["content"], $ticket->content);
    }

    public function testCanFindTicketBySubject(): void
    {
        $ticketRepo = new TicketRepository();
        $userRepo = new UserRepository();

        $userToSave = [
            "first_name" => "Testing",
            "last_name" => "FindBySubject",
            "email" => "email@email.com",
            "password" => bcrypt('password')
        ];
        $user = $userRepo->create($userToSave);

        $ticketToSave = [
            "subject" => "Subject Line For FindBy subject Test",
            "content" => "The content of the ticket for the findBy subject test goes here",
            "user_id" => $user->id,
        ];
        $ticketToTest = $ticketRepo->create($ticketToSave);

        // We could use the Ticket instance created by $ticketToTest here 
        // but this is more indicative or real world scenarion where the searched
        // variable to find by would come from somewhere else first such as this array text
        $tickets = $ticketRepo->findBy($ticketToSave["subject"]); 

        $this->assertSame($ticketToSave["subject"], $tickets->first()->subject);
        $this->assertSame($ticketToSave["content"], $tickets->first()->content);
    }

    public function testCanFindTicketByAnotherField(): void
    {
        $ticketRepo = new TicketRepository();
        $userRepo = new UserRepository();

        $userToSave = [
            "first_name" => "Testing",
            "last_name" => "FindByContent",
            "email" => "email@email.com",
            "password" => bcrypt('password')
        ];
        $user = $userRepo->create($userToSave);

        $ticketToSave = [
            "subject" => "Subject Line For FindBy content Test",
            "content" => "The content of the ticket for the findBy content test goes here",
            "user_id" => $user->id,
            "status" => true
        ];
        $ticketToTest = $ticketRepo->create($ticketToSave);

        $field = "subject";
        $tickets = $ticketRepo->findBy($ticketToSave["subject"], $field);

        $this->assertSame($ticketToSave["subject"], $tickets->first()->subject);
        $this->assertSame($ticketToSave["content"], $tickets->first()->content);

        $field = "status";
        $tickets = $ticketRepo->findBy($ticketToSave["status"], $field);

        $this->assertSame($ticketToSave["subject"], $tickets->first()->subject);
        $this->assertSame($ticketToSave["content"], $tickets->first()->content);
    }

    public function testCanCountTotalTickets() {
        $this->seed();
        // By default this seeds 5 users, 10 open tickets and 5 closed

        $ticketRepo = new TicketRepository();
        $count = $ticketRepo->countTotalTickets();

        $this->assertEquals($count, 15);
    }

    public function testCanCountOpenTickets() {
        $this->seed();
        // By default this seeds 5 users, 10 open tickets and 5 closed

        $ticketRepo = new TicketRepository();
        $count = $ticketRepo->countOpenTickets();

        $this->assertEquals($count, 10);
    }
}
