<?php

declare(strict_types=1);

namespace Yiisoft\Form\Field;

use Yiisoft\Form\Field\Base\AbstractDateTimeField;

/**
 * @link https://html.spec.whatwg.org/multipage/input.html#local-date-and-time-state-(type=datetime-local)
 */
final class DateTimeLocal extends AbstractDateTimeField
{
    protected function getInputType(): string
    {
        return 'datetime-local';
    }
}