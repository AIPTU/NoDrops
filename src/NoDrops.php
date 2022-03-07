<?php

/*
 *
 * Copyright (c) 2021 AIPTU
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 */

declare(strict_types=1);

namespace aiptu\nodrops;

use aiptu\nodrops\utils\TypedConfig;
use pocketmine\item\Item;
use pocketmine\item\LegacyStringToItemParser;
use pocketmine\item\LegacyStringToItemParserException;
use pocketmine\item\StringToItemParser;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\world\World;
use function explode;
use function in_array;
use function rename;
use function str_replace;
use function trim;

final class NoDrops extends PluginBase
{
	private const CONFIG_VERSION = 1.1;

	private TypedConfig $typedConfig;

	public function onEnable(): void
	{
		$this->checkConfig();

		$this->getServer()->getPluginManager()->registerEvents(new EventHandler($this), $this);
	}

	public function getTypedConfig(): TypedConfig
	{
		return $this->typedConfig;
	}

	public function checkItem(string $string): Item
	{
		try {
			$item = LegacyStringToItemParser::getInstance()->parse($string);
		} catch (LegacyStringToItemParserException $e) {
			if (($item = StringToItemParser::getInstance()->parse(explode(':', str_replace([' ', 'minecraft:'], ['_', ''], trim($string)))[0])) === null) {
				throw $e;
			}
		}

		return $item;
	}

	public function checkPermission(Player $player): bool
	{
		if (!$this->getTypedConfig()->getBool('enable-permission')) {
			return true;
		}

		return $player->hasPermission('nodrops.bypass');
	}

	public function checkWorld(World $world): bool
	{
		$blacklist = $this->getTypedConfig()->getBool('enable-world-blacklist');
		$whitelist = $this->getTypedConfig()->getBool('enable-world-whitelist');
		$worldName = $world->getFolderName();

		if ($blacklist === $whitelist) {
			return true;
		}

		if ($blacklist) {
			$disallowedWorlds = $this->getTypedConfig()->getStringList('blacklisted-worlds');
			return !(in_array($worldName, $disallowedWorlds, true));
		}

		if ($whitelist) {
			$allowedWorlds = $this->getTypedConfig()->getStringList('whitelisted-worlds');
			return in_array($worldName, $allowedWorlds, true);
		}

		return false;
	}

	private function checkConfig(): void
	{
		$this->saveDefaultConfig();

		if (!$this->getConfig()->exists('config-version') || ($this->getConfig()->get('config-version', self::CONFIG_VERSION) !== self::CONFIG_VERSION)) {
			$this->getLogger()->warning('An outdated config was provided attempting to generate a new one...');
			if (!rename($this->getDataFolder() . 'config.yml', $this->getDataFolder() . 'config.old.yml')) {
				$this->getLogger()->critical('An unknown error occurred while attempting to generate the new config');
				$this->getServer()->getPluginManager()->disablePlugin($this);
			}
			$this->reloadConfig();
		}

		$this->typedConfig = new TypedConfig($this->getConfig());

		foreach ($this->getTypedConfig()->getStringList('items') as $value) {
			$this->checkItem($value);
		}
	}
}
