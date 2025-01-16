<?php

namespace Labelman;

/*
    Copyright (C) 2025  Derek Kaser

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

$docroot = $docroot ?? $_SERVER['DOCUMENT_ROOT'] ?: '/usr/local/emhttp';
require_once "{$docroot}/plugins/labelman/include/common.php";

ob_implicit_flush(true);
readfile('/usr/local/emhttp/update.htm');
ob_implicit_flush(false);

if ( ! isset($_POST['containerName'])) {
    throw new \Exception("No container specified");
}

$containerName = $_POST['containerName'];
$configFile    = realpath("/boot/config/plugins/dockerMan/templates-user/my-{$containerName}.xml");
if ( ! $configFile || ! str_starts_with($configFile, "/boot/config/plugins/dockerMan/templates-user/my-")) {
    throw new \Exception("Bad Request");
}

$container = new Container($configFile);

$services = Utils::getServices();
try {
    foreach ($services as $service) {
        $container->Services[$service]->update($container->config, $_POST);
    }
} catch (\Throwable $e) {
    Utils::logmsg("Error updating {$service}: {$e->getMessage()}");
}

$xml = $container->config->asXML();
if ($xml) {
    $dom                     = new \DOMDocument('1.0');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput       = true;
    $dom->loadXML($xml);

    // Create config backup
    $time = time();
    copy($configFile, "/boot/config/plugins/labelman/{$containerName}.{$time}.xml");

    // Write config file
    file_put_contents($configFile, $dom->saveXML());

    Utils::run_command("/usr/local/emhttp/plugins/dynamix.docker.manager/scripts/rebuild_container '{$containerName}'");
}
