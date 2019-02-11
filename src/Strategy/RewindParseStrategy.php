<?php

namespace SomeWork\Minjust\Strategy;

use PHPHtmlParser\Dom;

class RewindParseStrategy extends AbstractParseStrategy
{
    public function getPage(Dom $dom): int
    {
        return $dom->find('span.currentStep')[0]->text();
    }

    public function getTotalPage(Dom $dom): int
    {
        $collection = $this->getSteps($dom);

        return (int) $collection->offsetGet($collection->count() - 2)->text();
    }

    protected function getSteps(Dom $dom): Dom\Collection
    {
        return $dom->find('span.summ > div.pagination > a > a');
    }

    public function isSupport(Dom $dom): bool
    {
        return $this->getSteps($dom)->count() > 0;
    }
}
