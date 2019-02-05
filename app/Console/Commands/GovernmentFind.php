<?php

namespace App\Console\Commands;

use App\FindClasses\GovernmentTenderClass;
use Illuminate\Console\Command;

class GovernmentFind extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenders:government';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find government tenders with keywords in name/lots';

    private $governmentTenderFind;

    /**
     * Create a new command instance.
     *
     * @param GovernmentTenderClass $governmentTenderFind
     */
    public function __construct(GovernmentTenderClass $governmentTenderFind)
    {
        parent::__construct();
        $this->governmentTenderFind = $governmentTenderFind;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->governmentTenderFind->findTenders();
    }
}
