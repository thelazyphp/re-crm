<?php

namespace App\Parsing;

use GuzzleHttp\Client;
use Psr\Http\Message\UriInterface;
use Throwable;
use App\Parsing\DOM\Document;
use App\Group;
use App\Post;
use GuzzleHttp\Psr7\Uri;
use App\Parsing\DOM\Element;

/**
 *
 */
class Parser
{
    /**
     * @var string
     */
    protected $userAgent = 'Mozilla/5.0 (iPhone; CPU iPhone OS 7_1_2 like Mac OS X) AppleWebKit/537.51.2 (KHTML, like Gecko) Version/7.0 Mobile/11D257 Safari/9537.53';

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $mobileVersionUrlHost = 'm.facebook.com';

    /**
     *
     */
    public function __construct()
    {
        $this->client = new Client([
            'cookies' => true,
            'verify' => false,
            // 'proxy' => 'http://127.0.0.1:80',

            'headers' => [
                'Accept'                    => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
                'Accept-Encoding'           => 'gzip, deflate',
                'Accept-Language'           => 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
                'Cache-Control'             => 'max-age=0',
                'Sec-Fetch-Dest'            => 'document',
                'Sec-Fetch-Mode'            => 'navigate',
                'Sec-Fetch-Site'            => 'none',
                'Sec-Fetch-User'            => '?1',
                'Upgrade-Insecure-Requests' => '1',
                'User-Agent'                => $this->userAgent,
            ],
        ]);
    }

    /**
     * @param string|UriInterface $url
     * @return array|false
     */
    public function searchGroup($url)
    {
        $errorLevel = error_reporting();
        error_reporting(E_ERROR);

        $maxExecutionTime = (int) ini_get('max_execution_time');
        set_time_limit(0);

        $url = $this->replaceUrlHost(
            $url, $this->mobileVersionUrlHost
        );

        try {
            $response = $this->client->get($url);
        } catch (Throwable $e) {
            //

            return false;
        }

        $html = (string) $response->getBody();

        $document = new Document();

        if (!$document->loadHTML($html)) {
            //

            return false;
        }

        $attributes = $this->parseGroupAttributes($document);

        error_reporting($errorLevel);
        set_time_limit($maxExecutionTime);

        return $attributes;
    }

    /**
     * @param null|string|string[] $groupId
     * @return float
     */
    public function startParsing($groupId = null)
    {
        $errorLevel = error_reporting();
        error_reporting(E_ERROR);

        $maxExecutionTime = (int) ini_get('max_execution_time');
        set_time_limit(0);

        $start = microtime(true);

        $groups = is_null($groupId)
            ? Group::all()
            : Group::whereIn('id', (array) $groupId)->get();

        foreach ($groups as $group) {
            if (!$group->get_posts) {
                continue;
            }

            $url = $this->replaceUrlHost(
                $group->url, $this->mobileVersionUrlHost
            );

            try {
                $response = $this->client->get($url);
            } catch (Throwable $e) {
                //

                continue;
            }

            $html = (string) $response->getBody();

            $document = new Document();

            if (!$document->loadHTML($html)) {
                //

                continue;
            }

            $groupAttributes = $this->parseGroupAttributes($document);

            $group->fill([
                'is_public' => $groupAttributes['is_public'],
                'is_visible' => $groupAttributes['is_visible'],
                'image' => $groupAttributes['image'],
                'name' => $groupAttributes['name'],
                'description' => $groupAttributes['description'],
            ]);

            $group->save();

            if (!$group->is_public) {
                //

                continue;
            }

            $group->is_parsing = true;
            $group->save();

            foreach ($document->querySelectorAll('#m_group_stories_container > section > article') as $postElement) {
                $postAttributes = $this->parsePostAttributes($postElement, $group->id);

                // echo '<pre>';
                // print_r($postAttributes);
                // echo '</pre>';

                if (
                    empty($postAttributes['id'])
                    || empty($postAttributes['group_id'])
                    || empty($postAttributes['url'])
                    || empty($postAttributes['user_name'])
                ) {
                    //

                    continue;
                }

                Post::updateOrCreate(
                    ['id' => $postAttributes['id']], $postAttributes
                );
            }

            $group->is_parsing = false;
            $group->save();
        }

        error_reporting($errorLevel);
        set_time_limit($maxExecutionTime);

        return microtime(true) - $start;
    }

    /**
     * @param string|UriInterface $url
     * @param string $host
     *
     * @return UriInterface
     */
    protected function replaceUrlHost($url, $host)
    {
        if (is_string($url)) {
            $url = new Uri($url);
        }

        return $url->withHost($host);
    }

    /**
     * @param string $url
     * @return string
     */
    protected function decodeResourceUrl($url)
    {
        return preg_replace_callback('/\\\([a-zA-Z0-9]{2}) /', function ($matches) {
            return chr(hexdec($matches[1]));
        }, $url);
    }

    /**
     * @param string $text
     * @return string
     */
    protected function convertDateTime($text)
    {
        $months = [
            'января'   => 'jan',
            'февраля'  => 'feb',
            'марта'    => 'mar',
            'апреля'   => 'apr',
            'мая'      => 'may',
            'июня'     => 'jun',
            'июля'     => 'jul',
            'августа'  => 'aug',
            'сентября' => 'sep',
            'октября'  => 'oct',
            'ноября'   => 'nov',
            'декабря'  => 'dec',
            ' г.'       => '',
            'ч.'       => 'hours ago',
            'мин.'     => 'minutes ago',
            'нед.'     => 'weeks ago',
            'Вчера в'  => 'yesterday',
        ];

        $text = str_ireplace(
            array_keys($months), array_values($months), $text
        );

        return date(
            'Y-m-d H:i:s', strtotime($text)
        );
    }

    /**
     * @param Document $document
     * @return array
     */
    protected function parseGroupAttributes(Document $document)
    {
        $result = [
            'id' => null,
            'url' => null,
            'is_public' => false,
            'is_visible' => false,
            'image' => null,
            'name' => null,
            'description' => null,
        ];

        if (!empty($element = $document->querySelector('._5i-y > [href^="/groups/"]'))) {
            if (preg_match('/^\/groups\/(\d+)/', $element->href, $matches)) {
                if ($matches[1]) {
                    $result['id'] = $matches[1];
                    $result['url'] = 'https://facebook.com/groups/'.$result['id'];
                }
            }
        }

        if (($count = count($elements = $document->querySelectorAll('._52ja._52jg'))) > 0) {
            if (mb_strpos(utf8_decode($elements[0]->textContent), 'Общедоступная') !== false) {
                $result['is_public'] = true;
            }

            if ($count > 1) {
                if (mb_strpos(utf8_decode($elements[1]->textContent), 'Видимая') !== false) {
                    $result['is_visible'] = true;
                }
            }
        }

        if (!empty($element = $document->querySelector('._3m1l > .img'))) {
            if (preg_match('/url\([\'"]?(.+?)[\'"]?\)/', $element->style, $matches)) {
                if ($matches[1]) {
                    $result['image'] = $this->decodeResourceUrl($matches[1]);
                }
            }
        }

        if (!empty($element = $document->querySelector('._de1'))) {
            $result['name'] = trim(utf8_decode($element->textContent));
        }

        if (($count = count($elements = $document->querySelectorAll('._4g34._5b6q._5b6p'))) > 3) {
            $result['description'] = trim(utf8_decode($elements[3]->textContent));
        }

        return $result;
    }

    /**
     * @param Element $document
     * @param string $groupId
     *
     * @return array
     */
    protected function parsePostAttributes(Element $postElement, $groupId)
    {
        $result = [
            'id' => null,
            'group_id' => $groupId,
            'url' => null,
            'user_id' => null,
            'user_url' => null,
            'user_image' => null,
            'user_name' => null,
            'message' => null,
            'images' => [],
            'videos' => [],
            'likes' => 0,
            'comments' => 0,
            'published_at' => null,
        ];

        if (preg_match('/top_level_post_id\.(\d+)/', $postElement->{'data-store'}, $matches)) {
            if ($matches[1]) {
                $result['id'] = $groupId.'_'.$matches[1];
            }
        }

        if (!empty($element = $postElement->querySelector('.story_body_container > header h3 [href^="/story.php?"]'))) {
            $result['url'] = 'https://facebook.com'.$element->href;
        } elseif (!empty($element = $postElement->querySelector('.story_body_container [aria-label="Открыть новость"]'))) {
            // $result['url'] = 'https://facebook.com'.$element->href;
            $result['url'] = $element->href;
        }

        if (preg_match('/\\\"actor_id\\\":(\d+)/', $postElement->{'data-store'}, $matches)) {
            if ($matches[1]) {
                $result['user_id'] = $matches[1];
            }
        }

        if (!empty($element = $postElement->querySelector('.story_body_container > header h3 a'))) {
            if ($element->href) {
                $result['user_url'] = 'https://facebook.com'.$element->href;
            } else if ($result['user_id']) {
                $result['user_url'] = 'https://facebook.com/'.$result['user_id'];
            }

            $result['user_name'] = utf8_decode($element->textContent);
        }

        if (!empty($element = $postElement->querySelector('.story_body_container > header .profpic'))) {
            // if (preg_match('/url\([\'"]?(.+?)[\'"]?\)/', $element->style, $matches)) {
            //     if ($matches[1]) {
            //         $result['user_image'] = $this->decodeResourceUrl($matches[1]);
            //     }
            // }

            $result['user_image'] = $element->src;
        }

        $message = '';

        if (!empty($element = $postElement->querySelector('.story_body_container > header ~ * [role="presentation"]'))) {
            $message = trim(utf8_decode($element->textContent));
        } elseif (!empty($element = $postElement->querySelector('.story_body_container > header ~ *'))) {
            $message = trim(utf8_decode($element->textContent));

            if (!empty($element = $postElement->querySelector('.story_body_container .story_body_container > header ~ *'))) {
                $message .= ' '.trim(utf8_decode($element->textContent));
            }
        } elseif (!empty($element = $postElement->querySelector('.story_body_container .story_body_container > header ~ *'))) {
            $message = trim(utf8_decode($element->textContent));
        }

        if (!empty($message)) {
            $result['message'] = $message;
        }

        foreach ($postElement->querySelectorAll('.story_body_container > header ~ * .img') as $element) {
            // if (preg_match('/url\([\'"]?(.+?)[\'"]?\)/', $element->style, $matches)) {
            //     if ($matches[1]) {
            //         $result['images'][] = $this->decodeResourceUrl($matches[1]);
            //     }
            // }

            $result['images'][] = $element->src;
        }

        foreach ($postElement->querySelectorAll('.story_body_container > header ~ * [data-store*="videoID"]') as $element) {
            if (preg_match('/"src":"(.+?)"/', $element->{'data-store'}, $matches)) {
                if ($matches[1]) {
                    $result['videos'][] = str_replace("\/", "/", $matches[1]);
                }
            }
        }

        if (!empty($element = $postElement->querySelector('footer .like_def'))) {
            $result['likes'] = (int) preg_replace(
                '/\D/', '', utf8_decode($element->textContent)
            );
        }

        if (!empty($element = $postElement->querySelector('footer .cmt_def'))) {
            $result['comments'] = (int) preg_replace(
                '/\D/', '', utf8_decode($element->textContent)
            );
        }

        // if (preg_match('/\\\"publish_time\\\":(\d+)/', $postElement->{'data-store'}, $matches)) {
        //     if ($matches[1]) {
        //         $result['published_at'] = date('Y-m-d H:i:s', (int) $matches[1]);
        //     }
        // }

        if (!empty($element = $postElement->querySelector('.story_body_container > header abbr'))) {
            $result['published_at'] = $this->convertDateTime(utf8_decode($element->textContent));
        }

        if (empty($result['published_at'])) {
            $result['published_at'] = date('Y-m-d H:i:s', time());
        }

        return $result;
    }
}
