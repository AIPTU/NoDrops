# NoDrops

[![Discord](https://img.shields.io/discord/830063409000087612?color=7389D8&label=discord)](https://discord.com/invite/EggNF9hvGv)
[![GitHub License](https://img.shields.io/github/license/AIPTU/NoDrops.svg)](https://github.com/AIPTU/NoDrops/blob/master/LICENSE)

A PocketMine-MP plugin to prohibit players from dropping their items

# Config
```yaml
---
# Do not change this (Only for internal use)!
config-version: 1

# Message used when canceling a player's drop.
# Use "§" or "&" to color the message.
message: "&cYou can't drop your items here"

items:
  # List of items that can't be dropped.
  # If the item has meta, you can use the format "minecraft:id:meta".
  list:
    - "minecraft:log"
    - "minecraft:diamond_ore"
    - "minecraft:apple"
    - "minecraft:diamond_pickaxe"
    - "minecraft:sapling"

worlds:
  # The mode can be either "blacklist" or "whitelist".
  # Blacklist mode will cancel player's drop according to predefined world folder names and will not cancel player's drop in all worlds.
  # Whitelist mode will only allow player's drop according to predefined world folder names and will cancel player's drop in all worlds.
  mode: "blacklist"
  # List of world folder names to blacklist/whitelist (depending on the mode set above).
  # Set it to [] if you want to allow player's drop in all worlds.
  list:
    - "world"
...

```

# How to Install

1. Download the plugin from [here](https://poggit.pmmp.io/ci/AIPTU/NoDrops/NoDrops)
2. Put the `NoDrops.phar` file into the `plugins` folder.
3. Restart the server.
4. Done!

# Additional Notes

- If you find bugs or want to give suggestions, please visit [here](https://github.com/AIPTU/NoDrops/issues)
- Icons made from [www.flaticon.com](https://www.flaticon.com)

# License

```
MIT License

Copyright (c) 2021 AIPTU

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```
