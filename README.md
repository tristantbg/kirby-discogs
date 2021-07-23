# Kirby Discogs

Discogs field for Kirby 3.

## Overview

> This plugin is completely free and published under the MIT license.

- [1. Installation](#1-installation)
- [2. Blueprint usage](#2-blueprint-usage)
- [3. Options](#3-options)
- [4. Front-end usage](#4-front-end-usage)
- [5. License](#5-license)
- [6. Credits](#6-credits)

## 1. Installation

Download and copy this repository to ```/site/plugins/discogs```

Alternatively, you can install it with composer: ```composer require tristantbg/discogs```

<br/>

## 2. Blueprint usage

The plugin provides a `discogs` field that you can include in any blueprint:

```yaml
fields:
  discogs:
    label: Embed
    type: discogs
```

<br/>

## 3. Options

```php
// site/config/config.php
return array(
    'tristantbg.discogs.apiKey'  => 'xxx',
    'tristantbg.discogs.apiSecret' => 'xxx',
);
```

<br/>

## 4. Front-end usage

The plugin provides a `->toDiscogsObject()` method, which is useful to get all the stored data of the discogs (its html code, and a few other informations detailed below).

It also allows you to make sure your embed is successfully synced before trying to access the data:

```php
if($discogs = $page->myfield()->toDiscogsObject()) {
    echo $discogs->title()
}
```

TBC

```php
TBC
```

<br/>

## 5. License

MIT

<br/>

## 6. Credits

TBC
