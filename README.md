# Nepf - Noch ein PHP Framework (Yet Another PHP Framework)

## Info
Simple PHP Framework with request routing and templates. Written completely object-oriented and with as few
layers of unnecessary fluff as possible. The class autoloader is a superset of PSR-0.

## Usage

### Example Directory Structure
```
[Your Web Root]
│   .htaccess
│   app.php
├───app
│   │   AGMain.php
│   │   AGUser.php
│   │   Config.php
│   │      ....
├───nepf
│   ├─── ....
└───resource
    └───template
```

### Example .htaccess
```
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteRule ^app\.php - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ app.php [QSA,L]
</IfModule>
```

### Example app.php
```php
<?php
#region ### Basic Setup
require_once __DIR__.'/nepf/bootstrap.php';

define('APP_DIR',__DIR__.'/app');

use Nepf\System\AutoLoader;
AutoLoader::RegisterNamespace('MyOwnProject',APP_DIR);
#endregion

use Nepf\Kernel;
use Nepf\Database\MySQLi\MySQLi;
use MyOwnProject\Config;

$kernel = new Kernel(Kernel::ModeDebug, __DIR__.'/resource');
$kernel->setup(array(
	'database' => new MySQLi(Config::DB_USER, Config::DB_PASS, Config::DB_SCHEMA)
));
$kernel->registerActionGroup('', 'MyOwnProject\\AGMain');
$kernel->registerActionGroup('user', 'MyOwnProject\\AGUser');
$kernel->run();
?>
```
