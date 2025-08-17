<?php

namespace Modules\Order\Console;

use Illuminate\Console\Command;
use Modules\Order\Entities\Order;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CancelUnpaidOrderAfterOneHour extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'order:cancelUnpaidOrderAfterOneHour';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
         Order::cancelUnpaidOrderAfterOneHour();
    }

}
