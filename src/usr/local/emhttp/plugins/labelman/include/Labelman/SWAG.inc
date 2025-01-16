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
    throw new \Exception("swag.inc called outside class");
}
?>

<dl>
    <dt>Enabled</dt>
    <dd>
        <select name='swag' size='1'>
            <?= Utils::make_option($this->enable, 'enable', "Yes");?>
            <?= Utils::make_option( ! $this->enable, '', "No");?>
        </select>
    </dd>
</dl>
<div class="advanced">
    <dl>
        <dt>Container Name or IP Address</dt>
        <dd>
            <input type="text" name="swag_address" class="narrow" min="0" max="65535" value="<?= $this->swag_address; ?>" placeholder="">
        </dd>
    </dl>
    <blockquote class='inline_help'>Overrides upstream app address. Can be set to an IP or a DNS hostname. Defaults to container name.</blockquote>

    <dl>
        <dt>Container Port</dt>
        <dd>
            <input type="number" name="swag_port" class="narrow" min="0" max="65535" value="<?= $this->swag_port; ?>" placeholder="0">
        </dd>
    </dl>
    <blockquote class='inline_help'>Overrides internal exposed port (by default, auto-proxy will use first detected exposed port)</blockquote>

    <dl>
        <dt>Scheme</dt>
        <dd>
            <select name='swag_proto' size='1'>
                <?= Utils::make_option($this->swag_proto == "http", 'http', "HTTP");?>
                <?= Utils::make_option($this->swag_proto == "https", 'https', "HTTPS");?>
            </select>
        </dd>
    </dl>
    <blockquote class='inline_help'>Overrides internal protocol (defaults to HTTP)</blockquote>

    <dl>
        <dt>URL</dt>
        <dd>
            <input type="text" name="swag_url" class="narrow" min="0" max="65535" value="<?= $this->swag_url; ?>" placeholder="">
        </dd>
    </dl>
    <blockquote class='inline_help'>Overrides server_name (defaults to containername.*)</blockquote>

    <dl>
        <dt>swag_auth</dt>
        <dd>
            <select name='swag_auth' size='1'>
                <?= Utils::make_option($this->swag_auth == "", '', "None");?>
                <?= Utils::make_option($this->swag_auth == "authelia", 'authelia', "Authelia");?>
                <?= Utils::make_option($this->swag_auth == "ldap", 'ldap', "LDAP");?>
                <?= Utils::make_option($this->swag_auth == "http", 'http', "HTTP");?>

            </select>
        </dd>
    </dl>
    <blockquote class='inline_help'>Enables auth methods (options are Authelia, LDAP and HTTP for basic HTTP authentication)</blockquote>
</div>
