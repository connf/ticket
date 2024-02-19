<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Ticket::factory()->complete()->count(5)->create(); // Complete first as processing is done in order
        \App\Models\Ticket::factory()->count(10)->create();
    }
}
