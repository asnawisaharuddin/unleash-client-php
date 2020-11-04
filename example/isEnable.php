<?php

require('vendor/autoload.php');
require('example/setup.php');

var_dump($unleash->isEnabled('es_report', ['userId' => 'asnawi@terato.com']));
