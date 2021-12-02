# garlic-proxy
Garlic-proxy is a Reverse Proxy for [Digital Signage Player](https://smil-control.com/magazine/what-is-a-digital-signage-player/) based on SMIL

## Why a special proxy solution?

Sometimes you need to operate a group of Digital Signage Player (e.g. in a super market or shopping mall), but you have only a slow internet connection.
In some cases a common proxy solution like [mod_proxy](https://httpd.apache.org/docs/2.4/mod/mod_proxy.html) could help.
But there are a cases where that's not enough.

### Garlic-Proxy can be useful if...

* you have same files with different names e.g. for reporting or security reasons. A standard proxy cannot differentiate in this case. Basil can!
* you want to update content at specific times (e.g. only during the night), but your clients do not support this.
* you generally need more control over the content update process 
* you want to prevent e.g. for security reason, that the clients connecting direct to internet
* you need to access with a protocol your clients not support. e.g. https/ftps 

## Description
Garlic-Proxy act as a [reverse proxy](https://en.wikipedia.org/wiki/Reverse_proxy)
If you want to save bandwith or it is limited you can use this software.
The software is lightweight and do not need a database backend. It will run even on small IoT devices.

## Requirements
* PHP 5.6+
* php-curl
* Webserver (Apache, nginx etc)
* To use full possible caching capabilities the corresponding Digital Signage CMS should create additional md5 files for every media

## Currently Implemented Features
* registering of SMIL Player
* testing environment
* downloading SMIL-Index from garlic-proxy
* downloading of SMIL-Index from CMS
* parsing SMIL
* downloading media
* Web GUI which can update indexes

## Status
Garlic-proxy is feature complete.
