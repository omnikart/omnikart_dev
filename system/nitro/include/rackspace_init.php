<?php
$connection = new \OpenCloud\Rackspace(RACKSPACE_US, array('username' => getNitroPersistence('CDNRackspace.Username'), 'apiKey' => getNitroPersistence('CDNRackspace.APIKey') ));
$objstore = $connection->ObjectStore('cloudFiles', getNitroPersistence('CDNRackspace.ServerRegion'), "publicURL");