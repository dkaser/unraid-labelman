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

class TSDProxy implements Service
{
    public bool $enable        = false;
    public bool $ephemeral     = false;
    public string $name        = "";
    public bool $funnel        = false;
    public int $container_port = 0;
    public string $scheme      = "http";
    public bool $tlsvalidate   = true;

    public function __construct(Container $container)
    {
        $labels = $container->getLabels();

        if (($labels['tsdproxy.enable'] ?? null) == "true") {
            $this->enable = true;
        }
        if (($labels['tsdproxy.ephemeral'] ?? null) == "true") {
            $this->ephemeral = true;
        }
        if (($labels['tsdproxy.funnel'] ?? null) == "true") {
            $this->funnel = true;
        }
        if (($labels['tsdproxy.tlsvalidate'] ?? null) == "false") {
            $this->tlsvalidate = false;
        }
        if (isset($labels['tsdproxy.container_port'])) {
            $this->container_port = intval($labels['tsdproxy.container_port']);
        }
        if (isset($labels['tsdproxy.name'])) {
            $this->name = $labels['tsdproxy.name'];
        }
        if (isset($labels['tsdproxy.scheme'])) {
            $this->scheme = $labels['tsdproxy.scheme'];
        }
    }

    public static function serviceExists(SystemInfo $info): bool
    {
        $tsdFound = false;
        foreach ($info->Images as $image) {
            if (str_contains(strtolower($image), "tsdproxy")) {
                $tsdFound = true;
                break;
            }
        }

        return $tsdFound;
    }

    public static function getDisplayName(): string
    {
        return "TSDProxy";
    }

    public function display(Container $container): void
    {
        include __DIR__ . "/TSDProxy.inc";
    }

    public function update(\SimpleXMLElement &$config, array $post): void
    {
        if ($this->enable != ($post['TSDProxy_enable'] == "true")) {
            Utils::apply_label($config, 'tsdproxy.enable', $post['TSDProxy_enable'], "false");
        }

        if (($this->ephemeral != ($post['TSDProxy_ephemeral'] == "true")) || ($post['TSDProxy_enable'] == "true")) {
            Utils::apply_label($config, 'tsdproxy.ephemeral', $post['TSDProxy_ephemeral']);
        }

        if ($this->funnel != ($post['TSDProxy_funnel'] == "true")) {
            Utils::apply_label($config, 'tsdproxy.funnel', $post['TSDProxy_funnel'], "false");
        }

        if ($this->tlsvalidate != ($post['TSDProxy_tlsvalidate'] == "true")) {
            Utils::apply_label($config, 'tsdproxy.tlsvalidate', $post['TSDProxy_tlsvalidate'], "true");
        }

        if ($this->container_port != intval($post['TSDProxy_container_port'])) {
            Utils::apply_label($config, 'tsdproxy.container_port', $post['TSDProxy_container_port'] ?: "0", "0");
        }

        if ($this->name != $post['TSDProxy_name']) {
            Utils::apply_label($config, 'tsdproxy.name', $post['TSDProxy_name'], "");
        }

        if ($this->scheme != $post['TSDProxy_scheme']) {
            Utils::apply_label($config, 'tsdproxy.scheme', $post['TSDProxy_scheme'], "http");
        }
    }

    public function isEnabled(): bool
    {
        return $this->enable;
    }
}
