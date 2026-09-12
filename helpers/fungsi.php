<?php
function base_url($path = '') {
    return rtrim((isset($_SERVER['HTTP_HOST'])? 'http://'.$_SERVER['HTTP_HOST'] : ''), '/') . '/' . ltrim($path, '/');
}

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function badRequest(): void
{
    http_response_code(400);
    exit('Data tidak valid.');
}

function inputString(array $input, string $key, bool $required = true): string
{
    $value = $input[$key] ?? '';
    if (!is_string($value) || ($required && trim($value) === '')) {
        badRequest();
    }
    return $value;
}

function positiveId($value): int
{
    if (!is_string($value) && !is_int($value)) {
        badRequest();
    }
    $id = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($id === false) {
        badRequest();
    }
    return $id;
}

function idList($values): array
{
    if (!is_array($values) || count($values) === 0) {
        badRequest();
    }
    return array_values(array_unique(array_map('positiveId', $values)));
}
