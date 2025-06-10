<?php

namespace app\models;

use DOMDocument;
use DOMXPath;

class Fb2Converter
{
    public static function convertToTxt($fb2Content)
    {
        $dom = new DOMDocument();
        $dom->loadXML($fb2Content, LIBXML_NOERROR | LIBXML_NOWARNING);
        
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('fb2', 'http://www.gribuser.ru/xml/fictionbook/2.0');
        
        $text = '';
        
        // Получаем заголовок
        $title = $xpath->query('//fb2:book-title');
        if ($title->length > 0) {
            $text .= $title->item(0)->textContent . "\n\n";
        }
        
        // Получаем автора
        $author = $xpath->query('//fb2:author/fb2:firstName | //fb2:author/fb2:lastName');
        if ($author->length > 0) {
            $text .= "Автор: ";
            foreach ($author as $name) {
                $text .= $name->textContent . " ";
            }
            $text .= "\n\n";
        }
        
        // Получаем основной текст
        $body = $xpath->query('//fb2:body');
        if ($body->length > 0) {
            $paragraphs = $xpath->query('.//fb2:p', $body->item(0));
            foreach ($paragraphs as $p) {
                $text .= $p->textContent . "\n\n";
            }
        }
        
        return $text;
    }
} 