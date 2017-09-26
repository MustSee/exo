<?php

namespace TinyUrl\MainBundle\Helpers;

class StringUtils
{
    public static function generateRandomString($length = 6)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /*function checkIfAlreadyExists($randomShortCode)
    {
        $shortCode = $this->get('doctrine')
            ->getRepository('TinyUrl\MainBundle\Entity\Link')
            ->findOneBy(array('shortCode' => $randomShortCode));
        if ($shortCode == null) {
            return false;
        } else {
            return true;
        }
    }*/

}