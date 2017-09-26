<?php
require_once 'functions.php';
require_once 'simplehtmldom_1_5/simple_html_dom.php';

$url_anchora = "lamai.pl";


if(checkOnline($strona)){ 
    echo "ONLINE<br>";
    if(is_404($strona)){
        echo "ERROR 404<br>";
    } else {
        echo "OK<br>";
    
        echo "<hr>";
        $strona_content = file_get_html($strona);
        
        if (preg_match("/\<\?xml/", $strona_content)){
            echo "RSS<br>";
        }else {
            foreach($strona_content->find('a[href="https://www.'.$url_anchora.'/"]') as $element){
                $anchor = $element->innertext;
                $rel_a = $element->rel;
            }
            if (empty($anchor)){
                foreach($strona_content->find('a[href="http://www.'.$url_anchora.'/"]') as $element){
                    $anchor = $element->plaintext;
                    $rel_a = $element->rel;
                }      
            }
            if (empty($anchor)){
                foreach($strona_content->find('a[href="http://'.$url_anchora.'/"]') as $element){
                    $anchor = $element->plaintext;
                    $rel_a = $element->rel;
                }      
            }
            if (empty($anchor)){
                foreach($strona_content->find('a[href="www.'.$url_anchora.'/"]') as $element){
                    $anchor = $element->plaintext;
                    $rel_a = $element->rel;
                }      
            }
            if (empty($anchor)){
                foreach($strona_content->find('a[href="https://www.'.$url_anchora.'"]') as $element){
                $anchor = $element->innertext;
                $rel_a = $element->rel;
                }
            }
            if (empty($anchor)){
                foreach($strona_content->find('a[href="http://www.'.$url_anchora.'"]') as $element){
                    $anchor = $element->plaintext;
                    $rel_a = $element->rel;
                }      
            }
            if (empty($anchor)){
                foreach($strona_content->find('a[href="http://'.$url_anchora.'"]') as $element){
                    $anchor = $element->plaintext;
                    $rel_a = $element->rel;
                }      
            }
            if (empty($anchor)){
                foreach($strona_content->find('a[href="www.'.$url_anchora.'"]') as $element){
                    $anchor = $element->plaintext;
                    $rel_a = $element->rel;
                }      
            }
            
            $tags = get_meta_tags($strona);
            if (isset($tags['robots'])){ $rel_robots = $tags['robots'];} 
            
            if(isset($rel_a) && !empty($rel_a)){
                echo $rel;
            }           
            if(isset($rel_a) && !empty($rel_a) && isset($rel_robots) && !empty($rel_robots)){
                echo " | ";
            }   
            if(isset($rel_robots) && !empty($rel_robots)){
                echo $rel_robots."<br>";
            }
            
            if (isset($anchor) && !empty($anchor)){
                if (preg_match("/\<img/", $anchor)){
                    //wyłuskiwanie alt from img
                    $startPoint = '<img';
                    $endPoint = 'alt="';
                    $startPoint2 = '"';
                    $endPoint2 = '/>';
                    $anchor = preg_replace('#('.preg_quote($startPoint).')(.*)('.preg_quote($endPoint).')#si', '', $anchor);
                    $anchor = "ALT ".preg_replace('#('.preg_quote($startPoint2).')(.*)('.preg_quote($endPoint2).')#si', '', $anchor);
                }
                echo $anchor."<br/>"; 
            }
            
        }      
        
        
    }
}else {
    echo "OFFLINE";
}