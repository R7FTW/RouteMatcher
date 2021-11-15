<?php declare(strict_types=1);

/*
 * @Author: rgiese 
 * @Date: 2021-11-15 08:42:38 
 * @Last Modified by: rgiese
 * @Last Modified time: 2021-11-15 09:49:01
 */

use RouteMatcher\RouteMatcher;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;

class RouteMatcherTest extends TestCase
{
    const URL_PATTERN = "https://meine-homepage/subpath/{name_underscore}/foo/bar/11/{id:\d+}";
    const URL =         "https://meine-homepage/subpath/abc-defg/foo/bar/11/1234567";
    const URL_NOMATCH = "https://meine-homepage/subpath/foo/bar/11/";

    public function testRouteNoMatch()
    {
        $matcher = new RouteMatcher();
        $result = $matcher->parseRoute($this::URL_PATTERN, $this::URL_NOMATCH);

        $expected = array();
        assertEquals($expected, $result);
    }

    public function testRouteMatch()
    {
        $matcher = new RouteMatcher();
        $result = $matcher->parseRoute($this::URL_PATTERN, $this::URL);

        $expected = [
            "name_underscore" => "abc-defg",
            "id" => "1234567"
        ];

        assertEquals($expected, $result);
    }

    public function testRouteEqualRoute()
    {
        $matcher = new RouteMatcher();
        $result = $matcher->parseRoute($this::URL_NOMATCH, $this::URL_NOMATCH);

        $expected = [$this::URL_NOMATCH];
        assertEquals($expected, $result);
    }
}
