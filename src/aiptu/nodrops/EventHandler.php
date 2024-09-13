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
use pocketmine\player\Player;
use function time;

final class EventHandler implements Listener {
	private array $cooldownMessage = [];

	public function __construct(private NoDrops $plugin) {}

	public function getPlugin() : NoDrops {
		return $this->plugin;
	}

	public function onPlayerDropItem(PlayerDropItemEvent $event) : void {
		$player = $event->getPlayer();
		$item = $event->getItem();

		if (ConfigManager::isAllItemsEnable()) {
			$this->check($player);

			$event->cancel();
			return;
		}

		foreach (ConfigManager::getListItems() as $itemDrops) {
			if ($item->equals($itemDrops, true, false)) {
				$this->check($player);

				$event->cancel();
			}
		}
	}

	private function check(Player $player) : void {
		if ($player->hasPermission('nodrops.bypass')) {
			return;
		}

		if (!$this->getPlugin()->checkWorld($player->getWorld())) {
			return;
		}

		if (isset($this->cooldownMessage[$player->getName()]) && time() - 3 < $this->cooldownMessage[$player->getName()]) {
			return;
		}

		$this->cooldownMessage[$player->getName()] = time();

		if (ConfigManager::isMessageEnable()) {
			$player->sendMessage(ConfigManager::getMessage());
		}
	}
}
