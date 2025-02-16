<?php
/**
 * Converts a string to title case, handling various input formats
 * (camelCase, kebab-case, snake_case, etc.)
 *
 * @param string $string The input string to convert
 * @return string The formatted title case string
 */
function toTitleCase($string)
{
    if (empty($string))
        return '';

    $string = preg_replace('/(?<=\w)(?=[A-Z])/', ' $1', $string);
    $string = preg_replace('/[-_.]/', ' ', $string);
    $string = preg_replace('/\s+/', ' ', $string);
    return mb_convert_case(trim($string), MB_CASE_TITLE, "UTF-8");
}

/**
 * Converts a string to camelCase.
 *
 * @param string $string The input string to convert
 * @return string The formatted camelCase string
 */
function toCamelCase($string)
{
    if (empty($string))
        return '';

    $string = toTitleCase($string);
    $string = lcfirst(str_replace(' ', '', $string));
    return $string;
}

/**
 * Converts a string to snake_case.
 *
 * @param string $string The input string to convert
 * @return string The formatted snake_case string
 */
function toSnakeCase($string)
{
    if (empty($string))
        return '';

    $string = preg_replace('/(?<=\w)(?=[A-Z])/', '_$1', $string);
    $string = preg_replace('/[-\s.]/', '_', $string);
    return strtolower(trim($string));
}

/**
 * Converts a string to kebab-case.
 *
 * @param string $string The input string to convert
 * @return string The formatted kebab-case string
 */
function toKebabCase($string)
{
    if (empty($string))
        return '';

    $string = preg_replace('/(?<=\w)(?=[A-Z])/', '-$1', $string);
    $string = preg_replace('/[_\s.]/', '-', $string);
    return strtolower(trim($string));
}

/**
 * Converts a string to PascalCase.
 *
 * @param string $string The input string to convert
 * @return string The formatted PascalCase string
 */
function toPascalCase($string)
{
    return str_replace(' ', '', toTitleCase($string));
}

/**
 * Converts a string to SCREAMING_SNAKE_CASE.
 *
 * @param string $string The input string to convert
 * @return string The formatted SCREAMING_SNAKE_CASE string
 */
function toScreamingSnakeCase($string)
{
    return strtoupper(toSnakeCase($string));
}

/**
 * Converts a string to Train-Case.
 *
 * @param string $string The input string to convert
 * @return string The formatted Train-Case string
 */
function toTrainCase($string)
{
    return str_replace(' ', '-', toTitleCase($string));
}

/**
 * Converts a string to flatcase (all lowercase, no separators).
 *
 * @param string $string The input string to convert
 * @return string The formatted flatcase string
 */
function toFlatCase($string)
{
    return strtolower(preg_replace('/\s+/', '', toTitleCase($string)));
}

/**
 * Converts a string to dot.case.
 *
 * @param string $string The input string to convert
 * @return string The formatted dot.case string
 */
function toDotCase($string)
{
    if (empty($string))
        return '';

    $string = preg_replace('/(?<=\w)(?=[A-Z])/', '.$1', $string);
    $string = preg_replace('/[_\s-]/', '.', $string);
    return strtolower(trim($string));
}

/**
 * Converts a string to UPPER-KEBAB-CASE.
 *
 * @param string $string The input string to convert
 * @return string The formatted UPPER-KEBAB-CASE string
 */
function toUpperKebabCase($string)
{
    return strtoupper(toKebabCase($string));
}
