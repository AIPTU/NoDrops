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

use InvalidArgumentException;
use pocketmine\item\Item;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use function array_filter;
use function array_map;
use function is_array;
use function is_bool;
use function is_string;
use function rename;
use function var_export;

final class ConfigManager
{
	private const CONFIG_VERSION = 1.2;

	private static Config $config;

	private static bool $enableWorldBlacklist;
	private static array $blacklistedWorlds;
	private static bool $enableWorldWhitelist;
	private static array $whitelistedWorlds;
	private static bool $enableMessage;
	private static string $message;
	private static bool $enableAllItems;
	private static array $listItems;

	public static function init(NoDrops $plugin): void
	{
		$plugin->saveDefaultConfig();

		if (!$plugin->getConfig()->exists('config-version') || ($plugin->getConfig()->get('config-version', self::CONFIG_VERSION) !== self::CONFIG_VERSION)) {
			$plugin->getLogger()->warning('An outdated config was provided attempting to generate a new one...');
			if (!rename($plugin->getDataFolder() . 'config.yml', $plugin->getDataFolder() . 'config.old.yml')) {
				$plugin->getLogger()->critical('An unknown error occurred while attempting to generate the new config');
				$plugin->getServer()->getPluginManager()->disablePlugin($plugin);
			}
			$plugin->reloadConfig();
		}

		self::$config = $plugin->getConfig();

		self::$enableWorldBlacklist = self::getBool('enable-world-blacklist', false);
		self::$blacklistedWorlds = self::getStringList('blacklisted-worlds', []);
		self::$enableWorldWhitelist = self::getBool('enable-world-whitelist', false);
		self::$whitelistedWorlds = self::getStringList('whitelisted-worlds', []);
		self::$enableMessage = self::getBool('enable-message', false);
		self::$message = self::getString('message', "&cYou can't drop your items here");
		self::$enableAllItems = self::getBool('enable-all-items', false);
		self::$listItems = array_map(static fn (string $item): Item => $plugin->checkItem($item), self::getStringList('list-items', []));
	}

	public static function isWorldBlacklistEnable(): bool
	{
		return self::$enableWorldBlacklist;
	}

	public static function getBlacklistedWorlds(): array
	{
		return self::$blacklistedWorlds;
	}

	public static function isWorldWhitelistEnable(): bool
	{
		return self::$enableWorldWhitelist;
	}

	public static function getWhitelistedWorlds(): array
	{
		return self::$whitelistedWorlds;
	}

	public static function isMessageEnable(): bool
	{
		return self::$enableMessage;
	}

	public static function getMessage(): string
	{
		return TextFormat::colorize(self::$message);
	}

	public static function isAllItemsEnable(): bool
	{
		return self::$enableAllItems;
	}

	public static function getListItems(): array
	{
		return self::$listItems;
	}

	private static function getBool(string $key, bool $default): bool
	{
		$value = self::$config->getNested($key, $default);
		if (!is_bool($value)) {
			throw new InvalidArgumentException("Invalid config value for {$key}: " . self::printValue($value) . ', expected bool');
		}
		return $value;
	}

	private static function getString(string $key, string $default): string
	{
		$value = self::$config->getNested($key, $default);
		if (!is_string($value)) {
			throw new InvalidArgumentException("Invalid config value for {$key}: " . self::printValue($value) . ', expected string');
		}
		return $value;
	}

	/**
	 * @param string[] $default
	 *
	 * @return string[]
	 */
	private static function getStringList(string $key, array $default): array
	{
		$value = self::$config->getNested($key, $default);
		if (!is_array($value) || array_filter($value, 'is_string') !== $value) {
			throw new InvalidArgumentException("Invalid config value for {$key}: " . self::printValue($value) . ', expected string array');
		}
		return $value;
	}

	private static function printValue(mixed $value): string
	{
		return var_export($value, true);
	}
}
