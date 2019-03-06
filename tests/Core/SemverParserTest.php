<?php declare(strict_types=1);

namespace Sasamium\Cra\Core;

use PHPUnit\Framework\TestCase;

class SemverParserTest extends TestCase
{
    /**
     * @dataProvider successfulParse
     * @param $input
     * @param $expected
     */
    public function testParseSucceeds($input, $expected)
    {
        $this->assertSame($expected, SemverParser::parse($input));
        $this->assertTrue(SemverParser::isParsable($input));
    }

    /**
     * These test cases are based on the source code on the following link published under the MIT license.
     * @see https://github.com/composer/semver/blob/master/tests/VersionParserTest.php
     * @license MIT
     * @return array
     */
    public function successfulParse()
    {
        return [
            'none'                              => [
                '1.0.0',
                ['major' => 1, 'minor' => 0, 'patch' => 0]
            ],
            'none/2'                            => [
                '1.2.3.4',
                ['major' => 1, 'minor' => 2, 'patch' => 3]
            ],
            'parses state'                      => [
                '1.0.0RC1dev',
                ['major' => 1, 'minor' => 0, 'patch' => 0]
            ],
            'CI parsing'                        => [
                '1.0.0-rC15-dev',
                ['major' => 1, 'minor' => 0, 'patch' => 0]
            ],
            'delimiters'                        => [
                '1.0.0.RC.15-dev',
                ['major' => 1, 'minor' => 0, 'patch' => 0]
            ],
            'RC uppercase'                      => [
                '1.0.0-rc1',
                ['major' => 1, 'minor' => 0, 'patch' => 0]
            ],
            'patch replace'                     => [
                '1.0.0.pl3-dev',
                ['major' => 1, 'minor' => 0, 'patch' => 0]
            ],
            'parses long'                       => [
                '10.4.13-beta',
                ['major' => 10, 'minor' => 4, 'patch' => 13]
            ],
            'parses long/2'                     => [
                '10.4.13beta2',
                ['major' => 10, 'minor' => 4, 'patch' => 13]
            ],
            'parses long/semver'                => [
                '10.4.13beta.2',
                ['major' => 10, 'minor' => 4, 'patch' => 13]
            ],
            'expand shorthand'                  => [
                '10.4.13-b',
                ['major' => 10, 'minor' => 4, 'patch' => 13]
            ],
            'expand shorthand/2'                => [
                '10.4.13-b5',
                ['major' => 10, 'minor' => 4, 'patch' => 13]
            ],
            'strips leading v'                  => [
                'v1.0.0',
                ['major' => 1, 'minor' => 0, 'patch' => 0]
            ],
            'parses dates w/ . as classical'    => [
                '2010.01.02',
                ['major' => 2010, 'minor' => 1, 'patch' => 2]
            ],
            'parses dates y.m.Y as classical'   => [
                '2010.1.555',
                ['major' => 2010, 'minor' => 1, 'patch' => 555]
            ],
            'parses dates y.m.Y/2 as classical' => [
                '2010.10.200',
                ['major' => 2010, 'minor' => 10, 'patch' => 200]
            ],
            'parses dates y.m.Y'                => [
                '2010.1.555',
                ['major' => 2010, 'minor' => 1, 'patch' => 555]
            ],
            'semver metadata/2'                 => [
                '1.0.0-beta.5+foo',
                ['major' => 1, 'minor' => 0, 'patch' => 0]
            ],
            'semver metadata/3'                 => [
                '1.0.0+foo',
                ['major' => 1, 'minor' => 0, 'patch' => 0]
            ],
            'semver metadata/4'                 => [
                '1.0.0-alpha.3.1+foo',
                ['major' => 1, 'minor' => 0, 'patch' => 0]
            ],
            'semver metadata/5'                 => [
                '1.0.0-alpha2.1+foo',
                ['major' => 1, 'minor' => 0, 'patch' => 0]
            ],
            'semver metadata/6'                 => [
                '1.0.0-alpha-2.1-3+foo',
                ['major' => 1, 'minor' => 0, 'patch' => 0]
            ],
            // not supported for BC 'semver metadata/7' => ['1.0.0-0.3.7', '1.0.0.0-0.3.7'],
            // not supported for BC 'semver metadata/8' => ['1.0.0-x.7.z.92', '1.0.0.0-x.7.z.92'],
            'metadata w/ alias'                 => [
                '1.0.0+foo as 2.0',
                ['major' => 1, 'minor' => 0, 'patch' => 0]
            ],
        ];
    }

    /**
     * @dataProvider failingParse
     * @param $input
     */
    public function testParseFails($input)
    {
        $this->assertFalse(SemverParser::parse($input));
        $this->assertFalse(SemverParser::isParsable($input));
    }

    /**
     * These test cases are based on the source code on the following link published under the MIT license.
     * @see https://github.com/composer/semver/blob/master/tests/VersionParserTest.php
     * @license MIT
     * @return array
     */
    public function failingParse()
    {
        return [
            'forces w.x.y.z'                => ['1.0-dev'],
            'forces w.x.y.z/2'              => ['0'],
            'strips v/datetime'             => ['v20100102', '20100102'],
            'parses dates w/ -'             => ['2010-01-02', '2010.01.02'],
            'parses numbers'                => ['2010-01-02.5', '2010.01.02.5'],
            'parses dates y-m as classical' => ['2010.01'],
            'parses datetime'               => ['20100102-203040'],
            'parses dt+number'              => ['20100102203040-10'],
            'parses dt+patch'               => ['20100102-203040-p1'],
            'parses master'                 => ['dev-master'],
            'parses trunk'                  => ['dev-trunk'],
            'parses branches'               => ['1.x-dev'],
            'parses arbitrary'              => ['dev-feature-foo'],
            'parses arbitrary/2'            => ['DEV-FOOBAR'],
            'parses arbitrary/3'            => ['dev-feature/foo'],
            'parses arbitrary/4'            => ['dev-feature+issue-1'],
            'ignores aliases'               => ['dev-master as 1.0.0'],
        ];
    }
}
