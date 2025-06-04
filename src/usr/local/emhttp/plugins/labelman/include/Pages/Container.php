<?php

namespace EDACerton\Labelman;

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
$utils = new Utils(PLUGIN_NAME);

if ( ! isset($_GET['container'])) {
    throw new \Exception("No container specified");
}

$containerName = $_GET['container'];
if ( ! is_string($containerName)) {
    throw new \Exception("Invalid container name");
}
$configFile = realpath("/boot/config/plugins/dockerMan/templates-user/my-{$containerName}.xml");
if ( ! $configFile || ! str_starts_with($configFile, "/boot/config/plugins/dockerMan/templates-user/my-")) {
    throw new \Exception("Bad Request");
}

$container = new Container($configFile);
$sysInfo   = new SystemInfo();
?>
<link type="text/css" rel="stylesheet" href="<?= Utils::auto_v('/webGui/styles/jquery.switchbutton.css');?>">
<span class="status vhshift"><input type="checkbox" class="advancedview"></span>

<h2><?= $containerName; ?></h2>

<form action="/plugins/labelman/update.php" method="POST" target="progressFrame">
<input type="hidden" name="containerName" value="<?= urlencode($containerName); ?>">

<?php

$services = Utils::getServices();
foreach ($services as $service) {
    ob_start();
    try {
        if ($service::serviceExists($sysInfo)) {
            echo "<div class='title'><span class='left'>{$service::getDisplayName()}</span></div>";
            $container->Services[$service]->display($container);
        }
    } catch (\Throwable $e) {
        $utils->logmsg("Error displaying {$service}: {$e->getMessage()}");
        ob_clean();
    }
    ob_end_flush();
}
?>

<div class='title'><span class='left'>Save Settings</span></div>

<dl>
    <dt>&nbsp;</dt>
    <dd>
        <input type="submit" name="#apply" value="Apply"><input type="button" id="DONE" value="Back" onclick="window.location.href='/Settings/Labelman'">
    </dd>
</dl>
</form>

<script src="<?= Utils::auto_v('/webGui/javascript/jquery.switchbutton.js');?>"></script>
<script>
    $(function() {
        if ($.cookie('labelman_view_mode') == 'advanced') {
            $('.advanced').show();
        } else {
            $('.advanced').hide();
        }

        $('.advancedview').switchButton({
            labels_placement: "left",
            on_label: "Advanced",
            off_label: "Basic",
            checked: $.cookie('labelman_view_mode') == 'advanced'
        });
        $('.advancedview').change(function(){
            if($('.advancedview').is(':checked')) {
                $('.advanced').show('slow');
            } else {
                $('.advanced').hide('slow');
            }
            $.cookie('labelman_view_mode', $('.advancedview').is(':checked') ? 'advanced' : 'basic', {expires:3650});
        });
    });
</script>