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

if ( ! isset($this)) {
    throw new \Exception("TSDProxy.inc called outside class");
}

if ( ! defined(__NAMESPACE__ . '\PLUGIN_ROOT') || ! defined(__NAMESPACE__ . '\PLUGIN_NAME')) {
    throw new \RuntimeException("Common file not loaded.");
}

$tr = $tr ?? new Translator(PLUGIN_ROOT);
?>

<dl>
  <dt><?= $tr->tr("enabled"); ?></dt>
  <dd>
    <select name="TSDProxy_enable" size="1">
      <?= Utils::make_option($this->enable, 'true', $tr->tr("yes"));?>
      <?= Utils::make_option( ! $this->enable, 'false', $tr->tr("no"));?>
    </select>
  </dd>
</dl>
<div class="advanced">
  <dl>
    <dt><?= $tr->tr("name"); ?></dt>
    <dd>
      <input
        type="text"
        name="TSDProxy_name"
        class="narrow"
        min="0"
        max="65535"
        value="<?= $this->name; ?>"
        placeholder=""
      />
    </dd>
  </dl>
  <blockquote class="inline_help">
    <?= $tr->tr("tsdproxy.help.name"); ?>
  </blockquote>

  <dl>
    <dt><?= $tr->tr("container_port"); ?></dt>
    <dd>
      <input
        type="number"
        name="TSDProxy_container_port"
        class="narrow"
        min="0"
        max="65535"
        value="<?= $this->container_port; ?>"
        placeholder="0"
      />
    </dd>
  </dl>
  <blockquote class="inline_help">
    <?= $tr->tr("tsdproxy.help.port"); ?>
  </blockquote>

  <dl>
    <dt><?= $tr->tr("tsdproxy.ephemeral"); ?></dt>
    <dd>
      <select name="TSDProxy_ephemeral" size="1">
        <?= Utils::make_option($this->ephemeral, 'true', $tr->tr("yes"));?>
        <?= Utils::make_option( ! $this->ephemeral, 'false', $tr->tr("no"));?>
      </select>
    </dd>
  </dl>
  <blockquote class="inline_help">
    <?= $tr->tr("tsdproxy.help.ephemeral"); ?>
  </blockquote>

  <dl>
    <dt><?= $tr->tr("tsdproxy.funnel"); ?></dt>
    <dd>
      <select name="TSDProxy_funnel" size="1">
        <?= Utils::make_option($this->funnel, 'true', $tr->tr("yes"));?>
        <?= Utils::make_option( ! $this->funnel, 'false', $tr->tr("no"));?>
      </select>
    </dd>
  </dl>
  <blockquote class="inline_help">
    <?= $tr->tr("tsdproxy.help.funnel"); ?>
  </blockquote>

  <dl>
    <dt><?= $tr->tr("tsdproxy.scheme"); ?></dt>
    <dd>
      <select name="TSDProxy_scheme" size="1">
        <?= Utils::make_option($this->scheme == "http", 'http', "HTTP");?>
        <?= Utils::make_option($this->scheme == "https", 'https', "HTTPS");?>
      </select>
    </dd>
  </dl>
  <blockquote class="inline_help">
    <?= $tr->tr("tsdproxy.help.scheme"); ?>
  </blockquote>

  <dl>
    <dt><?= $tr->tr("tsdproxy.tls_validate"); ?></dt>
    <dd>
      <select name="TSDProxy_tlsvalidate" size="1">
        <?= Utils::make_option($this->tlsvalidate, 'true', $tr->tr("yes"));?>
        <?= Utils::make_option( ! $this->tlsvalidate, 'false', $tr->tr("no"));?>
      </select>
    </dd>
  </dl>
  <blockquote class="inline_help">
    <?= $tr->tr("tsdproxy.help.tls_validate"); ?>
  </blockquote>
</div>
