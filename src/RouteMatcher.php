<?php declare(strict_types=1);

/*
 * @Author: rgiese 
 * @Date: 2021-11-15 08:42:08 
 * @Last Modified by: rgiese
 * @Last Modified time: 2021-11-15 16:24:08
 */

namespace RouteMatcher;

class RouteMatcher
{
    private const REGEX_PATTERN_PLACEHOLDER = "/{(\w+(?::.*?)?)}/";
    private const REGEX_PATTERN_DEFAULT = "[\w-]+";
    private const EXPRESSION_DELIMITER = ":";

    private function replacePlaceholder(string $pattern, &$keys = []): ?string
    {
        return preg_replace_callback(self::REGEX_PATTERN_PLACEHOLDER, function(array $match) use(&$keys)
        {
            $values = explode(self::EXPRESSION_DELIMITER, $match[1]);

            $key = $values[0];
            $keys[] = $key;

            $subPattern = count($values) == 1
                ? self::REGEX_PATTERN_DEFAULT
                : trim($values[1]);

            return "(?<$key>$subPattern)";
        }, $pattern);

    }
  
    private function filterArray(array $matches, array $keys): array
    {
        /*
        $filter = fn($key) => !is_numeric($key);
        return array_filter($matches, $filter, ARRAY_FILTER_USE_KEY);
        */
        $result = array();
        foreach($keys as $key)
        {
            if(isset($matches[$key]))
                $result[$key] = $matches[$key];
        }

        return $result;
    }

    public function parseRoute(string $pattern, string $route): array
    {
        if($route === $pattern)
            return [$route];

        $actualPattern = $this->replacePlaceholder($pattern, $keys);
        if(is_null($actualPattern))
            throw new Exception("Incorrect pattern found in route !");
        
        $success = preg_match("($actualPattern)", $route, $matches);
        return $success === false
            ? array()
            : $this->filterArray($matches, $keys);
    }
}