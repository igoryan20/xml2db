<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Xml2DbCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xml2db:send {path=./files/data.xml}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Xml file data to db';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        echo $this->argument('path');
        return 0;
    }
}
