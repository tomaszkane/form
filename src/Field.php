<?php

declare(strict_types=1);

namespace Yiisoft\Form;

use RuntimeException;
use Yiisoft\Form\Field\Checkbox;
use Yiisoft\Form\Field\Date;
use Yiisoft\Form\Field\Hidden;
use Yiisoft\Form\Field\Part\Error;
use Yiisoft\Form\Field\Part\Hint;
use Yiisoft\Form\Field\Part\Label;
use Yiisoft\Form\Field\Text;

use function array_key_exists;

final class Field
{
    /**
     * @psalm-var array<string,array>
     */
    private static array $configs = [
        'default' => [],
    ];

    private static string $defaultConfigName = 'default';

    /**
     * @psalm-var array<string,FieldFactory>
     */
    private static array $factories = [];

    /**
     * @psalm-param array<string,array> $configs
     */
    public static function initialize(array $configs = [], string $defaultConfigName = 'default'): void
    {
        self::$configs = array_merge(self::$configs, $configs);
        self::$defaultConfigName = $defaultConfigName;
    }

    public static function checkbox(FormModelInterface $formModel, string $attribute, array $config = []): Checkbox
    {
        return self::getFactory()->checkbox($formModel, $attribute, $config);
    }

    public static function date(FormModelInterface $formModel, string $attribute, array $config = []): Date
    {
        return self::getFactory()->date($formModel, $attribute, $config);
    }

    public static function hidden(FormModelInterface $formModel, string $attribute, array $config = []): Hidden
    {
        return self::getFactory()->hidden($formModel, $attribute, $config);
    }

    public static function text(FormModelInterface $formModel, string $attribute, array $config = []): Text
    {
        return self::getFactory()->text($formModel, $attribute, $config);
    }

    public static function label(FormModelInterface $formModel, string $attribute, array $config = []): Label
    {
        return self::getFactory()->label($formModel, $attribute, $config);
    }

    public static function hint(FormModelInterface $formModel, string $attribute, array $config = []): Hint
    {
        return self::getFactory()->hint($formModel, $attribute, $config);
    }

    public static function error(FormModelInterface $formModel, string $attribute, array $config = []): Error
    {
        return self::getFactory()->error($formModel, $attribute, $config);
    }

    /**
     * @psalm-template T
     * @psalm-param class-string<T> $class
     * @psalm-return T
     */
    public function field(string $class, FormModelInterface $formModel, string $attribute, array $config = []): object
    {
        return self::getFactory()->field($class, $formModel, $attribute, $config);
    }

    public static function getFactory(?string $name = null): FieldFactory
    {
        $name = $name ?? self::$defaultConfigName;

        if (!array_key_exists($name, self::$factories)) {
            if (!array_key_exists($name, self::$configs)) {
                throw new RuntimeException(
                    sprintf('Configuration with name "%s" not found.', $name)
                );
            }

            /** @psalm-suppress MixedArgument */
            self::$factories[$name] = new FieldFactory(...self::$configs[$name]);
        }

        return self::$factories[$name];
    }
}
