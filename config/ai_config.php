<?php

$secretFile = dirname(__DIR__, 3)
    . DIRECTORY_SEPARATOR . 'private'
    . DIRECTORY_SEPARATOR . 'openai.php';

if (!file_exists($secretFile)) {
    throw new RuntimeException('OpenAI secret file not found.');
}

$secrets = require $secretFile;

if (empty($secrets['api_key'])) {
    throw new RuntimeException('OpenAI API key is missing.');
}

define('OPENAI_API_KEY', $secrets['api_key']);