<?php
namespace Test;

class DataProviders
{
    /**
     * @return array
     */
    public static function invalidPregPatterns()
    {
        return array(
            array('/{2,1}/'),
            array('/)unopened.group/'),
            array('/un(closed.group/'),
            array('/*starting.quantifier/'),
            array(' /\/'),
            array('/\/'),
            array('/\\/'),
            array('/(/'),
            array('/{1}/'),
        );
    }


    public function invalidUtf8Sequences()
    {
        return array(
            array('Invalid 2 Octet Sequence', "\xc3\x28"),
            array('Invalid Sequence Identifier', "\xa0\xa1"),
            array('Invalid 3 Octet Sequence (in 2nd Octet)', "\xe2\x28\xa1"),
            array('Invalid 3 Octet Sequence (in 3rd Octet)', "\xe2\x82\x28"),
            array('Invalid 4 Octet Sequence (in 2nd Octet)', "\xf0\x28\x8c\xbc"),
            array('Invalid 4 Octet Sequence (in 3rd Octet)', "\xf0\x90\x28\xbc"),
            array('Invalid 4 Octet Sequence (in 4th Octet)', "\xf0\x28\x8c\x28"),
        );
    }

}
