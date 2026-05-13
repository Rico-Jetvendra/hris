<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Web\NotificationController;

class Notification extends Command{
    protected $signature = 'command:notification';
    protected $description = 'Command description';

    public function __construct(){
        parent::__construct();
    }

    public function handle(){
        $controller = new NotificationController();
        $controller->sendDueEmail();

        $this->info('Notification sent successfully!');
    }
}
