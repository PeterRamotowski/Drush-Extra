# Drush Extra

Provides additional Drush commands adapted from Drupal Console

## Commands list

| Description | Usage | Alias |
| --- | --- | --- |
| Release cron system lock to run cron again | ``` drush core:cron:release ```| ``` drush cror ``` |
| Displays all entities and bundles | ``` drush debug:entity ``` | ``` drush debe ``` |
| Displays all images styles with effects | ``` drush debug:image:styles ```| ``` drush debis ``` |
| Displays all roles with optional permissions | ``` drush debug:roles ``` | ``` drush debr ``` |
| | ``` drush debug:roles permissions ```|  |
| Rebuild node access permissions | ``` drush node:access:rebuild ``` | ``` drush nar ``` |
| | ``` drush node:access:rebuild batch ```|  |
