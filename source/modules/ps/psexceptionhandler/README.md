# psExceptionHandler

Extended exception/throwable handling for OXID 6.
Shows meaningful exception messages. For more info see [OXID PR #609](https://github.com/OXID-eSales/oxideshop_ce/pull/609).

## Installation

1. Composer
```
composer require proudcommerce/psexceptionhandler:dev-master
```

2. functions.php

Add the following lines in `modules/functions.php`:

```
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__ . '/modules/ps/psexceptionhandler/functions.php');
```

## Changelog

2017-11-27	1.0.0	Module release (OXID 6 )

## License

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.


## Copyright

Proud Sourcing GmbH / shoptimax GmbH
www.proudcommerce.com / www.shoptimax.de