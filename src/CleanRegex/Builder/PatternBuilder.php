<?php
namespace TRegx\CleanRegex\Builder;

use TRegx\CleanRegex\Internal\Prepared\Parser\BindingParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\InjectParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\MaskParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\PreparedParser;
use TRegx\CleanRegex\Internal\Prepared\Prepare;
use TRegx\CleanRegex\Internal\Prepared\Template\NoTemplate;
use TRegx\CleanRegex\PatternInterface;
use TRegx\CleanRegex\Template;

class PatternBuilder implements PcrePatternBuilder
{
    /** @var bool */
    private $pcre;

    public function __construct(bool $pcre)
    {
        $this->pcre = $pcre;
    }

    public function pcre(): PcrePatternBuilder
    {
        return new self(true);
    }

    public function bind(string $input, array $values, string $flags = null): PatternInterface
    {
        return Prepare::build(new BindingParser($input, $values, new NoTemplate()), $this->pcre, $flags ?? '');
    }

    public function inject(string $input, array $values, string $flags = null): PatternInterface
    {
        return Prepare::build(new InjectParser($input, $values, new NoTemplate()), $this->pcre, $flags ?? '');
    }

    public function prepare(array $input, string $flags = null): PatternInterface
    {
        return Prepare::build(new PreparedParser($input), $this->pcre, $flags ?? '');
    }

    public function mask(string $pattern, array $tokens, string $flags = null): PatternInterface
    {
        return Prepare::build(new MaskParser($pattern, $tokens), $this->pcre, $flags ?? '');
    }

    public function template(string $pattern, string $flags = null): Template
    {
        return new Template($pattern, $flags ?? '', $this->pcre);
    }
}
