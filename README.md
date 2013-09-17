Zend SmartCrud [WIP]
====================
Master: [![Build Status](https://secure.travis-ci.org/veewee/PhproSmartCrud.png?branch=master)](http://travis-ci.org/veewee/PhproSmartCrud)

Module providing a SmartCrud for working with the [Zend Framework 2](https://github.com/zendframework/zf2) MVC
layer.


Todo
============
* feature: The router should have a variabele 'listeners' which configures the crudservice
* improvement: Implement list view (Based on form hydrator)
* improvement: Implement read view (Based on form hydrator)
* improvement: Add better redirect funcitonality to the RedirectModel
* feature: Add extra triggers while creating the list (to inject filtering, sorting, pagination)
* feature: Use iterator on lists to prevent memory issues?
* improvement: improve view model / strategy / renderer logica
* feature: Create CLI generator with Symfony CLI
* feature: Create Zend Console routers which map to the symfony cli app.
* feature: implement zend db gateway
* documentation: Create some nice documentation!
* refactor: Make sure that the get/setParams on the event is linked to a parameter in the parameterservice
* refactor: use AbstractListenerAggregate for the listeners instead of (DRY)
* refactor: refactor the mock methods in the php specs (to make sure no methods are repeated)
* feature: implement other db gateways: mongoDB, propel, API's, ...


Installation
============
TODO ...
