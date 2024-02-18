<?php

namespace Tests\Unit;

use App\Models\User;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testCanAddNewUser(): void
    {
        // Load the Repo we are testing
        $userRepo = new UserRepository();

        /**
         * Create some demo data
         * We will use this later to check it saved correctly
         */
        $userToSave = [
            "first_name" => "First",
            "last_name" => "Last",
            "email" => "first.last@email.com",
            "password" => bcrypt('password')
        ];

        /** Let's create the record using the repo as we're testing the repo not the model */
        $user = $userRepo->create($userToSave);

        /**
         * Then assert that each of the fields we add is the same or equal to 
         * how they should be when pulled back from the DB / returned by the
         * create method
         */
        $this->assertSame($userToSave["first_name"], $user->first_name);
        $this->assertSame($userToSave["last_name"], $user->last_name);
        $this->assertSame($userToSave["email"], $user->email);
    }

    public function testCanFindUser(): void
    {
        $repo = new UserRepository();
        $userToSave = [
            "first_name" => "Testing",
            "last_name" => "ThisUser",
            "email" => "email@email.com",
            "password" => bcrypt('password')
        ];
        $userToTest = $repo->create($userToSave);
        $user = $repo->find($userToTest->id);

        $this->assertSame($userToSave["first_name"], $user->first_name);
        $this->assertSame($userToSave["last_name"], $user->last_name);
    }

    public function testCanFindUserByEmail(): void
    {
        $repo = new UserRepository();
        $userToSave = [
            "first_name" => "TestingFindByEmail",
            "last_name" => "ThisUser",
            "email" => "email@email.com",
            "password" => bcrypt('password')
        ];
        $userToTest = $repo->create($userToSave);

        // We could use the User instance created by $userToTest here 
        // but this is more indicative or real world scenarion where the searched
        // variable to find by would come from somewhere else first such as this array text
        $users = $repo->findBy($userToSave["email"]); 

        $this->assertSame($userToSave["first_name"], $users->first()->first_name);
        $this->assertSame($userToSave["last_name"], $users->first()->last_name);
        $this->assertSame($userToSave["email"], $users->first()->email);
    }

    public function testCanFindUserByAnotherField(): void
    {
        $repo = new UserRepository();
        $userToSave = [
            // Usually in a production environment we would use a proper testing suite which rebuilds each run
            // Or we can randomly add digits to ensure we get different records each test run
            "first_name" => "testingbyfirstname",
            "last_name" => "testingbylastname",
            "email" => "email@email.com",
            "password" => bcrypt('password')
        ];
        $userToTest = $repo->create($userToSave);

        $field = "last_name";
        $users = $repo->findBy($userToSave["last_name"], $field); 

        $this->assertSame($userToSave["first_name"], $users->first()->first_name);
        $this->assertSame($userToSave["last_name"], $users->first()->last_name);
        $this->assertSame($userToSave["email"], $users->first()->email);

        $field = "first_name";
        $users = $repo->findBy($userToSave["first_name"], $field); 

        $this->assertSame($userToSave["first_name"], $users->first()->first_name);
        $this->assertSame($userToSave["last_name"], $users->first()->last_name);
        $this->assertSame($userToSave["email"], $users->first()->email);
    }
}
