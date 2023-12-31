<?php
// Src/Helpers/StringUtil.php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Helpers;


class StringUtil
{
    public function displayFirst150Characters( string $string ) : string
    {
        $trimmedString = mb_substr($string, 0, 150 );

        return $trimmedString;
    }

    public function postExcerpt( string $string, int $characterNumber) : string
    {
        $trimmedString = mb_substr($string, 0, $characterNumber );

        return $trimmedString . ' ...';
    }

    public function removeStringsSpaces(string $string) : string
    {
        $stringWithoutSpaces = str_replace(' ', '-', $string);
        return strtolower($stringWithoutSpaces);
    }

    public function capitalLetter(string $string) : string
    {
        $capitalLetter = strtoupper(substr($string, 0, 1)) . strtolower(substr($string, 1));
        return $capitalLetter;
    }

    public function maskEmail(string $emailAddress) : string
    {
        $addressParts = explode('@', $emailAddress);
        $localPart = $addressParts[0];
        $domain = $addressParts[1];
    
        $firstTwoLetters = substr($localPart, 0, 2);
        $tld = end(explode('.', $domain));
    
        $maskedDomain = str_repeat('*', strlen($domain) - strlen($tld) - 1);
    
        $maskedEmail = $firstTwoLetters . str_repeat('*', strlen($localPart) - 2) . '@' . $maskedDomain . '.' . $tld;
    
        return $maskedEmail;
    }

    public function removeAccentsAndSpecialCharacters(string $texte) : string
    {
        $texte = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texte);
        $texte = preg_replace('/[^a-zA-Z0-9\s]/', '', $texte);
        
        return $texte;
    }

    public function getForamtedDate(string $dateToBeFormatted) :string|null
    {
        if ($dateToBeFormatted === null) {
            return "Date inconnue";
        }    
        
        $date = new \DateTime($dateToBeFormatted);
    
        $formatter = new \IntlDateFormatter(
            'fr_FR', 
            \IntlDateFormatter::LONG, 
            \IntlDateFormatter::NONE
        );
    
        return $formatter->format($date);
    }

    public function getHourFromDateTime(string $datetime) : string
    {
        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
        $heure = $date->format('H:i:s');
        return $heure; 
    }
}


