# DOM Handler

> DOM Handler provides handful methods to manipulate/browse a DOM Document.

[![License](https://poser.pugx.org/wsdltophp/domhandler/license)](https://packagist.org/packages/wsdltophp/domhandler)
[![Latest Stable Version](https://poser.pugx.org/wsdltophp/domhandler/version.png)](https://packagist.org/packages/wsdltophp/domhandler)
[![TeamCity build status](https://teamcity.mikael-delsol.fr/app/rest/builds/buildType:id:WsdlHandler_Build/statusIcon.svg)](https://github.com/WsdlToPhp/DomHandler)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/WsdlToPhp/DomHandler/badges/quality-score.png)](https://scrutinizer-ci.com/g/WsdlToPhp/DomHandler/)
[![Code Coverage](https://scrutinizer-ci.com/g/WsdlToPhp/DomHandler/badges/coverage.png)](https://scrutinizer-ci.com/g/WsdlToPhp/DomHandler/)
[![Total Downloads](https://poser.pugx.org/wsdltophp/domhandler/downloads)](https://packagist.org/packages/wsdltophp/domhandler)
[![StyleCI](https://styleci.io/repos/87977980/shield)](https://styleci.io/repos/87977980)
[![SymfonyInsight](https://insight.symfony.com/projects/93e73feb-60bc-4e99-a3ce-87e537c61fed/mini.svg)](https://insight.symfony.com/projects/93e73feb-60bc-4e99-a3ce-87e537c61fed)

DomHandler uses the [decorator design pattern](https://en.wikipedia.org/wiki/Decorator_pattern) in order to ease DOM handling.

The source code has been originally created into the [PackageGenerator](https://github.com/WsdlToPhp/PackageGenerator) project but it felt that it had the possibility to live by itself and to evolve independtly from the PackageGenerator project if necessary.

## Testing using [Docker](https://www.docker.com/)
Thanks to the [Docker image](https://hub.docker.com/r/splitbrain/phpfarm) of [phpfarm](https://github.com/fpoirotte/phpfarm), tests can be run locally under *any* PHP version using the cli:
- php-7.4

First of all, you need to create your container which you can do using [docker-compose](https://docs.docker.com/compose/) by running the below command line from the root directory of the project:
```bash
$ docker-compose up -d --build
```

You then have a container named `dom_handler` in which you can run `composer` commands and `php cli` commands such as:
```bash
# install deps in container (using update ensure it does use the composer.lock file if there is any)
$ docker exec -it dom_handler php-7.4 /usr/bin/composer update
# run tests in container
$ docker exec -it dom_handler php-7.4 -dmemory_limit=-1 vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Credits

Developers who helped on this project are listed in the [composer.json](composer.json#L8) file as `Contributor` and are:
- [phordijk](https://github.com/phordijk)

## FAQ

Feel free to [create an issue](https://github.com/WsdlToPhp/DomHandler/issues/new).

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

