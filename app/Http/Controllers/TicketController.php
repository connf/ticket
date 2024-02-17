<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function open() {
        /**
         * Returns a paginated list of all unprocessed tickets (i.e. all tickets with status set to false) 
         */
    }
    
    public function closed() {
        /**
         * Returns a paginated list of all processed tickets (i.e. all tickets with status set to true) 
         */
    }
}
