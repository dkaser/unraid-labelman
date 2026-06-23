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

if ( ! isset($this)) {
    throw new \Exception("Traefik.inc called outside class");
}
?>

<dl>
    <dt>Enabled*</dt>
    <dd>
        <select name='traefik_enable' size='1' required>
            <?= Utils::make_option($this->enable, 'true', "Yes");?>
            <?= Utils::make_option( ! $this->enable, 'false', "No");?>
        </select>
    </dd>
</dl>

<dl>
    <dt>Rule(s)*</dt>
    <dd>
        <input type="text" name="traefik_rule" value="<?= $this->rule; ?>" placeholder="" required />
    </dd>
</dl>
<blockquote class='inline_help'>Rules are a set of matchers configured with values, that determine if a particular request matches specific criteria. Example: "Host(`domain`)". See <a href="https://doc.traefik.io/traefik/routing/routers/#rule">documentation</a>.</blockquote>

<div class="advanced">
    <dl>
        <dt>Entrypoint(s)</dt>
        <dd>
            <input type="text" name="traefik_entrypoint" class="narrow" value="<?= $this->entrypoint; ?>" placeholder="" />
        </dd>
    </dl>
    <blockquote class='inline_help'>If not specified, will accept requests from all entrypoints in the <a href="https://doc.traefik.io/traefik/routing/entrypoints/#asdefault">list of default entrypoints</a>.</blockquote>

    <dl>
        <dt>Certificate Resolver</dt>
        <dd>
            <input type="text" name="traefik_certresolver" class="narrow" value="<?= $this->certresolver; ?>" placeholder="" />
        </dd>
    </dl>
    <blockquote class='inline_help'>If `certResolver` is defined, Traefik will try to generate certificates based on routers `Host` & `HostSNI` rules.</blockquote>

    <dl>
        <dt>Container Port</dt>
        <dd>
            <input type="number" name="traefik_container_port" class="narrow" min="0" max="65535" value="<?= $this->container_port > 0 ? $this->container_port : ''; ?>" placeholder="" />
        </dd>
    </dl>
    <blockquote class='inline_help'>Overrides internal exposed port (by default, Traefik uses the first exposed port)</blockquote>

    <dl>
        <dt>Scheme*</dt>
        <dd>
            <select name='traefik_scheme' size='1' required>
                <?= Utils::make_option($this->scheme == "http", 'http', "HTTP");?>
                <?= Utils::make_option($this->scheme == "https", 'https', "HTTPS");?>
            </select>
        </dd>
    </dl>
    <blockquote class='inline_help'>Overrides internal protocol (defaults to HTTP)</blockquote>
</div>
