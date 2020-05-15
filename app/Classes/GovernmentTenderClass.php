<?php

namespace App\FindClasses;

use App\Models\Search;
use App\Models\Tender;
use Exception;

class GovernmentTenderClass extends TenderClass
{
    //возвращает все гос. тендеры
    public function __construct(Tender $tender)
    {
        parent::__construct($tender);
        $this->tradeSegment = 3;
        $this->smartTenderUrl = 'https://smarttender.biz/publichni-zakupivli-prozorro/';}

    public function findTenders(Search $search)
    {
        $res = $this->getNewLinksSmarttender();
//        $res[] = ['url' => 'https://smarttender.biz/publichni-zakupivli-prozorro/9012885/', 'end_date'=>'2020-04-08 10:15:00', 'tenderId' => '9012885'];

        foreach ($res as $info) {
            $tender_id = $info['tenderId'];

            $payload = "{\"tenderId\":\"{$tender_id}\"}";
            $headers = ['Content-Type' => 'application/json; charset=UTF8'];
            $url = 'https://smarttender.biz/uk/PurchaseDetail/GetTenderModel/';


            try {
                $res = $this->payload($url, $payload, $headers, 'GET');
                $tender_name = $this->getTenderName($res);

                $lots_list = [];
                if(!empty($res['Lots'])){
                    //Multilot
                    foreach ($res['Lots'] as $lot) {
                        $lotId = $lot['LotId'];
                        $payload = "{\"lotId\":\"{$lotId}\"}";
                        $headers = ['Content-Type' => 'application/json; charset=UTF8'];
                        $url = 'https://smarttender.biz/uk/PurchaseDetail/GetLotModel/';

                        $res = $this->payload($url, $payload, $headers, 'GET');

                        $lots_list = array_merge($lots_list, $this->getMultiLotsDescriptions($res));
                        $lots_list = $this->addMultiLotsLots($res, $lots_list);
                    }
                }else{
                    $lots_list = $this->getNomenclatures($res);
                }
                $data = [
                    'url' => $info['url'],
                    'end_date' => $info['end_date'],
                    'type' => 'government',
                    'search_id' => $search->id,
                ];

                $tender = $search->tenders()->create($data);
                array_push($lots_list, $tender_name);
                if ($this->checkLots($lots_list)) {
                    array_pop($lots_list);
                    $successTender = $tender->successTender()->create(['tender_name' => trim($tender_name)]);
                    foreach ($lots_list as $lot){
                        $successTender->lots()->create(['lot' => trim($lot)]);
                    }
                }

            } catch (Exception $ex) {
                \Log::error( $info['url'].PHP_EOL."!!!!!!$ex!!!!!!" . PHP_EOL . PHP_EOL . PHP_EOL);
            }
        }
    }

    private function getNomenclatures($info)
    {
        return array_map(function ($item){
            return $item['Title'];
        },$info['Nomenclatures']);
    }

    private function getMultiLotsDescriptions($info)
    {
        return array_map(function ($item){
            return $item['Description']??'';
        },$info['Lots']);
    }

    private function addMultiLotsLots($info, $lots_list)
    {
        array_map(function ($item) use(&$lots_list){
            $lots_list = array_merge($lots_list, $this->getNomenclatures($item));
        },$info['Lots']);

        return $lots_list;
    }

    private function getTenderName($res)
    {
        $tender_name = $res['Title']??'';
        $tender_name .= '  ,description: ';
        $tender_name .= $res['Description']??'';

        return $tender_name;
    }
}
