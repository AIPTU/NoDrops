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
use pocketmine\item\Item;
use pocketmine\plugin\DisablePluginException;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use function array_filter;
use function array_map;
use function is_array;
use function is_bool;
use function is_string;
use function var_export;

final class ConfigManager {
	private const CONFIG_VERSION = 1.2;

	private static Config $config;

	private static bool $worldBlacklistEnabled;
	private static array $blacklistedWorlds;
	private static bool $worldWhitelistEnabled;
	private static array $whitelistedWorlds;
	private static bool $messageEnabled;
	private static string $message;
	private static bool $allItemsEnabled;
	private static array $listItems;

	public static function init(NoDrops $plugin) : void {
		$config = $plugin->getConfig();

		if (!$config->exists('config-version') || $config->get('config-version') !== self::CONFIG_VERSION) {
			$plugin->getLogger()->warning('An outdated config was provided. Attempting to generate a new one...');
			$filesystem = new Filesystem();

			try {
				$filesystem->rename(Path::join($plugin->getDataFolder(), 'config.yml'), Path::join($plugin->getDataFolder(), 'config.old.yml'));
				$plugin->reloadConfig();
			} catch (IOException $e) {
				$plugin->getLogger()->critical('Failed to rename old config file: ' . $e->getMessage());
				throw new DisablePluginException();
			}
		}

		self::$config = $plugin->getConfig();

		self::$worldBlacklistEnabled = self::getBool('enable-world-blacklist', false);
		self::$blacklistedWorlds = self::getStringList('blacklisted-worlds', []);
		self::$worldWhitelistEnabled = self::getBool('enable-world-whitelist', false);
		self::$whitelistedWorlds = self::getStringList('whitelisted-worlds', []);
		self::$messageEnabled = self::getBool('enable-message', false);
		self::$message = self::getString('message', "&cYou can't drop your items here");
		self::$allItemsEnabled = self::getBool('enable-all-items', false);
		self::$listItems = array_map(static fn (string $item) : Item => $plugin->checkItem($item), self::getStringList('list-items', []));
	}

	public static function isWorldBlacklistEnabled() : bool {
		return self::$worldBlacklistEnabled;
	}

	public static function getBlacklistedWorlds() : array {
		return self::$blacklistedWorlds;
	}

	public static function isWorldWhitelistEnabled() : bool {
		return self::$worldWhitelistEnabled;
	}

	public static function getWhitelistedWorlds() : array {
		return self::$whitelistedWorlds;
	}

	public static function isMessageEnabled() : bool {
		return self::$messageEnabled;
	}

	public static function getMessage() : string {
		return TextFormat::colorize(self::$message);
	}

	public static function isAllItemsEnabled() : bool {
		return self::$allItemsEnabled;
	}

	public static function getListItems() : array {
		return self::$listItems;
	}

	private static function getBool(string $key, bool $default) : bool {
		$value = self::$config->getNested($key, $default);
		if (!is_bool($value)) {
			throw new InvalidArgumentException("Invalid config value for {$key}: " . self::printValue($value) . ', expected bool');
		}

		return $value;
	}

	private static function getString(string $key, string $default) : string {
		$value = self::$config->getNested($key, $default);
		if (!is_string($value)) {
			throw new InvalidArgumentException("Invalid config value for {$key}: " . self::printValue($value) . ', expected string');
		}

		return $value;
	}

	/**
	 * @param array<string> $default
	 *
	 * @return array<string>
	 */
	private static function getStringList(string $key, array $default) : array {
		$value = self::$config->getNested($key, $default);
		if (!is_array($value) || array_filter($value, 'is_string') !== $value) {
			throw new InvalidArgumentException("Invalid config value for {$key}: " . self::printValue($value) . ', expected string array');
		}

		return $value;
	}

	private static function printValue(mixed $value) : string {
		return var_export($value, true);
	}
}
