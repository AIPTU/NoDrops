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

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\item\Item;
use pocketmine\player\Player;
use function time;

final class EventHandler implements Listener {
	private const COOLDOWN_SECONDS = 3;

	private array $cooldownMessage = [];

	public function __construct(private NoDrops $plugin) {}

	public function onPlayerDropItem(PlayerDropItemEvent $event) : void {
		$player = $event->getPlayer();
		$item = $event->getItem();

		if (ConfigManager::isAllItemsEnabled() || $this->isRestrictedItem($item)) {
			if ($this->shouldCancelDrop($player)) {
				$event->cancel();
			}
		}
	}

	private function isRestrictedItem(Item $item) : bool {
		foreach (ConfigManager::getListItems() as $restrictedItem) {
			if ($item->equals($restrictedItem, true, false)) {
				return true;
			}
		}

		return false;
	}

	private function shouldCancelDrop(Player $player) : bool {
		if ($player->hasPermission('nodrops.bypass') || !$this->plugin->isWorldEnabled($player->getWorld())) {
			return false;
		}

		if (isset($this->cooldownMessage[$player->getName()]) && time() - self::COOLDOWN_SECONDS < $this->cooldownMessage[$player->getName()]) {
			return true;
		}

		$this->cooldownMessage[$player->getName()] = time();

		if (ConfigManager::isMessageEnabled()) {
			$player->sendMessage(ConfigManager::getMessage());
		}

		return true;
	}
}
