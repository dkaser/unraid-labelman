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

interface Service
{
    public function __construct(Container $container);
    public function display(Container $container): void;
    public function isEnabled(): bool;

    /**
    * @param array<string,string> $post
    */
    public function update(\SimpleXMLElement &$config, array $post): void;

    public static function serviceExists(SystemInfo $info): bool;
    public static function getDisplayName(): string;
}
