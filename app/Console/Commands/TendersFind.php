<?php

namespace App\Console\Commands;

use App\FindClasses\CommercialTenderClass;
use App\FindClasses\GovernmentTenderClass;
use App\Models\Search;
use Illuminate\Console\Command;

class TendersFind extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenders:find';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'deleting passed tenders and find new';

    private $commercialTenderClass, $governmentTenderClass;

    /**
     * Create a new command instance.
     *
     * @param CommercialTenderClass $commercialTenderClass
     * @param GovernmentTenderClass $governmentTenderClass
     */
    public function __construct(CommercialTenderClass $commercialTenderClass, GovernmentTenderClass $governmentTenderClass)
    {
        parent::__construct();
        $this->commercialTenderClass = $commercialTenderClass;
        $this->governmentTenderClass = $governmentTenderClass;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->commercialTenderClass->deletePassedDates();
        $this->governmentTenderClass->deletePassedDates();

        Search::doesnthave('tenders')->delete();
        $search = Search::create(['ended' => false]);
        $search->save();

        $this->commercialTenderClass->findTenders($search);
        $this->governmentTenderClass->findTenders($search);

        $search->ended = true;
        $search->save();

    }
}
