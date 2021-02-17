<?php

namespace App\Parsers;

use App\Models\Ad;
use App\Parser;
use App\ParserRule;
use Psr\Http\Message\UriInterface;

class Irr extends Parser
{
    /**
     * {@inheritDoc}
     */
    public $categoryId = Ad::CATEGORY_APARTMENTS_ID;

    /**
     * {@inheritDoc}
     */
    public $sourceId = Ad::SOURCE_IRR_ID;

    /**
     * {@inheritDoc}
     */
    public function rules(UriInterface $url): array
    {
        return [
            'title' => (new ParserRule())->find('.title3')->text()->normalizeSpaces(),
        ];
    }
}
