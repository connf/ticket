<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatController extends Controller
{
    public function view() {
        /**
         * /stats - Returns the following stats: 
         *  total number of tickets in the database; 
         *  total number of unprocessed tickets in the database; 
         *  name of the user who submitted the highest number of tickets (count by email); and 
         *  time when the last processing of a ticket was done. 
         */
    }
}
