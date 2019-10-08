<?php

namespace App\Listeners;

use App\Constant\UserActionHistory;
use App\Events\LoginEvent;
use App\Interfaces\Auth\AuthInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogUserActionListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    private $authService;

    public function __construct(AuthInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle the event.
     *
     * @param  LoginEvent  $event
     * @return void
     */
    public function handle(LoginEvent $event)
    {
        $this->authService->LogActionHistory($event->getUser(), UserActionHistory::ACTION_TYPE_LOGIN);
    }
}
