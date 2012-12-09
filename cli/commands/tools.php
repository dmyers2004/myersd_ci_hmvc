#!/usr/bin/env php
<?php
include dirname(__FILE__).'/library/init.php';

$options = array(
    'method' => array(
        'description'   => 'Method to Call on the CLI Controller',
        'default'       => '',
        'type'          => 'string',
        'required'      => true,
    ),
);

$banner = c("Run CodeIgniter Tool Controller Methods",'red');
Pharse::setBanner($banner);

$opts = Pharse::options($options);

$command = "cd ../public/; php index.php cli ".$opts['method'];

passthru($command);