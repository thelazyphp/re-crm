<?php

namespace App\Parsing;

use App\Parsing\DOM\Document;

/**
 *
 */
class ProxyProvider
{
    /**
     * @var array
     */
    protected $parsers = [];

    /**
     * @var array
     */
    protected $proxies = [];

    /**
     *
     */
    public function __construct()
    {
        $this->registerParsers();
    }

    /**
     * @return void
     */
    protected function registerParsers()
    {
        $this->parsers = [
            'https://free-proxy-list.net/' => function (Document $doc) {
                $proxies = [];

                foreach ($doc->querySelectorAll('#proxylisttable > tbody > tr') as $trElem) {
                    $tdElems = $trElem->querySelectorAll('td');
                    $scheme = $tdElems[6]->textContent == 'no' ? 'http' : 'https';
                    $proxies[] = $scheme.'://'.$tdElems[0]->textContent.':'.$tdElems[1]->textContent;
                }

                return $proxies;
            },

            'https://www.proxynova.com/proxy-server-list/' => function (Document $doc) {
                $proxies = [];

                foreach ($doc->querySelectorAll('#tbl_proxy_list > tbody > tr') as $trElem) {
                    $tdElems = $trElem->querySelectorAll('td');

                    if ($elem = $tdElems[0]->querySelector('script')) {
                        if (preg_match('/document\.write\(\'(.+?)\'\);/', $elem->textContent, $matches) && $matches[1]) {
                            $proxies[] = 'http://'.$matches[1].':'.trim($tdElems[1]->textContent);
                        }
                    }
                }

                return $proxies;
            },
        ];
    }
}
