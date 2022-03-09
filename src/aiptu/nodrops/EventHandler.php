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

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\player\Player;
use function time;

final class EventHandler implements Listener
{
	private array $cooldownMessage = [];

	public function __construct(private NoDrops $plugin)
	{
	}

	public function getPlugin(): NoDrops
	{
		return $this->plugin;
	}

	public function onPlayerDropItem(PlayerDropItemEvent $event): void
	{
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

	private function check(Player $player): void
	{
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
