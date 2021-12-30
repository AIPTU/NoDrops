# NoDrops

[![Discord](https://img.shields.io/discord/830063409000087612?color=7389D8&label=discord)](https://discord.com/invite/EggNF9hvGv)

A PocketMine-MP plugin to prohibit players from dropping their items.

# Features

- Permission bypass.
- Supports `&` as formatting codes.
- Custom item that can be dropped.
- Per world support.
- Lightweight and open source ❤️

# Default Config
```yaml
---
# Do not change this (Only for internal use)!
config-version: 1.0

# Message used when canceling a player dropping an item.
# Use "§" or "&" to color the message.
message: "&cYou can't drop your items here"

items:
  # List of items that can be dropped.
  # If the item has meta, you can use the format "minecraft:id:meta".
  list:
    - "minecraft:oak_log"
    - "minecraft:diamond_ore"
    - "minecraft:apple"
    - "minecraft:diamond_pickaxe"
    - "minecraft:oak_sapling"

worlds:
  # The mode can be either "blacklist" or "whitelist".
  # Blacklist mode will cancel player's drop according to predefined world folder names and will not cancel player's drop in all worlds.
  # Whitelist mode will only allow player's drop according to predefined world folder names and will cancel player's drop in all worlds.
  mode: "blacklist"
  # List of world folder names to blacklist/whitelist (depending on the mode set above).
  # Leave it empty if you want to allow player's drop in all worlds.
  list:
    - "world"
...

```

# How to Install

1. Download the plugin from [here](https://poggit.pmmp.io/ci/AIPTU/NoDrops/NoDrops).
2. Put the `NoDrops.phar` file into the `plugins` folder.
3. Restart the server.
4. Done!

# Upcoming Features

- Currently none planned. You can contribute or suggest for new features.

# Additional Notes

- If you find bugs or want to give suggestions, please visit [here](https://github.com/AIPTU/NoDrops/issues).
- We accept any contributions! If you want to contribute please make a pull request in [here](https://github.com/AIPTU/NoDrops/pulls).
- Icons made from [www.flaticon.com](https://www.flaticon.com)
