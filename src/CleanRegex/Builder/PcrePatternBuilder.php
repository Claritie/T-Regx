<?php
namespace TRegx\CleanRegex\Builder;

use TRegx\CleanRegex\Internal\Delimiter\Strategy\PcreStrategy;
use TRegx\CleanRegex\Internal\Prepared\Parser\BindingParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\InjectParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\PreparedParser;
use TRegx\CleanRegex\Internal\Prepared\PrepareFacade;
use TRegx\CleanRegex\Internal\Prepared\Template\NoTemplate;
use TRegx\CleanRegex\PatternInterface;
use TRegx\CleanRegex\Template;

class PcrePatternBuilder
{
    public function bind(string $input, array $values): PatternInterface
    {
        return PrepareFacade::build(new BindingParser($input, $values, new NoTemplate()), new PcreStrategy());
    }

    public function inject(string $input, array $values): PatternInterface
    {
        return PrepareFacade::build(new InjectParser($input, $values, new NoTemplate()), new PcreStrategy());
    }

    public function prepare(array $input): PatternInterface
    {
        return PrepareFacade::build(new PreparedParser($input), new PcreStrategy());
    }

    public function template(string $pattern): Template
    {
        return new Template($pattern, new PcreStrategy());
    }
}
