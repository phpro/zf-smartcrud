# SmartCrud for Zend Framework
Master: [![Build Status](https://secure.travis-ci.org/phpro/zf-smartcrud.png?branch=master)](http://travis-ci.org/phpro/zf-smartcrud)
Dev-Master: [![Build Status](https://secure.travis-ci.org/phpro/zf-smartcrud.png?branch=0.1-dev)](http://travis-ci.org/phpro/zf-smartcrud)

Module providing a SmartCrud for working with the [Zend Framework 2](https://github.com/zendframework/zf2) MVC
layer.

## Installation

Installation of this module uses composer. For composer documentation, please refer to
[getcomposer.org](http://getcomposer.org/).

```sh
php composer.phar require phpro/zf-smartcrud
# (When asked for a version, type `dev-master`)
```

Then add `Phpro\SmartCrud` to your `config/application.config.php`.

Installation without composer is not officially supported and requires you to manually install all dependencies
that are listed in `composer.json`

## Documentation

### SmartCrud Configuration

#### Services

```sh
return array(
    'phpro-smartcrud-service' => array(
        'SmartCrudServiceKey' => array(
            'default' => array(
                'gateway' => 'smartcrud.gateway.doctrine.tenant',
                'entity-class' => 'entity-key',
                'form' => 'form-key',
                'listeners' => []
            ),
        ),
    ),
);
```

##### listeners:

Array of service manager keys, which return EventListenerAggregateInterface. These listeners can be used listen to SmartCrud events on entities.

###### Available SmartCrud events:

```sh
BEFORE_LIST
AFTER_LIST
BEFORE_DATA_VALIDATION
BEFORE_CREATE
AFTER_CREATE
INVALID_CREATE
BEFORE_READ
AFTER_READ
BEFORE_UPDATE
AFTER_UPDATE
INVALID_UPDATE
BEFORE_DELETE
AFTER_DELETE
INVALID_DELETE
BEFORE_VALIDATE
AFTER_VALIDATE
FORM_READY
```

#### Controllers

```sh
return array(
    'phpro-smartcrud-controller' => array(
        'SmartCrudControllerKey' => array(
            'controller' => 'Phpro\SmartCrud\Controller\CrudController',
            'identifier-name' => 'identifier',
            'smart-service' => 'SmartCrudServiceKey',
            'view-builder' => 'Phpro\SmartCrud\View\Model\ViewModelBuilder',
            'view-path' => 'path',
        ),
    ),
);
```




More coming soon!