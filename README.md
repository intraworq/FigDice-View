# Slim Framework FigDice View

[![Build Status](https://travis-ci.org/intraworq/FigDice-View.svg?branch=master)](https://travis-ci.org/intraworq/FigDice-View)

This is a Slim Framework view helper built on top of the FigDice templating component. You can use this component to create and render templates in your Slim Framework application.

## Install

Via [Composer](https://getcomposer.org/)

```bash
$ composer require intraworq/figdice-view
```

Requires Slim Framework 3 and PHP 5.6.0 or newer.

## Usage

### 1. Register FigDice SlimView component in Slim container
~~~~php
// Create container
$container = new \Slim\Container;

// Register component on container
$container['view'] = function ($c) {
  $view = new Slim\Views\FigDice('path/to/templates', $settings_array);
  // ...
  return $view;
};
~~~~

### 2. Render FigDice templates in Slim routes
~~~~php
// Create app
$app = new \Slim\App($container);

// Render FigDice template in route
$app->get('/hello/{name}', function ($request, $response, $args) {
    return $this->view->render($response, 'profile.html', [
        'name' => $args['name']
    ]);
});

// Run app
$app->run();
~~~~

## Testing

```bash
phpunit --coverage-text --configuration phpunit.xml.dist
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email github@intraworq.com instead of using the issue tracker.

## Credits

- [Bolek Tekielski](https://github.com/tboloo)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
