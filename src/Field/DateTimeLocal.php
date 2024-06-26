<?php

declare(strict_types=1);

namespace Yiisoft\Form\Field;

use DateTimeInterface;
use Yiisoft\Form\Field\Base\DateTimeInputField;

/**
 * Represents `<input>` element of type "datetime-local" are let the user easily enter both a date and a time, including
 * the year, month, and day as well as the time in hours and minutes.
 *
 * @link https://html.spec.whatwg.org/multipage/input.html#local-date-and-time-state-(type=datetime-local)
 * @link https://developer.mozilla.org/docs/Web/HTML/Element/input/datetime-local
 */
final class DateTimeLocal extends DateTimeInputField
{
    protected function getInputType(): string
    {
        return 'datetime-local';
    }

    protected function formatDateTime(DateTimeInterface $value): string
    {
        return $value->format('Y-m-d\TH:i');
    }
}
