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

use InvalidArgumentException;
use JackMD\UpdateNotifier\UpdateNotifier;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\plugin\DisablePluginException;
use pocketmine\plugin\PluginBase;
use pocketmine\world\World;
use function class_exists;
use function in_array;

final class NoDrops extends PluginBase {
	public function onEnable() : void {
		try {
			ConfigManager::init($this);
		} catch (\Throwable $e) {
			$this->getLogger()->critical('An error occurred while attempting to load the config: ' . $e->getMessage());
			throw new DisablePluginException();
		}

		$this->getServer()->getPluginManager()->registerEvents(new EventHandler($this), $this);

		if (!class_exists(UpdateNotifier::class)) {
			$this->getLogger()->error('UpdateNotifier virion not found. Download NoDrops at https://poggit.pmmp.io/p/NoDrops');
			$this->getServer()->getPluginManager()->disablePlugin($this);
			return;
		}

		UpdateNotifier::checkUpdate($this->getDescription()->getName(), $this->getDescription()->getVersion());
	}

	public function checkItem(string $itemString) : Item {
		$parsedItem = StringToItemParser::getInstance()->parse($itemString);
		if (!$parsedItem instanceof Item) {
			throw new InvalidArgumentException("Invalid item '{$itemString}'");
		}

		return $parsedItem;
	}

	public function isWorldEnabled(World $world) : bool {
		$blacklist = ConfigManager::isWorldBlacklistEnabled();
		$whitelist = ConfigManager::isWorldWhitelistEnabled();
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
