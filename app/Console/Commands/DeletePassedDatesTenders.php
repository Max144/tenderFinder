<?php

namespace App\Console\Commands;

use App\FindClasses\CommercialTenderClass;
use App\FindClasses\GovernmentTenderClass;
use Illuminate\Console\Command;

class DeletePassedDatesTenders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenders:delete-passed-dates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete tenders with passed end dates';

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
    }
}
