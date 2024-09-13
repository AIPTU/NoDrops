<?php

/*
 * Copyright (c) 2021-2024 AIPTU
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/AIPTU/NoDrops
 */

declare(strict_types=1);

namespace aiptu\nodrops;

use JackMD\UpdateNotifier\UpdateNotifier;
use pocketmine\item\Item;
use pocketmine\item\LegacyStringToItemParser;
use pocketmine\item\LegacyStringToItemParserException;
use pocketmine\item\StringToItemParser;
use pocketmine\plugin\PluginBase;
use pocketmine\world\World;
use function class_exists;
use function explode;
use function in_array;
use function str_replace;
use function trim;

final class NoDrops extends PluginBase {
	public function onEnable() : void {
		ConfigManager::init($this);

		$this->getServer()->getPluginManager()->registerEvents(new EventHandler($this), $this);

		if (!class_exists(UpdateNotifier::class)) {
			$this->getLogger()->error('UpdateNotifier virion not found. Download NoDrops at https://poggit.pmmp.io/p/NoDrops');
			$this->getServer()->getPluginManager()->disablePlugin($this);
			return;
		}

		UpdateNotifier::checkUpdate($this->getDescription()->getName(), $this->getDescription()->getVersion());
	}

	public function checkItem(string $string) : Item {
		try {
			$item = LegacyStringToItemParser::getInstance()->parse($string);
		} catch (LegacyStringToItemParserException $e) {
			if (($item = StringToItemParser::getInstance()->parse(explode(':', str_replace([' ', 'minecraft:'], ['_', ''], trim($string)))[0])) === null) {
				throw $e;
			}
		}

		return $item;
	}

	public function checkWorld(World $world) : bool {
		$blacklist = ConfigManager::isWorldBlacklistEnable();
		$whitelist = ConfigManager::isWorldWhitelistEnable();
		$worldName = $world->getFolderName();

		if ($blacklist === $whitelist) {
			return true;
		}

		if ($blacklist) {
			$disallowedWorlds = ConfigManager::getBlacklistedWorlds();
			return !in_array($worldName, $disallowedWorlds, true);
		}

		if ($whitelist) {
			$allowedWorlds = ConfigManager::getWhitelistedWorlds();
			return in_array($worldName, $allowedWorlds, true);
		}

		return false;
	}
}
