<?php
// Define your Symfony environment variables
$envVars = [
    'APP_SECRET' => '01b574fb18607b54cef5361079d168',
    'DATABASE_URL' => 'mysql://root:123456789@127.0.0.1:3306/ecommerce_db?serverVersion=8.0.32'
];

echo "Copy and paste the following lines into your PowerShell session:\n\n";

foreach ($envVars as $key => $value) {
    // Escape double quotes
    $escaped = str_replace('"', '`"', $value);
    echo "\$env:$key = \"$escaped\"\n";
}
