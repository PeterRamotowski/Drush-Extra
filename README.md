# Drush Extra

Provides additional Drush commands adapted from Drupal Console

## Usage

Displays all entities and bundles:

```bash
drush debug:entity
```

Displays all roles with optional permissions:

```bash
drush debug:roles
```
or

```bash
drush debug:roles permissions
```

Release cron system lock to run cron again

```bash
drush cron:release
```

## Todo

1. Add translation support
2. Much more commands :)
