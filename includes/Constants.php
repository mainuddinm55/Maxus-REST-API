<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'maxusint');

define('DATA_INSERTED', 101);
define('DATA_INSERTED_FAILED', 102);
define('DATA_EXISTS', 103);

define('PIN_INVALID', 200);
define('PIN_VALID', 201);

define('MOBILE_DUPLICATE', 104);
define('USERNAME_DUPLICATE', 105);
define('EMAIL_DUPLICATE', 106);

define('USER_CREATED', 101);
define('USER_EXISTS', 102);
define('USER_FAILURE', 103);
define('USER_AUTHENTICATED', 201);
define('USER_NOT_FOUND', 202);
define('USER_PASSWORD_DO_NOT_MATCH', 203);
define('USER_UNAUTHENTICATED', 204);
define('PASSWORD_CHANGED', 301);
define('PASSWORD_DO_NOT_MATCH', 302);
define('PASSWORD_NOT_CHANGED', 303);
?>