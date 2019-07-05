<?php

namespace App\Console\Commands;

use App\FindClasses\GovernmentTenderClass;
use App\Models\Search;
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

    private $governmentTenderClass;

    /**
     * Create a new command instance.
     *
     * @param GovernmentTenderClass $governmentTenderClass
     */
    public function __construct(GovernmentTenderClass $governmentTenderClass)
    {
        parent::__construct();
        $this->governmentTenderClass = $governmentTenderClass;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $search = new Search([
            'ended' => false
        ]);

        $this->governmentTenderClass->findTenders($search);

        $search->ended = true;
        $search->save();
    }
}
