<?php

// Database configuration
$_DVWA = array();
$_DVWA['db_server']   = getenv('DB_HOST') ?: '127.0.0.1';
$_DVWA['db_database'] = getenv('DB_NAME') ?: 'ticketdisaster';
$_DVWA['db_user']     = getenv('DB_USER') ?: 'root';
$_DVWA['db_password'] = getenv('DB_PASS') ?: 'vulnerable';
$_DVWA['db_port']     = '3306';

// Default security level
$_DVWA['default_security_level'] = 'low';

// Default admin credentials
$_DVWA['default_user'] = 'admin';
$_DVWA['default_pass'] = 'password';

// Recaptcha keys (placeholder)
$_DVWA['recaptcha_public_key']  = '';
$_DVWA['recaptcha_private_key'] = '';
