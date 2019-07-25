<?php

namespace App\FindClasses;

use App\Models\Search;
use App\Models\Tender;
use Symfony\Component\DomCrawler\Crawler;

class CommercialTenderClass extends TenderClass
{

    /**
     * @var Search $search
     */
    protected $search;

    public function findTenders(Search $search)
    {
        $this->search = $search;
        $this->findSmartTender();
//        $this->findAlladin();
//        $this->findTenderGid();
//        $this->findRealto();
    }

    private function findSmartTender()
    {
        {
            $this->setLinksStartEnd('https://smarttender.biz/komertsiyni-torgy/?p=', '');
            $this->setLink();

            $res = $this->getNewLinksSmarttender();
//            $res = [
//                [
//                    'url' => 'https://smarttender.biz/komertsiyni-torgy/5820078/',
//                    'date_end' => '22.01.2019 16:00:00'
//                ]
//            ];

            foreach ($res as $num=>$info) {
                try {
                    $html = $this->client->get($info['url'])->getBody();
                }catch (\GuzzleHttp\Exception\RequestException $ex){
                    \Log::error($info['url'] . PHP_EOL ."error while receiving page content");
                    continue;
                }

                $crawler = new Crawler();
                $crawler->addHtmlContent($html);
                try {
                    //lots pattern
                    $pattern = "/\"MinimalStep\":.+?,\s*\"title\":\"(.+?)\",/i";
                    preg_match_all($pattern, $html, $lots, PREG_SET_ORDER);
                    $lots = array_map([$this, 'getValuesFromRegex'],$lots);


                    //name pattern
                    $pattern = "/\"Number\":.+?,\"Title\":\"(.+?)\",/i";
                    if(preg_match($pattern, $html, $name)){
                        $tender_name = $name[1];
                    } else{
                        \Log::alert($info['url'] . " error with tender name");
                        $tender_name = 'undefined';
                    }
                }
                catch (\GuzzleHttp\Exception\RequestException $ex){
                    \Log::error($info['url'] . PHP_EOL ."error while finding lots and getting name");
                    continue;
                }
                try{
                    $info['type'] = 'smarttender';
                    $info['search_id'] = $this->search->id;
                    $tender = $this->search->tenders()->create($info);
                    array_push($lots, $tender_name);
                    if ($this->checkLots($lots)) {
                        array_pop($lots);
                        $successTender = $tender->successTender()->create(['tender_name' => trim($tender_name)]);
                        foreach ($lots as $lot){
                            $successTender->lots()->create(['lot' => trim($lot)]);
                        }
                    }
                }catch (\Exception $ex){
                    \Log::error( $info['url'].PHP_EOL."!!!!!!$ex!!!!!!" . PHP_EOL . PHP_EOL . PHP_EOL);
                }
                //$this->logger->info('added to DB');
            }
        }
    }

    private function findAlladin()
    {
        {
            $ch = curl_init();

            // set url
            curl_setopt($ch, CURLOPT_URL, "https://alltenders.ald.in.ua/api/tender/list?parameters=%7B%22pageIndex%22:0,%22pageSize%22:1000,%22filters%22:%7B%22stageStateId%22:%7B%22value%22:2,%22matchMode%22:%22in%22%7D%7D,%22column%22:%22dateStart%22,%22order%22:1%7D");

            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            // $output contains the output string
            $output = curl_exec($ch);

            // close curl resource to free up system resources
            curl_close($ch);

            $res = \GuzzleHttp\json_decode($output)->data;

            foreach ($res as $tenderInfo) {
                $url = $tenderInfo->aladdinId;

                if(!Tender::where('url', $url)->count()) {
                    $name = $tenderInfo->title;
                    $date = $tenderInfo->dateEnd;
//                dd($date);
                    try {
                        $info = [
                            'url' => $url,
                            'type' => 'alladin',
                            'end_date' => $date,
                            'search_id' => $this->search->id,
                        ];
                        $tender = Tender::create($info);

                        if ($this->checkLots([$name])) {
                            $tender->successTender()->create(['tender_name' => trim($name)]);
                        }
                    } catch (\Exception $ex) {
                        \Log::error($info['url'] . PHP_EOL . "!!!!!!$ex!!!!!!" . PHP_EOL . PHP_EOL . PHP_EOL);
                    }
                }
            }
        }
    }


    private function findRealto()
    {
        $headers = ['Content-Type' => 'application/json; charset=UTF8'];
        $payload_start = '{"Page":';
        $payload_end = ',"PageSize":10,"OrderColumn":"","OrderDirection":"desc","SearchFilter":{"PriceFrom":"","PriceTo":"","ProcurementMethod":"open","procurementMethodTypes":[],"regions":[],"Statuses":["active.enquiries","active.tendering"],"IsStasusesDefaulted":false,"Cpvs":[],"Dkpp":null,"isProductionMode":true,"parentCodesEDRPOU":[],"codeEDRPOUs":"","Title":null,"OrganizationName":null,"searchTimeType":null,"tenderPeriodEndFrom":null,"tenderPeriodEndTo":null,"tenderPeriodStartFrom":null,"tenderPeriodStartTo":null,"isShowOnlyTendersCreatedOnOurSite":false,"CustomerRegion":null,"IsRealTendersForTestMode":false}}';
        $this->page=1;
        $ids = [];
        $dates = [];
        $maxPage=1;
        do {
            $payload = $payload_start . $this->page . $payload_end;
            $url = 'https://rialto.e-tender.ua/api/services/etender/tender/GetTenders';

            $return = $this->payload($url, $payload, $headers)['result'];

            if (empty($maxPage)) {
                $maxPage = (int)($return['countAllRecords'] / 10 + 1);
            }

            foreach ($return['tender'] as $value) {
                $link = "https://rialto.e-tender.ua/#/tenderDetailes/" . $value['id'];
                if (!$this->db->checkExists($link)) {
                    $ids[] = $value['id'];
                    $dates[] = date("Y-m-d H:i:s", strtotime($value['tenderEndDate']));
                }
            }
            $this->page++;
        } while ($this->page<=$maxPage);

        $url = 'https://rialto.e-tender.ua/api/services/etender/tender/GetTender';
        foreach ($ids as $key => $id) {
            $lots = [];
            $payload = '{"id":"' . $id . '","display":true}';
            $return = $this->payload($url, $payload, $headers)['result'];

            $name =  $return['title'];
            foreach ($return['lots'] as $lot) {
                foreach ($lot['items'] as $item) {
                    $lots[] = $item['description'];
                }
            }
            $link = "https://rialto.e-tender.ua/#/tenderDetailes/" . $id;
            $this->db->writeLinkToDatabase($link, $dates[$key]);
            array_push($lots, $name);
            if ($this->checkLots($lots)) {
                array_pop($lots);
                $this->db->writeSuccessLinkToDatabase($name);
                $this->db->writeLotToDatabase($lots);
            }
        }
    }

    private function findTenderGid()
    {
        $this->setLinksStartEnd('https://tendergid.ua/ru/тендеры/actual/0/type/1/sort/published:desc/page/', '');
        $this->page=0;
        $this->setLink();
        $tenders = [];

        $html = $this->client->get($this->link)->getBody();

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);
        $res = $crawler->filter('div.pages.for_search_place>a')->eq(6)->text();
        $max_page = ($res-1)*25;

        while ($this->page <= $max_page) {
            $this->setLink();
            $html = $this->client->get($this->link)->getBody();
            $html = iconv("windows-1251", "UTF-8", $html);
            $crawler->clear();
            $crawler->addHtmlContent($html, 'UTF-8');

            $links = $info = $crawler->filter('td.col3>div>div>a')->each(function (Crawler $node, $i) {
                return substr($node->attr('href'), 2);
            });

            $names = $crawler->filter('td.col3>div>div>a>span')->each(function (Crawler $node, $i) {
                return $node->text();
            });

            $dates = $crawler->filter('td.col2>div>a>span')->each(function (Crawler $node, $i) {
                return $node->text();
            });


            foreach ($links as $key => $link) {
                if (!$this->db->checkExists($link)&&$this->db->checkOutOfDate(strtotime($dates[$key]))) {
                    $tenders[] = [
                        'link'=>$link,
                        'name'=>$names[$key],
                        'date'=>date("Y-m-d H:i:s", strtotime($dates[$key])),
                    ];
                }
            }
            $this->page+=25;
        }

        if (count($tenders)==0) {
            return;
        }
        foreach ($tenders as $key=>$info) {
            $this->db->writeLinkToDatabase($info['url'], $info['end_date']);
            if ($this->checkLots([$info['name']])) {
                $this->db->writeSuccessLinkToDatabase($info['name']);
            }
        }
    }

    private function getValuesFromRegex($a)
    {
        return $a[1];
    }
}
