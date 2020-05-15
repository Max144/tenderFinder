<?php

namespace App\FindClasses;


use App\Models\Tender;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Exception\RequestException;

abstract class TenderClass
{
    protected
        $client,
        $tenderModel,
        $tradeSegment,
        $smartTenderUrl;
    /**
     * @var array
     */
    protected $keywords;

    public function __construct(Tender $tender)
    {
        $keywords = config('keywords.keywords');
//        include_once 'keywords.txt';
        $this->keywords = $keywords;
        $this->client = new Client();
        $this->tenderModel = $tender;
    }

    protected function checkLots(array $lots)
    {
        foreach ($lots as $lot) {
            foreach ($this->keywords as $pattern) {
                if (preg_match($pattern . "ui", $lot)) {
                    return true;
                }
            }
        }
        return false;
    }

    protected function createTenderLink($tenderId)
    {
        return $this->smartTenderUrl . $tenderId . '/';
    }

    /**
     * @return array
     */
    protected function getNewLinksSmarttender()
    {
        try {
            $result = [];
            $page = 1;
            do {
                $res = $this->client->post('https://smarttender.biz/ProZorroTenders/GetTenders/', [
                    'form_params' => [
                        'searchParam' => [
                            'AdditionalBiddingTypesClassification' => null,
                            'AddressSearchTypes' => [1],
                            'AssignedManagerIds' => [],
                            'AwardStatusCodes' => [],
                            'BiddingTypeCodes' => [],
                            'CategoryIds' => [],
                            'ClassificationGroupId' => null,
                            'GroupedBiddingTypeCodes' => [],
                            'MainProcurementCategoryIds' => [],
                            'MyFilterId' => null,
                            'OrganizerIds' => [],
                            'Page' => $page,
                            'PaymentTermTypeIds' => [],
                            'RegionInfos' => [],
                            'Sorting' => 2,
                            'TenderMode' => 1,
                            'TenderStatuses' => [],
                            'TradeSegment' => $this->tradeSegment,
                        ]
                    ]
                ]);

                $tendersResult = json_decode($res->getBody()->getContents());
//                $pagesCount = ceil($tendersResult->TotalCount / 20);
                $pagesCount = 1;
                $tenders = $tendersResult->Tenders;

                $newTenders = array_filter($tenders, function ($tender) {
                    return !$this->tenderModel->where('url', $this->createTenderLink($tender->Id))->exists();
                });

                foreach ($newTenders as $tender) {
                    $date = Carbon::parse($tender->TenderingPeriod->DateEnd)->format('Y-m-d H:i:s');
                    $result[] = [
                        'url' => $this->createTenderLink($tender->Id),
                        'end_date' => $date,
                        'tenderId' => $tender->Id
                    ];
                }
            } while ($page++ < $pagesCount);
        } catch (RequestException $ex) {
            var_dump("нет доступа\n");
            return [];
        }
        return $result;
    }

    /**
     * @param string $url
     * @param array $data
     * @param array $send_headers
     * @param string|null $requestType
     * @return array
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function payload($url, $data = array(), $send_headers = array(), string $requestType = null)
    {
        $request = new Request(
            $requestType ?? 'POST',
            $url,
            $send_headers,
            $data
        );
        $return = $this->client->send($request);

        $content = $return->getBody()->getContents();

        $res = json_decode($content, true);
        return ($res);
    }

    public function deletePassedDates()
    {
        $this->tenderModel->whereDate('end_date', '<', Carbon::now())->delete();
    }
}
