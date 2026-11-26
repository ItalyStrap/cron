<?php

// The absolute paths of the WordPress installed with SQLite by tests/bin/setup-wordpress.php.
return [
    'WP_ROOT_DIR' => __DIR__ . '/_wordpress',
    'WP_DB_URL' => 'sqlite://' . __DIR__ . '/_wordpress/data/db.sqlite',
];
