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

class Utils extends \EDACerton\PluginUtils\Utils
{
    /**
     * @return array<string>
     */
    public static function getServices(): array
    {
        return [
            'EDACerton\Labelman\TSDProxy',
            'EDACerton\Labelman\SWAG'
        ];
    }

    /**
     * @return array<string>
     */
    public static function run_command(string $command, bool $alwaysShow = false, bool $show = true): array
    {
        if ( ! defined(__NAMESPACE__ . '\PLUGIN_ROOT') || ! defined(__NAMESPACE__ . '\PLUGIN_NAME')) {
            throw new \RuntimeException("Common file not loaded.");
        }
        $utils = new Utils(PLUGIN_NAME);

        $output = array();
        $retval = null;
        if ($show) {
            $utils->logmsg("exec: {$command}");
        }
        exec("{$command} 2>&1", $output, $retval);

        if (($retval != 0) || $alwaysShow) {
            $utils->logmsg("Command returned {$retval}" . PHP_EOL . implode(PHP_EOL, $output));
        }

        return $output;
    }

    public static function apply_label(\SimpleXMLElement &$config, string $label, string $value, string $default = ""): void
    {
        $remove = $value == $default;
        $found  = false;

        foreach ($config->Config as $c) {
            $attributes = $c->attributes();
            if ($attributes['Type'] == "Label") {
                if ($attributes['Target'] == $label) {
                    if ($remove) {
                        unset($c[0]);
                    } else {
                        /** @phpstan-ignore offsetAssign.valueType */
                        $c[0] = $value;
                    }

                    $found = true;
                    break;
                }
            }
        }

        if ( ! $found && ! $remove) {
            $new = $config->addChild("Config");
            $new->addAttribute("Name", $label);
            $new->addAttribute("Target", $label);
            $new->addAttribute("Default", "");
            $new->addAttribute("Mode", "");
            $new->addAttribute("Description", "Added by Labelman");
            $new->addAttribute("Type", "Label");
            $new->addAttribute("Display", "advanced");
            $new->addAttribute("Required", "false");
            $new->addAttribute("Mask", "false");
            $new[0] = $value;
        }
    }
}
