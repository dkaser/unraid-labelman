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
    along with this program.  If not, see <https://www.gnu.org/licenses/>. */ if ( ! isset($this)) {
    throw new \Exception("swag.inc called outside class");
} if ( ! defined(__NAMESPACE__ . '\PLUGIN_ROOT') || ! defined(__NAMESPACE__ .
'\PLUGIN_NAME')) {
    throw new \RuntimeException("Common file not loaded.");
} $tr
 = $tr ?? new Translator(PLUGIN_ROOT); ?>

<dl>
  <dt><?= $tr->tr("enabled"); ?></dt>
  <dd>
    <select name="swag" size="1" class="narrow">
      <?= Utils::make_option($this->enable, 'enable', $tr->tr("yes"));?>
      <?= Utils::make_option( ! $this->enable, '', $tr->tr("no"));?>
    </select>
  </dd>
</dl>
<div class="advanced">
  <dl>
    <dt><?= $tr->tr("swag.name_ip"); ?></dt>
    <dd>
      <input
        type="text"
        name="swag_address"
        class="narrow"
        min="0"
        max="65535"
        value="<?= $this->swag_address; ?>"
        placeholder=""
      />
    </dd>
  </dl>
  <blockquote class="inline_help">
    <?= $tr->tr("swag.help.name_ip"); ?>
  </blockquote>

  <dl>
    <dt><?= $tr->tr("container_port"); ?></dt>
    <dd>
      <input
        type="number"
        name="swag_port"
        class="narrow"
        min="0"
        max="65535"
        value="<?= $this->swag_port; ?>"
        placeholder="0"
      />
    </dd>
  </dl>
  <blockquote class="inline_help"><?= $tr->tr("swag.help.port"); ?></blockquote>

  <dl>
    <dt><?= $tr->tr("swag.scheme"); ?></dt>
    <dd>
      <select name="swag_proto" size="1" class="narrow">
        <?= Utils::make_option($this->swag_proto == "http", 'http', "HTTP");?>
        <?= Utils::make_option($this->swag_proto == "https", 'https',
            "HTTPS");?>
      </select>
    </dd>
  </dl>
  <blockquote class="inline_help">
    <?= $tr->tr("swag.help.scheme"); ?>
  </blockquote>

  <dl>
    <dt><?= $tr->tr("swag.url"); ?></dt>
    <dd>
      <input
        type="text"
        name="swag_url"
        class="narrow"
        min="0"
        max="65535"
        value="<?= $this->swag_url; ?>"
        placeholder=""
      />
    </dd>
  </dl>
  <blockquote class="inline_help"><?= $tr->tr("swag.help.url"); ?></blockquote>

  <dl>
    <dt><?= $tr->tr("swag.authentication"); ?></dt>
    <dd>
      <select name="swag_auth" size="1" class="narrow">
        <?= Utils::make_option($this->swag_auth == "", '', "None");?>
        <?= Utils::make_option($this->swag_auth == "authelia", 'authelia',
            "Authelia");?>
        <?= Utils::make_option($this->swag_auth == "ldap", 'ldap', "LDAP");?>
        <?= Utils::make_option($this->swag_auth == "http", 'http', "HTTP");?>
      </select>
    </dd>
  </dl>
  <blockquote class="inline_help">
    <?= $tr->tr("swag.help.authentication"); ?>
  </blockquote>
</div>
