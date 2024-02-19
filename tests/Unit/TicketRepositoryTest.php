<?php

namespace Tests\Unit;

use App\Models\User;
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

    public function testCanGetOpenTickets() {
        $this->seed();
        // By default this seeds 5 users, 10 open tickets and 5 closed

        $ticketRepo = new TicketRepository();
        $tickets = $ticketRepo->getOpenTickets();

        $this->assertEquals($tickets->count(), 10);
    }

    public function testCanGetClosedTickets() {
        $this->seed();
        // By default this seeds 5 users, 10 open tickets and 5 closed

        $ticketRepo = new TicketRepository();
        $tickets = $ticketRepo->getClosedTickets();

        $this->assertEquals($tickets->count(), 5);
    }

    public function testCanGetTicketsByUserEmail() {
        // Add some redundant users and ticket - we want to make sure it's not these that come back
        $this->seed(); // By default this seeds 5 users, 10 open tickets and 5 closed - we want user 6

        // Hardcode an email
        $email = "this.is.the.testing.email@testing.com";

        // Add this user and 2 tickets
        User::factory()->create(['id' => 6, 'email' => $email]);
        Ticket::factory()->count(2)->create(['user_id' => 6]);

        // And a completed ticket as we want to return all tickets
        Ticket::factory()->complete()->create(['user_id' => 6]);

        // Pull tickets for this users email
        $ticketRepo = new TicketRepository();
        $tickets = $ticketRepo->getTicketsByUserEmail($email);

        $this->assertEquals($tickets->count(), 3);
    }

    public function testCanGetUserWithMostTickets() {
        $ticketRepo = new TicketRepository();

        // Create 3 users
        User::factory()->count(3)->create();

        // Create 2 tickets not for this user and 3 for it - we should match the 3
        Ticket::factory()->create(['user_id' => 1]);
        Ticket::factory()->create(['user_id' => 2]);
        Ticket::factory()->count(3)->create(['user_id' => 3]);

        // // Get the user we expect
        $user = User::find(3);

        $userToCheck = $ticketRepo->getUserWithMostTickets();
        $this->assertSame($user->id, $userToCheck->id);
    }

    public function testCanGetLastProcessedTicketTime() {
        // Hardcode some dates
        $date1 = "2024-01-04 12:00:00";
        $date2 = "2024-02-10 14:10:30"; // we should match this value

        // Create a user for the tickets
        User::factory()->create();

        // Add the tickets with the hardcoded dates - we should match ticket2's date when updated to complete
        $ticket1 = Ticket::factory()->complete()->create(['updated_at' => $date1]);
        $ticket2 = Ticket::factory()->complete()->create(['updated_at' => $date2]);

        $ticketRepo = new TicketRepository();
        $dateToCheck = $ticketRepo->getLastProcessedTicketTime();

        $this->assertEquals(Carbon::create($date2)->format('l jS \o\f F Y H:i'), $dateToCheck);
    }
}
