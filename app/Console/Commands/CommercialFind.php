<?php

namespace App\Console\Commands;

use App\FindClasses\CommercialTenderClass;
use Illuminate\Console\Command;

class CommercialFind extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenders:commercial';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find commercial tenders with keywords in name/lots';

    private $commercialTenderFind;

    /**
     * Create a new command instance.
     *
     * @param CommercialTenderClass $commercialTenderFind
     */
    public function __construct(CommercialTenderClass $commercialTenderFind)
    {
        parent::__construct();
        $this->commercialTenderFind = $commercialTenderFind;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->commercialTenderFind->findTenders();
    }
}
