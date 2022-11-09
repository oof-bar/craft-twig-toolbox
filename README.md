# Twig Toolbox for Craft CMS

_The simplest way to keep business logic out of your Twig templates._

All this plugin does is registers a Twig extension—everything else is up to you! With Twig Toolbox, you can finally start to clean up the mess that accumulates at the top of every template…

## Usage

Twig Toolbox lets you inject custom filters, functions, globals, and tests into Craft’s template engine.

To get started, install the plugin from the Craft Plugin Store, or use Composer:

```bash
composer require oof-bar/craft-twig-toolbox
php craft plugins/install twig-toolbox
```

Then, copy this into `config/twig-toolbox.php`:

```php
<?php return [
    'filters' => [],
    'functions' => [],
    'globals' => [],
    'tests' => [],
];
```

> :bulb: If you’re in the mood for a challenge, writing [your own Twig extension](https://craftcms.com/docs/4.x/extend/extending-twig.html) is a great way to get familiar with custom module development.

Let’s look at some examples of how each language feature can be used.

### Filters

&rarr; Documentation on [Twig Filters](https://twig.symfony.com/doc/3.x/templates.html#filters)

The `filters` key should contain alphanumeric keys and [functions](https://www.php.net/manual/en/functions.user-defined.php) or [callables](https://www.php.net/manual/en/language.types.callable.php) as values. Filters _always_ have at least one argument!

#### Example

```php
<?php return [
    'filters' => [
        'salePrice' => function(float $price): float {
            return $price * 0.9;
        },
    ],
];
```

```twig
<div class="product">
    <div class="sku">{{ product.sku }}</div>
    <span class="price price--default">{{ product.price | money }}</span>
    <span class="price price--members">{{ product.price | salePrice | money }}</span>
</div>
```

### Functions

&rarr; Documentation on [Twig Functions](https://twig.symfony.com/doc/3.x/templates.html#functions)

Each item in the `functions` array should have an alphanumeric key, and a [function](https://www.php.net/manual/en/functions.user-defined.php) as its value. The function needs to declare expected arguments, and should explicitly return a value, if appropriate.

> :smile: You can use virtually any Craft API in a function!

#### Example:

```php
<?php return [
    'functions' => [
        'getDeals' => function(): array {
            return Entry::find()
                ->section('products')
                ->onSale(true)
                ->all();
        },
        'log' => function(mixed $message): void {
            Craft::getLogger()->log($message);
        },
    ],
];
```

```twig
{# Use to fetch data for a loop... #}
{% for deal in getDeals() %}
    <div class="deal">
        <div class="title">{{ deal.title }}</div>
        <div class="expiry">{{ deal.saleEndDate | date('short') }}</div>
    </div>
{% else %}
    {# ...or just do something silently! #}
    {% do log('We didn’t show a user any deals!') %}

    <div class="empty">Sorry, there is nothing on sale right now.</div>
{% endfor %}
```

### Globals

&rarr; Documentation on [Twig Globals](https://twig.symfony.com/doc/3.x/templates.html#globals)

Globals are best used sparingly, and only for simple values. The new [custom config](https://craftcms.com/docs/4.x/config#custom-settings) in Craft 4 is a near equivalent!

> :warning: Be mindful of what you are assigning to a global! Calling some Craft or Plugin APIs can cause a race condition as the system initializes.

#### Example:

```php
<?php return [
    'globals' => [
        'cutoffTime' => (new \DateTime)->modify('midnight'),
    ],
];
```

```twig
<h2>Prices are valid until {{ cutoffTime | date }}!</h2>
```

### Tests

&rarr; Documentation on [Twig Tests](https://twig.symfony.com/doc/3.x/templates.html#test-operator)

Tests are sort of like functions, but only available when using Twig’s `is` operator. They can do a lot to make your templates read more clearly—especially when the logic behind it is convoluted.

#### Examples

```php
<?php

use craft\elements\User;

return [
    'tests' => [
        'expensive' => function(float $value): bool {
            return $value > 10.0;
        },
        'member' => function(User $user): bool {
            return $user->isInGroup('members');
        },
    ],
];
```

```twig
{% set image = product.image.one() %}

{% if product.price is expensive %}
    <img src="{{ image.url }}" class="shiny-effect">
{% else %}
    <img src="{{ image.url }}">
{% endif %}
```

## Tips + Tricks

### Handling Types

Some of the functions above could be made even more flexible by accepting the special `mixed` type, or a [union type](https://www.php.net/manual/en/language.types.declarations.php#language.types.declarations.composite.union). For example, the `expensive` test could do some type checking and normalization like this...

```php

<?php

use craft\elements\Entry;

return [
    'tests' => [
        'expensive' => function(float|Entry $value): bool {
            // Normalize an Entry into a scalar field value:
            if ($value instanceof Entry) {
                $value = $value->price;
            }

            return $value > 10.0;
        },
    ],
];
```

...then, the template can read a bit more fluidly:

```twig
{% if product is not expensive %}
    <button>Buy two!</button>
{% endif %}
```

### Parameterization

`filters` and `functions` can take arguments to customize their behavior. If you find yourself adding a number of similar helpers, take a moment to consider how they could be consolidated and parameterized with one or more arguments.

### HTML Helpers

Consider how Twig can help you generate HTML, rather than trying to build it up yourself!

```php
<?php return [
    'functions' => [
        'bem' => function(string $base, array $flags): string {
            $classNames = [$base];

            // Create BEM-style class names, ignoring empty flags:
            foreach (array_filter($flags) as $flag) {
                $classNames[] = "{$base}--{$flag}";
            }

            return join(' ', array_unique($classNames));
        },
    ],
];
```

```twig
<div class="{{ bem('product', [
    product is expensive ? 'expensive' : null,
    currentUser is member ? 'member-pricing' : null,
]) }}">
    {{ product.title }}
</div>
```

## Help + Support

If you’re having trouble getting started, create an issue on GitHub and we’ll do our best to help out! If you need support on a project-specific task (like finding the appropriate Craft APIs), we recommend posing it to the [broader community](https://craftcms.com/community).
