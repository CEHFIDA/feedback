<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Selfreliance\Feedback\FeedbackController;

class EmailParser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse email messages from gmail';

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
     * @return mixed
     */
    public function handle()
    {
        $parse = FeedbackController::parse_email();
        if($parse) $this->info('Parse email successfuly.');
        else $this->error('Parse email error.');
    }
}
