<?php

namespace Selfreliance\feedback\Console;

use Illuminate\Console\Command;

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
        $messages = \EmailParser::getInbox();
        \EmailParser::parseMessages($messages);
        unset($messages);
        $this->info('Parse email succesfuly!');
    }
}
