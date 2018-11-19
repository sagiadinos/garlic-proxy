# basil-proxy
Basil is a Proxy Web application for Digital Signage SMIL Player

If you want to use several media player in a internal network, but have limited bandwith you can use basil.

Basil-proxy is created without a framework and do not need a database. It should run on small IoT Devices.

Minimum: PHP 5.6 

Theoretical concept:
Basil-Proxy acts as an intermediary between the players and the CMS.

The player are connecting/registering with Basil.
Basil connects via cronjob with the CMS, downloads the SMIL-Indexes for all registered player and selects which media must be downloaded.
It can help to reduce bandwith.


Currently the software is in a very early stage and not feature complete.
