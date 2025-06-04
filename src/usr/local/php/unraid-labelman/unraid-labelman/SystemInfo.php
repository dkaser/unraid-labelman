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

class SystemInfo
{
    /** @var array<string> $Images */
    public array $Images;

    /** @var array<string> $ManagedContainers */
    public array $ManagedContainers;

    public function __construct()
    {
        $this->Images            = self::getImages();
        $this->ManagedContainers = self::getManagedContainers();
    }

    /**
     * @return array<string>
     */
    private static function getImages(): array
    {
        return Utils::run_command('docker container ls --format="{{.Image}}"');
    }

    /**
     * @return array<string>
     */
    private static function getManagedContainers(): array
    {
        $containers = Utils::run_command('docker container ls --format="{{.Names}}" --filter "label=net.unraid.docker.managed=dockerman"');
        sort($containers, SORT_STRING | SORT_FLAG_CASE);

        return $containers;
    }
}
