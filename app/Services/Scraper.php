<?php

namespace App\Services;

class Scraper {

    public $exchange;
    public $timePeriod;
    public $baseUrl;
    public $curl;

    public function getCurl($url) {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        $resp = curl_exec($curl);

        curl_close($curl);

        return $resp;
    }
    public function getLinksInClass($html, $class, $includeBase = false) {
        $urls = [];
        while (strpos($html, $class) !== false) {
            $htmlToEndFromClass = substr($html, strpos($html, $class));
            $urlEndpoint = $this->getNextHref($htmlToEndFromClass);

            if ($includeBase) {
                $urls[] = $this->baseUrl.$urlEndpoint;
            }
            else {
                $urls[] = $urlEndpoint;
            }
            $html = substr($htmlToEndFromClass, strpos($htmlToEndFromClass, "href=")+6);
        }
        return $urls;
    }

    public function getLinksWithClass($html, $class) {
        $urls = [];
        while (strpos($html, $class) !== false) {
            $htmlToEndFromClass = substr($html, strpos($html, $class));
            $urlEndpoint = $this->getNextHref($htmlToEndFromClass);
            $urls[] = $this->baseUrl.$urlEndpoint;
            $html = substr($htmlToEndFromClass, strpos($htmlToEndFromClass, "href=")+6);
        }
        return $urls;
    }

    public function getAllLinksBetweenText($text, $start, $end) {
        $text = $this->getInBetween($text, $start, $end);
        return $this->getAllLinksInText($text);
    }

    public function getLinkBetweenText($text, $start, $end) {
        $text = $this->getInBetween($text, $start, $end);
        return $this->getNextHref($text);
    }

    public function getAllLinksInText($text) {
        $urls = [];
        $text = strtolower($text);
        while ($this->inString($text, 'href') && $this->inString($text, '<a')) {
            $text = substr($text, strpos($text, '<a'));

            if (strpos($text, 'href') < 100) {
                $urlEndpoint = $this->getNextHref($text);
                $urls[] = $this->baseUrl.$urlEndpoint;
            }
            if ($this->inString($text, '</a>')) {
                $text = substr($text, strpos($text, '</a>') + 3);
            }
            else {
                $text = '';
            }
        }
        return $urls;
    }

    public function getLinksWithWebsiteEndpoints($text, $website) {
        $allLinks = $this->getAllLinksInText($text);
        $endpointLinks = [];

        foreach ($allLinks as $link) {
            $firstCharacter = $this->firstCharacter($link);
            $firstThree = strtolower(substr($link, 0, 3));

            if ($firstCharacter != '#'
                && $firstThree != 'htt') {
                    if ($firstCharacter == '/') {
                        $endpointLinks[] = $link;
                    }
                    else {
                        $endpointLinks[] = '/'.$link;
                    }
            }
            elseif ($this->inString($link, $website)) {
                $endpointLinks[] = $link;
            }
        }
        return array_unique($endpointLinks);
    }

    public function firstCharacter($text) {
        return substr($text,0, 1);
    }

    //
    public function getNextHref($text) {
        $linkToEnd = substr($text, strpos($text, "href=")+6);
        return substr($linkToEnd, 0, strpos($linkToEnd, '"'));
    }

    public function getInBetween($text, $start, $end) {
        if ($end == '"') {
            $text = str_replace('"', "*", $text);
            $end = "*";
        }

        $startToEnd = substr($text, strpos($text, $start));
        return substr($startToEnd, strpos($startToEnd, $start) + strlen($start), strpos($startToEnd, $end) -strlen($start) );
    }

    public function getWithinTagOpenBased($text, $tagOpen) {
        $startToEnd = substr($text, strpos($text, $tagOpen));
        $openCloseToEnd = substr($startToEnd, strpos($startToEnd, '>')+1);

        return substr($openCloseToEnd, 0, strpos($openCloseToEnd, '</'));
    }

    public function getToEnd($text, $start) {
        return substr($text, strpos($text, $start)+strlen($start));
    }

    public function returnIfExistsElseBlank($text, $start, $end) {
        if (strpos($text, $start) !== false) {
            return $this->getInBetween($text, $start, $end);
        }
        else {
            return "";
        }
    }

    public function reverseGetInBetween($text, $start, $end) {
        //start is end of actual text

        $reverseText = strrev($text);
        $reverseStart = strrev($start);
        $reverseEnd = strrev($end);

        return strrev($this->getInBetween($reverseText, $reverseStart, $reverseEnd));
    }

    public function getContactLink($text) {

        while (strpos(strtoupper($text), 'CONTACT') !== false) {
            $textToContact = substr($text, 0, strpos(strtoupper($text), 'CONTACT'));

            $reverseTextToContact = strrev($textToContact);

            if (strpos(strtoupper($reverseTextToContact), 'A<') !== false) {
                $anchorPosition = strpos(strtoupper($reverseTextToContact), 'A<');

                if ($anchorPosition < 50) {
                    $anchorSubtract = $anchorPosition+2;
                    $anchorOpenToEnd = substr($text, strpos(strtoupper($text), 'CONTACT')-$anchorSubtract);

                    $anchor = substr($anchorOpenToEnd, 0, strpos($anchorOpenToEnd, '</a')+4);

                    return $this->getUrlFromAnchor($anchor);

                    break;
                }
                else {
                    $text = substr($text, strpos(strtoupper($text), 'CONTACT')+7);
                }
            }
            else {
                $text = substr($text, strpos(strtoupper($text), 'CONTACT')+7);
            }
        }
    }

    public function getNextTextBetweenTagsThatHasString($needle, $haystack) {
        $positionOfNeedle = strpos($haystack, $needle);
        $textUntilNeedle = substr($haystack, 0, $positionOfNeedle);

        $textUntilNeedleReversed = strrev($textUntilNeedle);
        $textUntilTagReversed = substr($textUntilNeedleReversed, 0, strpos($textUntilNeedleReversed, '>'));
        $textUntilTag = strrev($textUntilTagReversed);

        $textAfterNeedle = substr($haystack, $positionOfNeedle);
        $textAfterNeedle = substr($textAfterNeedle, 0, strpos($textAfterNeedle, '<'));
        return $textUntilTag.$textAfterNeedle;

    }

    public function getUrlFromAnchor($anchor) {
        $anchor = str_replace('"', "'", $anchor);
        $anchor = str_replace(' ', '', $anchor);

        $linkStartToEnd = substr($anchor, strpos($anchor, 'href')+6);
        $link = substr($linkStartToEnd, 0, strpos($linkStartToEnd, "'"));

        if (substr($link, 0, 1) == '/') {
            return $this->baseUrl.$link;
        }
        else {
            return $link;
        }
    }

    public function stripFromText($text, $start, $end) {
        if (strpos(strtoupper($text), $start) !== false) {
            $goodStartOfText = substr($text, 0, strpos($text,$start));

            $badUntilEnd = substr($text, strpos($text,$start));

            $afterBadUntilEnd = substr($badUntilEnd, strpos($badUntilEnd,$end)+strlen($end));

            return $goodStartOfText.$afterBadUntilEnd;
        }
        else {
            return $text;
        }
    }

    public function stripSetsOfValuesWhereSetItemsRelated($text, $searchItems) {
        $response = [];
        $index = 0;

        while (strpos($text, $searchItems[0]['start']) !== false) {
            foreach($searchItems as $search) {
                $response[$index][$search['name']] = $this->getInBetween($text, $search['start'], $search['end']);
            }
            $text = $this->getToEnd($text, end($searchItems)['start']);
            $index++;
        }
        return $response;
    }

    public function getHostPeriodPosition($htmlText) {
        $dotFound = false;
        $atFound = false;
        $position = -1;

        while (!$dotFound && $position < 500) {
            $position = $position + 1;

            $currentCharacter = substr($htmlText, $position, 1);

            if (!$atFound) {
                if ($currentCharacter == '@') {
                    $atFound = true;
                }
            }
            else {
                if ($currentCharacter == '.') {
                    $dotFound = true;
                }
            }
        }
        return $position;
    }

    public function checkEmailExtension($email) {
        $emailRevers = strrev($email);

        $reverseExtension = substr($emailRevers, 0, strpos($emailRevers, '.'));

        $extension = strtolower(strrev($reverseExtension));

        if (strlen($extension) < 2) {
            return false;
        }
        else {
            if ( in_array($extension, ['jpeg', 'jpg', 'png', 'gif', 'svg'])) {
                return false;
            }
            else {
                return true;
            }
        }
    }

    public function getEmailAddressesInLink($link) {
        $html = $this->getCurl($link);
        $emails = [];

        str_replace('%40','@', $html);


        while (strpos(strtoupper($html), '@') !== false) {
            $atPosition = strpos($html, "@");

            $foundEmailStart = false;

            while (!$foundEmailStart) {
                $atPosition--;
                $previousCharacter = substr($html, $atPosition, 1);

                if (!ctype_alnum($previousCharacter) && !in_array($previousCharacter, ['.', '-', '_'])) {
                    $html = substr($html, $atPosition+1);
                    $foundEmailStart = true;
                }
            }

            $emailDotPosition = $this->getHostPeriodPosition($html);
            $foundEmailEnd = false;

            while (!$foundEmailEnd) {
                $emailDotPosition++;
                $nextCharacter = substr($html, $emailDotPosition, 1);

                if (!ctype_alnum($nextCharacter)) {
                    $email = substr($html,0,$emailDotPosition);
                    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !in_array($email, $emails)) {
                        $validExtensionCheck = $this->checkEmailExtension($email);

                        if ($validExtensionCheck) {
                            $emails[] = $email;
                        }
                    }
                    $foundEmailEnd = true;
                }
            }
            $html = substr($html, $emailDotPosition);
        }
        return $emails;
    }

    public function getCountOfValuesExistingInText($text, $values) {
        $valueExistCount = 0;
        foreach ($values as $value) {
            if (strpos(strtoupper($text), strtoupper($value)) !== false) {
                $valueExistCount++;
            }
        }
        return $valueExistCount;
    }

    public function inString($text, $needle) {
        if (strpos(strtoupper($text), strtoupper($needle)) !== false) {
            return true;
        }
        else {
            return false;
        }
    }

    public function postRequest($url, $fields) {
        $this->curl = curl_init();

        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl,CURLOPT_POST, 1);
        curl_setopt($this->curl,CURLOPT_POSTFIELDS, json_encode($fields));

        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, NULL);

        //Added to Get Curl Info
        //curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);

        $resp = curl_exec($this->curl);

        // $headers = curl_getinfo($this->curl, CURLINFO_HEADER_OUT);
        return json_decode($resp);
    }

    public function sleepRandomSeconds($starEnd = [5, 45]) {
        sleep(rand($starEnd[0], $starEnd[1]));
    }

    public function getTextBeforeString($needle, $haystack) {
        return substr($haystack,0, strpos($haystack, $needle));
    }

    public function getTextAfterString($needle, $haystack) {
        return substr($haystack,strpos($haystack, $needle)+strlen($needle)+1);
    }


}
