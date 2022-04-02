<?php

declare(strict_types=1);

namespace Yiisoft\Form\Field\Part;

use InvalidArgumentException;
use Stringable;
use Yiisoft\Form\Field\Base\FormAttributeTrait;
use Yiisoft\Html\Html;
use Yiisoft\Widget\Widget;

final class Hint extends Widget
{
    use FormAttributeTrait;

    /**
     * @psalm-var non-empty-string
     */
    private string $tag = 'div';
    private array $tagAttributes = [];

    private string|Stringable|null $content = null;

    private bool $encode = true;

    /**
     * Set the container tag name for the hint.
     *
     * @param string $tag Container tag name.
     *
     * @return static
     */
    public function tag(string $tag): self
    {
        if ($tag === '') {
            throw new InvalidArgumentException('Tag name cannot be empty.');
        }

        $new = clone $this;
        $new->tag = $tag;
        return $new;
    }

    /**
     * @return static
     */
    public function tagAttributes(array $attributes): self
    {
        $new = clone $this;
        $new->tagAttributes = $attributes;
        return $new;
    }

    /**
     * @return static
     */
    public function content(string|Stringable|null $content): self
    {
        $new = clone $this;
        $new->content = $content;
        return $new;
    }

    /**
     * Whether content should be HTML-encoded.
     *
     * @param bool $value
     *
     * @return static
     */
    public function encode(bool $value): self
    {
        $new = clone $this;
        $new->encode = $value;
        return $new;
    }

    protected function run(): string
    {
        $content = $this->content ?? $this->getAttributeHint();

        return $content === ''
            ? ''
            : Html::tag($this->tag, $content, $this->tagAttributes)
                ->encode($this->encode)
                ->render();
    }
}