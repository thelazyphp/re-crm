<?php

namespace App\Parsers;

use App\HtmlDocument;
use App\ParserRule;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class Kufar
{
    /**
     * @var int
     */
    protected $total = 0;

    /**
     * @var array
     */
    protected $parsedLinks = [];

    /**
     * @param  int|null  $limit
     * @return float
     */
    public function start($limit = null)
    {
        error_reporting(E_ERROR);
        set_time_limit(0);

        $start = microtime(true);

        $links = [
            'https://cre-api.kufar.by/items-search/v1/engine/v1/search/rendered-paginated?prn=1000&size=30&sort=lst.d&typ=sell&cat=1010&gbx=b%3A23.550417068107055%2C51.901245686174285%2C23.905412795646125%2C52.28316029724989&gtsy=country-belarus~province-brestskaja_oblast~locality-brest',
        ];

        $html = new HtmlDocument();

        foreach ($links as $link) {
            try {
                $res = Http::retry(5, 10000)->get($link);
            } catch (RequestException $e) {
                continue;
            }

            if ($res->successful()) {
                $json = $res->json();

                for (;;) {
                    if (isset($json['ads'])) {
                        foreach ($json['ads'] as $ad) {
                            if (isset($ad['ad_link'])) {
                                if (! is_null($limit) && $this->total >= $limit) {
                                    break 3;
                                }

                                try {
                                    $res = Http::retry(5, 10000)->get($ad['ad_link']);
                                } catch (RequestException $e) {
                                    continue;
                                }

                                if ($res->successful() && $html->loadHTML($res->body())) {
                                    //

                                    $this->parsedLinks[] = $ad['ad_link'];

                                    //

                                    $json = (new ParserRule())->find('#__NEXT_DATA__')->text()->fromJson()->eval($html);

                                    //
                                }
                            }
                        }
                    } else {
                        break;
                    }

                    if (isset($json['pagination'])) {
                        $item = collect($json['pagination']['pages'])->first(function ($item) {
                            return 'next' == $item['label'];
                        });

                        if ($item) {
                            try {
                                $res = Http::retry(5, 10000)->get("{$link}&cursor={$item['token']}");
                            } catch (RequestException $e) {
                                continue;
                            }

                            if ($res->successful()) {
                                $json = $res->json();
                            } else {
                                break;
                            }
                        } else {
                            break;
                        }
                    } else {
                        break;
                    }
                }
            }
        }

        return microtime(true) - $start;
    }
}
