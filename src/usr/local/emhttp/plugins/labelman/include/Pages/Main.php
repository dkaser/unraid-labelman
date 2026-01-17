<?php

namespace EDACerton\Labelman;

use EDACerton\PluginUtils\Translator;

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

if ( ! defined(__NAMESPACE__ . '\PLUGIN_ROOT') || ! defined(__NAMESPACE__ . '\PLUGIN_NAME')) {
    throw new \RuntimeException("Common file not loaded.");
}
$tr    = $tr ?? new Translator(PLUGIN_ROOT);
$utils = new Utils(PLUGIN_NAME);

$sysInfo = new SystemInfo();

/** @var array<string,bool> $serviceEnabled */
$serviceEnabled = array();

$services = Utils::getServices();

foreach ($services as $k => $service) {
    try {
        $serviceEnabled[$service] = $service::serviceExists($sysInfo);
    } catch (\Throwable $e) {
        unset($services[$k]);
        $utils->logmsg("Error checking if {$service} exists: {$e->getMessage()}");
    }
}

?>
<script src="/webGui/javascript/jquery.tablesorter.widgets.js"></script>
<link type="text/css" rel="stylesheet" href="/plugins/labelman/style.css">

<table id='statusTable' class="unraid statusTable tablesorter">
    <thead>
        <tr>
            <th><?= $tr->tr("container"); ?></th>
            <?php
                foreach ($services as $k => $service) {
                    try {
                        if ($serviceEnabled[$service]) {
                            echo("<th class='filter-select filter-match'>{$service::getDisplayName()} " . $tr->tr("enabled") . "</th>");
                        }
                    } catch (\Throwable $e) {
                        unset($services[$k]);
                        $utils->logmsg("Error checking if {$service} exists: {$e->getMessage()}");
                    }
                }
?>
            <th class="filter-false"><?= $tr->tr("actions"); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach ($sysInfo->ManagedContainers as $c) {
                $configFile = realpath("/boot/config/plugins/dockerMan/templates-user/my-{$c}.xml");
                if ( ! $configFile || ! str_starts_with($configFile, "/boot/config/plugins/dockerMan/templates-user/my-")) {
                    continue;
                }
                $container    = new Container($configFile);
                $containerURL = urlencode($c);

                $row = "<tr><td>{$c}</td>";

                foreach ($services as $service) {
                    try {
                        if ($serviceEnabled[$service]) {
                            $row .= "<td>" . ($container->Services[$service]->isEnabled() ? $tr->tr("yes") : $tr->tr("no")) . "</td>";
                        }
                    } catch (\Throwable $e) {
                        $utils->logmsg("Error checking if {$service} enabled: {$e->getMessage()}");
                        $row .= "<td>Unknown</td>";
                    }
                }

                $row .= "<td><a href='/Settings/Labelman?container={$containerURL}'>" . $tr->tr("edit") . "</a></td></tr>";

                echo($row);
            }
?>
    </tbody>
</table>
<button type="button" class="reset"><?= $tr->tr("reset_filters"); ?></button>

<script>
$(function() {
    $('#statusTable').tablesorter({
      widthFixed : true,
      sortList: [[0,0]],
      sortAppend: [[0,0]],
      widgets: ['stickyHeaders','filter','zebra'],
      widgetOptions: {
        // on black and white, offset is height of #menu
        // on azure and gray, offset is height of #header
        stickyHeaders_offset: ($('#menu').height() < 50) ? $('#menu').height() : $('#header').height(),
        filter_columnFilters: true,
        filter_reset: '.reset',
        filter_liveSearch: true,

        zebra: ["normal-row","alt-row"]
      }
    });
});

</script>

<?= $utils->getLicenseBlock(); ?>
