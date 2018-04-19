<?php
// Default values
define('DEFAULT_QUERY_LIMIT', 100);
define('DEFAULT_QUERY_OFFSET', 0);

// Time
define('THIRTY_DAY_IN_SECOND', 2592000);
define('ONE_DAY_IN_SECOND', 86400);
define('ONE_HOUR_IN_SECOND', 3600);
define('ONE_MINUTE_IN_SECOND', 60);
define('ONE_SECOND', 1);

// Length
define('PASSWORD_MIN_LENGTH', 8);
define('ID_LENGTH', 8);

// Section: Header
define('TOKEN_HEADER', 'X-Subject-Token');

// Section: HTTP status codes
// https://en.wikipedia.org/wiki/List_of_HTTP_status_codes
define('OK', 200);
define('CREATED', 201);

define('BAD_REQUEST', 400);
define('UNAUTHORIZED', 401);
define('FORBIDDEN', 403);
define('NOT_FOUND', 404);
define('METHOD_NOT_ALLOW', 405);
define('UNPROCESSABLE_ENTITY', 422);
define('LOCKED', 423);

define('INTERNAL_SERVER_ERROR', 500);
// End HTTP status codes

// Section: Validation messages
define('EMAIL_INVALID', 'The email is invalid.');
define('NAME_EXISTS', 'The given name is already exists.');
define('NAME_REQUIRED', 'The name is required.');
define('UNEXPECTED_CODE_REACH', 'Unexpected code reached.');

// When id (primary key) duplicate
define('ITEM_NOT_FOUND', 'Item not found.');
define('RECORD_EXISTS', 'Record cannot be created because it already exists');
define('PAGE_NOT_FOUND', 'Page not found.');
define('PASSWORD_TOO_SHORT', 'The given password length is too short.');
define('PASSWORD_REQUIRED', 'The password is required.');
define('SESSION_EXPIRED', 'The token session has expired. Please re-login.');
define('USERNAME_ALREADY_REGISTERED', 'The given username is already registered.');
define('USERNAME_REQUIRED', 'The username is required.');
// End Validation messages

// Sanitization
define('SANITIZE_ID', ['trim', 'string']);
define('SANITIZE_STRING', ['trim', 'string']);
define('SANITIZE_NUMBER', ['trim', 'int']);

// Web assets
define('SCRIPT_PREFIX', 'asset/script/');
define('STYLE_PREFIX', 'asset/style/');
define('IMAGE_PREFIX', 'asset/image/');
define('VENDOR_PREFIX', 'asset/vendor/');
