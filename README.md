
# A Laravel Nova package to expand rows in index view

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sprigs/expandable-row.svg?style=flat-square)](https://packagist.org/packages/sprigs/expandable-row)
[![Total Downloads](https://img.shields.io/packagist/dt/sprigs/expandable-row.svg?style=flat-square)](https://packagist.org/packages/sprigs/expandable-row)

This package moves the preview data from the modal to an expandable row. Other details can be expanded aswell.

![Preview of a row expanding](/docs/3-expandable-row.gif)


## Installation

You can install the nova tool in to a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require sprigs/expandable-row
```

## Usage

To show the expandable row, use it jus as any other field.

```php
// in app/Nova/YourResource.php

use Sprigs\ExpandableRow\ExpandableRow;

// ...

public function fields(NovaRequest $request)
    {
        return [      
            // ... your other fields
            
            ExpandableRow::make('')
   ];
}
```

## Methods

By default ExpandableRow creates a field that only shows on index.
You can change the column name by passing it as a parameter when initializing:

### Column header
```
    ExpandableRow::make('Custom header');
```


### Toggle label
By default, the label next to the toggle arrow is set to 'Details'. It can be changed by calling the `toggleLabel()` method;
```
    ExpandableRow::make('')->toggleLabel('Show more')
```

### Move to actions
To show the dropdown as the first option in the actions column, use the `moveToActions()` method:
```
    ExpandableRow::make('')->moveToActions();
```

![Preview of a row expanding](/docs/4-expandable-row.gif)

_Note: using the `moveToActions()` method, will ignore the `toggleLabel` as only the arrow will be shown_

### Showing the data
When initializing the ExpandableRow, by default, it shows the fields that would show in the Preview modal (any field that has the `showOnPreview()` method called).

Or it you can pass an `$array` to `expandingData()` of items of the following structure:

```php
ExpandableRow::make('')
    ->expandingData(
        [
            [
                'name' => 'Custom row',
                'value' => 'Single string', // Can be a string or array of strings
            ],
            [
                'name' => 'Title of another item',
                'value' => [
                    'Array of strings',
                    'That gets displayed as a list',
                    'Keeping the stule of the preview panel tags'],
            ],
        ]
    );
```



<!-- ### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently. -->

<!-- ## Contributing

Please see [CONTRIBUTING](https://github.com/sprigs/.github/blob/main/CONTRIBUTING.md) for details. -->

<!-- ## Credits

- [Contributor](https://github.com/contributor) -->

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.