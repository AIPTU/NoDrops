# NoDrops

[![](https://poggit.pmmp.io/shield.state/NoDrops)](https://poggit.pmmp.io/p/NoDrops)
[![](https://poggit.pmmp.io/shield.dl.total/NoDrops)](https://poggit.pmmp.io/p/NoDrops)

A PocketMine-MP plugin to prohibit players from dropping their items.

# Features

- Permission bypass.
- World blacklist and whitelist support.
- Option to enable or disable messages.
- Supports `&` as formatting codes.
- Option to enable or disable all item drops.
- Custom item that can't be dropped.
- Lightweight and open source ❤️

# Default Config
```yaml
---
# Do not change this (Only for internal use)!
config-version: 1.1

# Set this to true if you want to use the blacklisted-worlds settings.
# If both enable-world-blacklist and disable-world-blacklist are set to the same setting,
# dropped items will be enabled for all worlds.
enable-world-blacklist: false
# If enable-world-blacklist is set to true, dropped items will be enabled for all worlds,
# except the worlds mentioned here.
blacklisted-worlds:
  - "blacklistedworld1"
  - "blacklistedworld2"

# Set this to true if you want to use the whitelisted-worlds settings.
# If both enable-world-blacklist and disable-world-blacklist are set to the same setting,
# dropped items will be disabled for all worlds.
enable-world-whitelist: false
# If enable-world-whitelist is set to true, dropped items will be disabled for all worlds,
# except the worlds mentioned here.
whitelisted-worlds:
  - "whitelistedworld1"
  - "whitelistecworld2"

# If this is set to true, players who try to drop items will be sent a message telling them they cannot do so.
enable-message: true
# Message used when canceling a player dropping an item.
# Use "&" to color the message.
message: "&cYou can't drop your items here"

# If this is set to true, all items can't be dropped.
enable-all-items: false
# List of items that can't be dropped.
# If the item has meta, you can use the format "id:meta".
list-items:
  - "oak_log"
  - "diamond_ore"
  - "apple"
  - "diamond_pickaxe"
  - "oak_sapling"
...

```

# Upcoming Features

- Currently none planned. You can contribute or suggest for new features.

# Additional Notes

- If you find bugs or want to give suggestions, please visit [here](https://github.com/AIPTU/NoDrops/issues).
- We accept all contributions! If you want to contribute, please make a pull request in [here](https://github.com/AIPTU/NoDrops/pulls).
- Icons made from [www.flaticon.com](https://www.flaticon.com)
