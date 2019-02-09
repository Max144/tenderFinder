<?php

namespace App\FindClasses;



use App\Models\Tender;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Exception\RequestException;

class TenderClass
{
    protected
        $link_start,
        $link_end,
        $link,
        $page=1,
        $client,
        $tenderModel;
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

    protected function setLink()
    {
        $this->link = $this->link_start . $this->page . $this->link_end;
    }

    protected function setLinksStartEnd($start, $end)
    {
        $this->link_start=$start;
        $this->link_end=$end;
    }

    protected function checkLots(array $lots)
    {
        foreach ($lots as $lot) {
            foreach ($this->keywords as $pattern) {
                if (preg_match($pattern."ui", $lot)) {
                    return true;
                }
            }
        }
        return false;
    }

    protected function getSmartTenderMaxPage($html)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $pages_count = $crawler->filter('.pager-button')->last()->text();

        return $pages_count;
    }

    protected function getSmarttenderLinks($html)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $result = $crawler->filter('td.col2>a.linkSubjTrading')->each(function (Crawler $node, $i) {
            return $node->attr('href');
        });

        return $result;
    }

    protected function getSmarttenderDates($html)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $result = $crawler->filter('td.col6')->each(function (Crawler $node, $i) {
            $pattern = "/прийом пропозицій до:\s*(\d{2}\.\d{2}\.\d{4}\s\d{2}:\d{2})/ui";
            preg_match($pattern, $node->text(), $date);
            return $date[1];
        });

        return $result;
    }

    /**
     * @return array
     */
    protected function getNewLinksSmarttender()
    {
        try  {
            $html = $this->client->get($this->link)->getBody();
            $pages_count = $this->getSmartTenderMaxPage($html);
//            $pages_count = 1;
            \Log::info("количество страниц : $pages_count");

            $result = array();
            do {
//                if($this->page>2000) {
                    $this->setLink();
//                    echo($this->page . '/' . $pages_count);

                    $html = $this->client->get($this->link)->getBody();

                    $links = $this->getSmarttenderLinks($html);
//
//              дата окончания тендера
                    $dates = $this->getSmarttenderDates($html);
                    $this->tenderModel;
                    $model = $this->tenderModel;
                    $links = array_filter($links, function ($url) use ($model){
                        return !$this->tenderModel->where('url', $url)->count();
                    });
                    foreach ($links as $key => $value) {
                        $date = \DateTime::createFromFormat('d.m.Y*H:i', $dates[$key]);
                        $result[] = ['url' => $value, 'end_date' => $date];
                    }
//                }
                $this->page++;
            } while ($this->page<=$pages_count);
        }catch (RequestException $ex){
            var_dump("нет доступа\n");
            return [];
        }
        return $result;
    }

    /**
     * @param string $url
     * @param array $data
     * @param array $send_headers
     * @param bool $return_headers
     * @return array
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function payload($url, $data=array(), $send_headers=array(), $return_headers=false)
    {
        $request = new Request(
            'POST',
            $url,
            $send_headers,
            $data
        );
        $return = $this->client->send($request);

        $content = $return->getBody()->getContents();

        $res = json_decode($content, true);
        return($res);
    }

    public function deletePassedDates()
    {
        $this->tenderModel->whereDate('end_date', '<', Carbon::now())->delete();
    }

    abstract public function makeTendersOld();
}
