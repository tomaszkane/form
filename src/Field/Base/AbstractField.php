<?php

declare(strict_types=1);

namespace Yiisoft\Form\Field\Base;

use InvalidArgumentException;
use Yiisoft\Html\Tag\CustomTag;
use Yiisoft\Widget\Widget;

abstract class AbstractField extends Widget
{
    /**
     * @psalm-var non-empty-string
     */
    protected string $containerTag = 'div';
    protected array $containerTagAttributes = [];
    protected bool $useContainer = true;

    private bool $isStartedByBegin = false;

    final public function containerTag(string $tag): static
    {
        if ($tag === '') {
            throw new InvalidArgumentException('Tag name cannot be empty.');
        }

        $new = clone $this;
        $new->containerTag = $tag;
        return $new;
    }

    final public function containerTagAttributes(array $attributes): static
    {
        $new = clone $this;
        $new->containerTagAttributes = $attributes;
        return $new;
    }

    final public function useContainer(bool $use): static
    {
        $new = clone $this;
        $new->useContainer = $use;
        return $new;
    }

    final public function begin(): ?string
    {
        parent::begin();
        $this->isStartedByBegin = true;

        $content = $this->generateBeginContent();

        if (!$this->useContainer) {
            return $content;
        }

        $containerTag = CustomTag::name($this->containerTag);
        if ($this->containerTagAttributes !== []) {
            $containerTag = $containerTag->attributes($this->containerTagAttributes);
        }

        return $containerTag->open()
            . ($content === '' ? '' : (PHP_EOL . $content))
            . PHP_EOL;
    }

    final protected function run(): string
    {
        if ($this->isStartedByBegin) {
            $this->isStartedByBegin = false;
            return $this->renderEnd();
        }

        $content = $this->generateContent();
        if ($content === null) {
            return '';
        }

        if (!$this->useContainer) {
            return $content;
        }

        $containerTag = CustomTag::name($this->containerTag);
        if ($this->containerTagAttributes !== []) {
            $containerTag = $containerTag->attributes($this->containerTagAttributes);
        }

        return $containerTag->open()
            . ($content === '' ? '' : (PHP_EOL . $content))
            . PHP_EOL
            . $containerTag->close();
    }

    abstract protected function generateContent(): ?string;

    protected function generateBeginContent(): string
    {
        return '';
    }

    protected function generateEndContent(): string
    {
        return '';
    }

    private function renderEnd(): string
    {
        $content = $this->generateEndContent();

        if (!$this->useContainer) {
            return $content;
        }

        $containerTag = CustomTag::name($this->containerTag);

        return
            "\n" .
            ($content !== '' ? $content . "\n" : '')
            . $containerTag->close();
    }
}
