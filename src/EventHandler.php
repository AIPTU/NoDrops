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
use pocketmine\utils\TextFormat;

final class EventHandler implements Listener
{
	public function __construct(private NoDrops $plugin)
	{
	}

	public function getPlugin(): NoDrops
	{
		return $this->plugin;
	}

	public function onPlayerDropItem(PlayerDropItemEvent $event): void
	{
		if ($event->isCancelled()) {
			return;
		}

		$player = $event->getPlayer();
		$item = $event->getItem();

		if (!$this->getPlugin()->checkPermission($player)) {
			return;
		}

		if (!$this->getPlugin()->checkWorld($player->getWorld())) {
			return;
		}

		if ($this->getPlugin()->getTypedConfig()->getBool('enable-all-items')) {
			if ($this->getPlugin()->getTypedConfig()->getBool('enable-message')) {
				$player->sendPopup(TextFormat::colorize($this->getPlugin()->getTypedConfig()->getString('message', "&cYou can't drop your items here")));
			}

			$event->cancel();
			return;
		}

		foreach ($this->getPlugin()->getTypedConfig()->getStringList('items') as $value) {
			$itemDrops = $this->getPlugin()->checkItem($value);

			if ($item->equals($itemDrops, true, false)) {
				if ($this->getPlugin()->getTypedConfig()->getBool('enable-message')) {
					$player->sendPopup(TextFormat::colorize($this->getPlugin()->getTypedConfig()->getString('message', "&cYou can't drop your items here")));
				}

				$event->cancel();
			}
		}
	}
}
