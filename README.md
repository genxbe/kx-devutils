# Kirby devutils plugin

Some handy Kirby commands to help during Kirby development.

- [Kirby devutils plugin](#kirby-devutils-plugin)
  - [Available commands](#available-commands)
  - [Commands](#commands)
    - [`x:up`](#xup)
    - [`x:down`](#xdown)
      - [Commandline arguments](#commandline-arguments)
      - [Options](#options)
    - [`x:roots`](#xroots)
    - [`x:users`](#xusers)
    - [`x:routes`](#xroutes)
    - [`x:options`](#xoptions)
    - [`x:install`](#xinstall)
      - [Commandline arguments](#commandline-arguments-1)
      - [Options](#options-1)
  - [Installation](#installation)
    - [As a dev dependency](#as-a-dev-dependency)
    - [As a normal dependency](#as-a-normal-dependency)
  - [License](#license)
  - [Credits](#credits)

## Available commands

| Command     | Description                                                             |
| ----------- | ----------------------------------------------------------------------- |
| `x:up`      | Removes the generic maintenance mode message.                           |
| `x:down`    | Sets a generic maintenance mode message with an optional email address. |
| `x:roots`   | Show all roots of the kirby instance                                    |
| `x:users`   | Show all users of the kirby site                                    |
| `x:routes`  | List all routes                                                         |
| `x:options` | Show all or specific plugin options                                     |
| `x:install` | Install npm / yarn / composer (depending on what is used)               |

## Commands

### `x:up`

This command will remove the generic maintenance mode message.

### `x:down`

This command will set a generic maintenance mode message with an optional email address. You will be prompted with a confirmation message before the site is brought down.
You can also manually trigger the maintenance mode by creating a `.maintenance` file in the root of your Kirby installation. The contents of this file will be used as the email address.

Users who are logged in will still be able to visit the site.

You can always override the default snippet by creating a `site/snippets/x/maintenance.php` file.

#### Commandline arguments

**email**: The email address to show in the maintenance message

Example usage: `kirby x:down support@getkirby.com`

#### Options

```php
'genxbe.kx-devutils' => [
	'maintenance' => true,
];
```

**maintenance**: Enable or disable the __possibility__ for the maintenance mode.

### `x:roots`

This command will show all roots of the kirby instance.

### `x:users`

This command will show all users of the kirby site.

### `x:routes`

This command will list all routes.

### `x:options`

This command will show all or specific plugin options.

### `x:install`

This command will install the required dependencies for the project. It will detect if you are using npm, yarn or composer and run the appropriate command(s).

The first composer install should obviously be done manually. (otherwise this package won't be available yet 😅)

#### Commandline arguments

**--nophp**: Do not run the composer command (if a `composer.json` is found)
**--nojs**: Do not run the npm or yarn command (if a `package.json` or `yarn.lock` is found)

#### Options

```
'genxbe.kx-devutils.x-install' => [
    'createEnv' => false,
    'composerRunPrefix' => '',
];
```

**createEnv**: Create a `.env` file or rename an .env.example file if it does not exist
**composerPrefix**: Prefix for the composer command with herd/valet or a php executable

## Installation

This plugin can only be installed with composer!
If you are not using the maintenance mode or scheduler you can install the plugin as a dev dependency.

### As a dev dependency

```bash
composer require genxbe/kx-devutils --dev
```

### As a normal dependency

```bash
composer require genxbe/kx-devutils
```

## License

MIT

## Credits

- [Sam Serrien](https://sam.serrien.be) @ [GeNx](https://genx.be)
