<?php

namespace app\modules\users\helpers;

use Yii;

class Password
{
    /**
     * Wrapper for yii security helper method.
     *
     * @param $password
     *
     * @return string
     */
    public static function hash($password)
    {
        return Yii::$app->security->generatePasswordHash($password, Yii::$app->getModule('user')->cost);
    }

    /**
     * Wrapper for yii security helper method.
     *
     * @param $password
     * @param $hash
     *
     * @return bool
     */
    public static function validate($password, $hash)
    {
        return Yii::$app->security->validatePassword($password, $hash);
    }


    public static function generate($length)
    {
        if  ($length < 4) {
            throw new \RuntimeException('"' . __CLASS__ . '::' . __METHOD__ . '" too small length');
        }

        $password = '';
        $sets = [
            ['A','B','C','D','E','F','G','H','J','K','M','N','P','Q','R','S','T','U','V','W','X','Y','Z'],
            ['a','b','c','d','e','f','g','h','j','k','m','n','p','q','r','s','t','u','v','w','x','y','z'],
            ['2','3','4','5','6','7','8','9'],
        ];

        foreach ($sets as $set) {
            $password .= $set[array_rand($set)];
        }

        for ($i = 0; $i < $length - count($sets); $i++) {
            $set = $sets[array_rand($sets)];
            $password .= $set[array_rand($set)];
        }

        return str_shuffle($password);
    }

}