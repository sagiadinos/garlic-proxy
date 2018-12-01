# basil-proxy
Basil is a Proxy Web application for Digital Signage SMIL Player

## Description
Sometimes you want to use several SMIL-Player in a internal network which shares same or partialy same content. 
If you want to save bandwith or it is limited you can use Basil-Proxy.
Basil is lightweight and do not need a database. It will run even on small IoT devices.

## Requirements
* PHP 5.6+
* php-curl
* Webserver (Apache, nginx etc)
* To use full possible caching capabilities the corresponding Digital Signage CMS should create additional md5 files for every media

## Theoretical concept:
Basil-Proxy acts as an intermediary between SMIL players and a Digital Signage CMS.

The player are connecting/registering with Basil-Proxy.
Basil-Proxy connects via cronjob with the CMS, downloads the SMIL-Indexes for all registered player and selects which media must be downloaded.
It helps to reduce bandwith significantly.

## Currently Implemented Features
* registering of SMIL Player
* testing environment
* downloading SMIL-Index from Basil-Proxy
* downloading of SMIL-Index from CMS
* parsing SMIL
* downloading media

## ToDo Features for later versions
* check and download media and firmware updates if neccesary

## Status
Basil-Proxy is feature complete in backend.
currently I write unit tests for refactoring and testing
