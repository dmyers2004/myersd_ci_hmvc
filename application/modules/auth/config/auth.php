<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* how long the keys should be this needs to match the table schema */
$config['key_length']	= 72;

/*
maximum number of times to let some try to log in
NOTE:
Keep you admin login secret (or keep a separate one)
if someone bangs on that X times it will lock your account
*/
$config['max_tries'] = 5;

/* advance the remember me cookie X hours (remember_timeframe below) with each login */
$config['advance_remember'] = TRUE;

/* time out for each key in hours */
$config['active_timeframe'] = 72;
$config['forgot_timeframe'] = 72;
$config['remember_timeframe'] = 336;
