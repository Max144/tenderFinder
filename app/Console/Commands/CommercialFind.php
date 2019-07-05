<?php

namespace App\Console\Commands;

use App\FindClasses\CommercialTenderClass;
use App\Models\Search;
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

    private $commercialTenderClass;

    /**
     * Create a new command instance.
     *
     * @param CommercialTenderClass $commercialTenderClass
     */
    public function __construct(CommercialTenderClass $commercialTenderClass)
    {
        parent::__construct();
        $this->commercialTenderClass = $commercialTenderClass;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $search = Search::create([
            'ended' => false
        ]);

        $this->commercialTenderClass->findTenders($search);

        $search->ended = true;
        $search->save();
    }
}
