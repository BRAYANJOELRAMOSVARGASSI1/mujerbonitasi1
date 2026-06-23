<?php
$envPath = '/var/www/html/.env';
$env = file_get_contents($envPath);
$replacements = [
    '/^MAIL_HOST=.*$/m' => 'MAIL_HOST=smtp.gmail.com',
    '/^MAIL_USERNAME=.*$/m' => 'MAIL_USERNAME=ramosvargasbrayanjoel66@gmail.com',
    '/^MAIL_PASSWORD=.*$/m' => 'MAIL_PASSWORD=fukyueribrpvwrkd',
    '/^MAIL_FROM_ADDRESS=.*$/m' => 'MAIL_FROM_ADDRESS=ramosvargasbrayanjoel66@gmail.com'
];
foreach ($replacements as $pattern => $replacement) {
    $env = preg_replace($pattern, $replacement, $env);
}
file_put_contents($envPath, $env);
echo "AWS .env updated.\n";
