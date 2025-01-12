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
    // Handle empty or null input
    if (empty($string))
        return '';

    // Convert camelCase to spaces
    $string = preg_replace('/(?<=\\w)(?=[A-Z])/', ' $1', $string);

    // Replace special characters with spaces
    $string = preg_replace('/[-_.]/', ' ', $string);

    // Remove any extra spaces and trim
    $string = preg_replace('/\s+/', ' ', $string);
    $string = trim($string);

    // Convert to title case, handling special cases
    return mb_convert_case($string, MB_CASE_TITLE, "UTF-8");
}