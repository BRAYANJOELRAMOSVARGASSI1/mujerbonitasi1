#!/bin/bash
cd /var/www/html
sudo php artisan tinker << 'PHPEOF'
$db = DB::connection();
$tables = $db->select('SHOW TABLES');
foreach($tables as $t) {
    $arr = (array)$t;
    echo implode('', $arr) . "\n";
}
exit;
PHPEOF
