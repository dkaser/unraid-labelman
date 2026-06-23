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

class Traefik implements Service
{
    private string $container_name = "";

    public bool $enable            = false;
    public string $rule            = "";
    public string $entrypoint      = "";
    public string $certresolver    = "";
    public int $container_port     = 0;
    public string $scheme          = "http";

    public function __construct(Container $container)
    {
        $this->container_name = $container->config->Name;

        $labels = $container->getLabels();

        if (($labels['traefik.enable'] ?? null) == "true") {
            $this->enable = true;
        }
        if (isset($labels[sprintf('traefik.http.routers.%s.rule', $this->container_name)])) {
            $this->rule = $labels[sprintf('traefik.http.routers.%s.rule', $this->container_name)];
        }
        if (isset($labels[sprintf('traefik.http.routers.%s.entrypoints', $this->container_name)])) {
            $this->entrypoint = $labels[sprintf('traefik.http.routers.%s.entrypoints', $this->container_name)];
        }
        if (isset($labels[sprintf('traefik.http.routers.%s.tls.certresolver', $this->container_name)])) {
            $this->certresolver = $labels[sprintf('traefik.http.routers.%s.tls.certresolver', $this->container_name)];
        }
        if (isset($labels[sprintf('traefik.http.services.%s.loadbalancer.server.port', $this->container_name)])) {
            $this->container_port = intval($labels[sprintf('traefik.http.services.%s.loadbalancer.server.port', $this->container_name)]);
        }
        if (isset($labels[sprintf('traefik.http.services.%s.loadbalancer.server.scheme', $this->container_name)])) {
            $this->scheme = $labels[sprintf('traefik.http.services.%s.loadbalancer.server.scheme', $this->container_name)];
        }
    }

    public static function serviceExists(SystemInfo $info): bool
    {
        $traefikFound = false;
        foreach ($info->Images as $image) {
            if (str_contains(strtolower($image), "traefik")) {
                $traefikFound = true;
                break;
            }
        }

        return $traefikFound;
    }

    public static function getDisplayName(): string
    {
        return "Traefik";
    }

    public function display(Container $container): void
    {
        include __DIR__ . "/Traefik.inc";
    }

    public function update(\SimpleXMLElement &$config, array $post): void
    {
        if ($this->enable != ($post['traefik_enable'] == "true")) {
            Utils::apply_label($config, 'traefik.enable', $post['traefik_enable'], "false");
        }

        if ($this->rule != $post['traefik_rule']) {
            Utils::apply_label($config, sprintf('traefik.http.routers.%s.rule', $this->container_name), $post['traefik_rule'], "");
        }

        if ($this->entrypoint != $post['traefik_entrypoint']) {
            Utils::apply_label($config, sprintf('traefik.http.routers.%s.entrypoints', $this->container_name), $post['traefik_entrypoint'], "");
        }

        if ($this->certresolver != $post['traefik_certresolver']) {
            Utils::apply_label($config, sprintf('traefik.http.routers.%s.tls.certresolver', $this->container_name), $post['traefik_certresolver'], "");
        }

        if ($this->container_port != intval($post['traefik_container_port'])) {
            Utils::apply_label($config, sprintf('traefik.http.services.%s.loadbalancer.server.port', $this->container_name), $post['traefik_container_port'] ?: "0", "0");
        }

        if ($this->scheme != $post['traefik_scheme']) {
            Utils::apply_label($config, sprintf('traefik.http.services.%s.loadbalancer.server.scheme', $this->container_name), $post['traefik_scheme'], "http");
        }
    }

    public function isEnabled(): bool
    {
        return $this->enable;
    }
}
