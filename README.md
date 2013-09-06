Zend SmartCrud [WIP]
====================
Master: [![Build Status](https://secure.travis-ci.org/veewee/PhproSmartCrud.png?branch=master)](http://travis-ci.org/veewee/PhproSmartCrud)

Module providing a SmartCrud for working with the [Zend Framework 2](https://github.com/zendframework/zf2) MVC
layer.


Todo
============
* paramtersService: use routematch controller in combination with the controller loader from the servicemanager to load controller.
* refactor: Make getParameters() link to the right parameters + service in the crudservice classes
* The router should have a variabele 'listeners' which configures the crudservice
* Implement list view (Based on form hydrator)
* Implement read view (Based on form hydrator)
* Add better redirect funcitonality to the RedirectModel
* Add extra triggers while creating the list (to inject filtering, sorting, pagination)
* Use iterator on lists to prevent memory issues?
* improve view model / strategy / renderer logica
* Create CLI generator with Symfony CLI
* Create Zend Console routers which map to the symfony cli app.
* implement zend db gateway
* Create some nice documentation!

Installation
============
TODO ...
