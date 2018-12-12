# basil-proxy
Basil is a Proxy Web application for Digital Signage SMIL Player

## Why a special proxy solution?

Sometimes you need to operate a group of Digital Signage Player (e.g. in a supermarket), but you have only a slow internet connection.
In some cases a common proxy solution like [mod_proxy](https://httpd.apache.org/docs/2.4/mod/mod_proxy.html) could help.
But there are a few cases where that's not enough.

### Basil can be useful if..

* you have same files with different names e.g. for reporting or security reasons. A standard proxy cannot differentiate in this case. Basil can! This results in redundancy not neccessary downloads.
* you want to update content at specific times (e.g. only during the night), but your clients do not support this.
* you don't want the clients connecting direct to internet 
* you generally need more control over the content update process 

## Description
Basil act as a [reverse proxy](https://en.wikipedia.org/wiki/Reverse_proxy)
If you want to save bandwith or it is limited you can use Basil-Proxy.
Basil is lightweight and do not need a database. It will run even on small IoT devices.

## Requirements
* PHP 5.6+
* php-curl
* Webserver (Apache, nginx etc)
* To use full possible caching capabilities the corresponding Digital Signage CMS should create additional md5 files for every media

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
