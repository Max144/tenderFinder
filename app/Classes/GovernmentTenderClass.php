<?php

namespace App\FindClasses;

use App\Models\Tender;
use Exception;

class GovernmentTenderClass extends TenderClass
{
    //возвращает все гос. тендеры
    public function __construct(Tender $tender)
    {
        parent::__construct($tender);
        $this->setLinksStartEnd('https://smarttender.biz/publichni-zakupivli-prozorro/?p=', '&extfilter=1&statuses=%2b%2cP&nh=1');
    }

    public function findTenders()
    {
        $this->makeTendersOld();
        $this->setLink();

        $res = $this->getNewLinksSmarttender();
//        $res[] = ['link' => 'https://smarttender.biz/publichni-zakupivli-prozorro/5352308/', 'date'=>'asd'];
        $pattern="/\/(\d+)/";
        $numNewLinks = count($res);

        foreach ($res as $num=>$info) {
            preg_match($pattern, $info['url'], $res);
            $tender_id = $res[1];

            $payload = "{\"tenderId\":\"{$tender_id}\"}";
            $headers = ['Content-Type' => 'application/json; charset=UTF8'];
            $url = 'https://smarttender.biz/uk/PurchaseDetail/GetTenderModel/';


            try {
                $res = $this->payload($url, $payload, $headers);

                $tender_name = $this->getTenderName($res);

                $lots_list = [];
                if(!empty($res['Lots'])){
                    //Multilot
                    foreach ($res['Lots'] as $lot) {
                        $lotId = $lot['LotId'];
                        $payload = "{\"lotId\":\"{$lotId}\"}";
                        $headers = ['Content-Type' => 'application/json; charset=UTF8'];
                        $url = 'https://smarttender.biz/uk/PurchaseDetail/GetLotModel/';

                        $res = $this->payload($url, $payload, $headers);

                        $lots_list = array_merge($lots_list, $this->getMultiLotsDescriptions($res));
                        $lots_list = $this->addMultiLotsLots($res, $lots_list);
                    }
                }else{
                    $lots_list = $this->getNomenclatures($res);
                }

                $info['type'] = 'government';
                $tender = $this->tenderModel::create($info);
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

    public function makeTendersOld()
    {
        $successTenders = $this->tenderModel->whereHas('successTender')->where('type', 'government')->get();

        foreach ($successTenders as $tender){
            $tender->successTender()->update(['new' => false]);
        }
    }
}
