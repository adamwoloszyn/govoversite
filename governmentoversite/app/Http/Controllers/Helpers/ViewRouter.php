<?php

namespace App\Http\Controllers\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ViewRouter
{
    protected array $routingTable = array();

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // home
        $this->routingTable['index']        = array( 'guest' => "home.homeGuest",       "subscriber" => "home.homeSubscriber",              "admin" => "home.homeAdmin" );
        // welcome
        $this->routingTable['welcome']      = array( 'guest' => "home.homeGuest",       "subscriber" => "home.homeSubscriber",              "admin" => "home.homeAdmin" );

        // userSettings
        $this->routingTable['userSettings'] = array( 'guest' => "home.homeGuest",       "subscriber" => "usersettings.indexSubscriber",     "admin" => "usersettings.indexAdmin" );
    }   // end of constructor

    /**
     * Determine what view to display
     *
     * @return string 
     */
    public function determinePageView($pageDesired)
    {
        $currentRole = $this->WhatIsCurrentRole();

        //dd( [$pageDesired, $currentRole, $this->routingTable, $this->routingTable[$pageDesired][$currentRole]] );
        if (env('LOGGING_DISPLAY_DYNAMIC_ROUTES'))
        {
            Log::info("Page Desired:[" . $pageDesired . "] Role: [" . $currentRole . ']');
        }

        return $this->routingTable[$pageDesired][$currentRole];
    }   // end of determinePageView()

    public function WhatIsCurrentRole()
    {
        return $currentRole = (Auth::guest()) ? "guest" : auth()->user()->role;
    }   // end of WhatIsCurrentRole()

    public function IsASubscriber()
    {
        return $this->WhatIsCurrentRole() == "subscriber";
    }   // end of IsASubscriber()
}   // end of ViewRouter class
