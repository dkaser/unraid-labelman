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

class swag implements Service
{
    public bool $enable = false;
    public bool $swag = false;
    public string $swag_address = "";
    public int $swag_port = 0;
    public string $swag_proto = "http";
    public string $swag_url = "";
    public string $swag_auth = "";

    public function __construct(Container $container)

    {
        $labels = $container->getLabels();

        if (($labels['swag'] ?? null) == "enable") {
            $this->swag = true;
        }
        if (isset($labels['swag_address'])) {
            $this->swag_address = $labels['swag_address'];
        }
        if (isset($labels['swag_port'])) {
            $this->swag_port = intval($labels['swag_port']);
        }
        if (isset($labels['swag_proto'])) {
            $this->swag_proto = $labels['swag_proto'];
        }
        if (isset($labels['swag_url'])) {
            $this->swag_url = $labels['swag_url'];
        }
        if (isset($labels['swag_auth'])) {
            $this->swag_auth = $labels['swag_auth'];
        }
    }

    public static function serviceExists(SystemInfo $info): bool
    {
        $tsdFound = false;
        foreach ($info->Images as $image) {
            if (str_contains(strtolower($image), "linuxserver/swag")) {
                $tsdFound = true;
                break;
            }
        }

        return $tsdFound;
    }

    public static function getDisplayName(): string
    {
        return "swag";
    }

    public function display(Container $container): void
    {
        include __DIR__ . "/Swag.inc";
    }

    /**
     * @param array<string,string> $post
     */
    public function update(\SimpleXMLElement &$config, array $post): void
    {
        if ($this->swag !== (($post['swag'] ?? "false") === "enable")) {
        Utils::apply_label($config, 'swag.swag', $post['swag'] ?? "false", "false");
    }

        if ($this->swag_address !== ($post['swag_address'] ?? "")) {
            Utils::apply_label($config, 'swag.swag_address', $post['swag_address'] ?? "");
        }

        if ($this->swag_port !== intval($post['swag_port'] ?? "0")) {
            Utils::apply_label($config, 'swag.swag_port', $post['swag_port'] ?? "0", "0");
        }

        if ($this->swag_url !== ($post['swag_url'] ?? "")) {
            Utils::apply_label($config, 'swag.swag_url', $post['swag_url'] ?? "", "");
        }

        if ($this->swag_auth !== ($post['swag_auth'] ?? "")) {
            Utils::apply_label($config, 'swag.swag_auth', $post['swag_auth'] ?? "", "");
        }
    }
    public function isEnabled(): bool
    {
        return $this->enable;
    }
}