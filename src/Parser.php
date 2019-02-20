<?php

namespace SomeWork\Minjust;

use SomeWork\Minjust\Strategy\ParseStrategyInterface;
use PHPHtmlParser\Dom;

class Parser
{
    /**
     * @var Dom
     */
    private $dom;

    /**
     * @var ParseStrategyInterface[]
     */
    private $strategies;

    public function __construct(Dom $dom, array $strategies)
    {
        $this->dom = $dom;
        $this->strategies = $strategies;
    }

    public function buildResponse(string $body): FindResponse
    {
        $dom = new Dom();
        $dom->load($body);
        $strategy = $this->guessStrategy($dom);

        $findResponse = new FindResponse();
        $findResponse
            ->setPage($strategy->getPage($dom))
            ->setTotalPage($strategy->getTotalPage($dom))
            ->setElements($strategy->getElements($dom));

        return $findResponse;
    }

    protected function guessStrategy(Dom $dom)
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->isSupport($dom)) {
                return $strategy;
            }
        }

        throw new \LogicException('No strategy found for current dom');
    }
}
