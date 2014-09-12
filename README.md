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

### Configuration

It is possible to configure the smartcrud services on multiple places.
 e.g. For the list service, the configuration will be merged as followed:

 - phpro-smartcrud-service['default']
 - phpro-smartcrud-service['default-list']
 - service_manager['my-custom-smartcrud-service']['default']
 - service_manager['my-custom-smartcrud-service']['list']

This means it is possible to specify some default configuration and overwrite it for custom services.

### SmartCrud Configuration

### Gateways

It is possible to configure multiple data-source gateways.
Those gateways are being used by the services to load and save the data.

```php
'phpro-smartcrud-gateway' => array(
    'smartcrud.gateway.doctrine.default' => array(
        'type' => 'PhproSmartCrud\Gateway\DoctrineCrudGateway',
        'options' => array(
            'object_manager' => 'doctrine.documentmanager.odm_default',
        ),
    )
),
```

#### Services

```php
'phpro-smartcrud-service' => array(
    'SmartCrudServiceKey' => array(
        'default' => array(
            'gateway' => 'smartcrud.gateway.doctrine.default',
            'entity-class' => 'entity-key',
            'form' => 'form-key',
            'listeners' => []
        ),
    ),
),
```

##### List Service

The list service has some extra configurable options.
It is required to specify a paginator and it is optional to add a query provider to filter / sort lists.

**Paginator**

```php
'phpro-smartcrud-service' => array(
    'default-list' => array(
        'options' => array(
            'paginator' => array(
                'adapter_class' => '\Zend\Paginator\Adapter\ArrayAdapter',
                'page_size' => 50,
                'page_key' => 'page',
            ),
        )
    )
),
```

**Query Provider**

A query provider implements the `QueryProviderInterface`.
It is possible to add your own query provider to a List Service:

```php
'phpro-smartcrud-service' => array(
    'default-list' => array(
        'options' => array(
            'query-provider' => 'servicemanager.key.my-custom-query-provider',
        ),
    )
),
```

##### listeners:

Array of service manager keys, which return EventListenerAggregateInterface. These listeners can be used listen to SmartCrud events on entities.

###### Available SmartCrud events:

```php
CrudEvent::BEFORE_LIST
CrudEvent::AFTER_LIST
CrudEvent::BEFORE_DATA_VALIDATION
CrudEvent::BEFORE_CREATE
CrudEvent::AFTER_CREATE
CrudEvent::INVALID_CREATE
CrudEvent::BEFORE_READ
CrudEvent::AFTER_READ
CrudEvent::BEFORE_UPDATE
CrudEvent::AFTER_UPDATE
CrudEvent::INVALID_UPDATE
CrudEvent::BEFORE_DELETE
CrudEvent::AFTER_DELETE
CrudEvent::INVALID_DELETE
CrudEvent::BEFORE_VALIDATE
CrudEvent::AFTER_VALIDATE
CrudEvent::FORM_READY
```

#### Controllers

```php
'phpro-smartcrud-controller' => array(
    'SmartCrudControllerKey' => array(
        'controller' => 'Phpro\SmartCrud\Controller\CrudController',
        'identifier-name' => 'identifier',
        'smart-service' => 'SmartCrudServiceKey',
        'view-builder' => 'Phpro\SmartCrud\View\Model\ViewModelBuilder',
        'view-path' => 'path',
    ),
),
```




More coming soon!