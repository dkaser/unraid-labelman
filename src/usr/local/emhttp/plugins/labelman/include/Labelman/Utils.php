<?php

namespace Labelman;

class Utils
{
    public static function make_option(bool $selected, string $value, string $text, string $extra = ""): string
    {
        return "<option value='{$value}'" . ($selected ? " selected" : "") . (strlen($extra) ? " {$extra}" : "") . ">{$text}</option>";
    }

    public static function auto_v(string $file): string
    {
        global $docroot;
        $path = $docroot . $file;
        clearstatcache(true, $path);
        $time    = file_exists($path) ? filemtime($path) : 'autov_fileDoesntExist';
        $newFile = "{$file}?v=" . $time;

        return $newFile;
    }

    /**
     * @return array<string>
     */
    public static function run_command(string $command, bool $alwaysShow = false, bool $show = true): array
    {
        $output = array();
        $retval = null;
        if ($show) {
            self::logmsg("exec: {$command}");
        }
        exec("{$command} 2>&1", $output, $retval);

        if (($retval != 0) || $alwaysShow) {
            self::logmsg("Command returned {$retval}" . PHP_EOL . implode(PHP_EOL, $output));
        }

        return $output;
    }

    public static function logmsg(string $message, bool $debug = false): void
    {
        if ($debug) {
            if (defined("TAILSCALE_TRUNK")) {
                $message = "DEBUG: " . $message;
            } else {
                return;
            }
        }
        $timestamp = date('Y/m/d H:i:s');
        $filename  = basename($_SERVER['PHP_SELF']);
        file_put_contents("/var/log/labelman.log", "{$timestamp} {$filename}: {$message}" . PHP_EOL, FILE_APPEND);
    }

    public static function apply_label(\SimpleXMLElement &$config, string $label, string $value, string $default = ""): void
    {
        $remove = $value == $default;
        $found = false;

        foreach($config->Config as $c) {
            $attributes = $c->attributes();
            if($attributes['Type'] == "Label") {
                if($attributes['Target'] == $label) {
                    if($remove) {
                        unset($c[0]);
                    } else {
                        $c[0] = $value;
                    }

                    $found = true;
                    break;
                }
            }
        }

        if(!$found && !$remove) {
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
