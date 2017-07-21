<?php

namespace App\Listeners;

use App\Events\EvenTest;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ListenerTest
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  EvenTest  $event
     * @return void
     */
    public function handle(EvenTest $event)
    {
        //
    }
}
