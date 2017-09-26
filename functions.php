<?php
require_once 'simplehtmldom_1_5/simple_html_dom.php';

function dodaj_anchor_i_rel($tab,$kolumny,$url_anchora){
    $content = '';
    $w = 0;
    foreach ($tab as $item){
        for($i=0;$i<count($item);$i++){
            if(!empty($item[$i][1])){
                if ($w == 0){
                    for ($j=0;$j<=($kolumny-1);$j++){                      
                        if ($j == 2){ $content .= "DOFOLLOW/ NOFOLLOW;ANCHOR;";}
                        $content .= $item[$i][$j].";";
                    } 
                    $w++;
                    //echo $content;exit;
                }else { 
                    for ($j=0;$j<=($kolumny-1);$j++){
                        if (!isset ($item[$i][$j])){$item[$i][$j] = null;}
                        if ($j == 2 && $item[$i][1] != null){
                            $strona = $item[$i][1];
                            $strona_content = file_get_html($strona);
                            
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
                            
                            //pobieram META tagi ze strony
                            $tags = get_meta_tags($strona);
                            
                            //jesli w tagach istnieje atrybut robots 
                            if (isset($tags['robots'])){ $rel_robots = $tags['robots'];} else { $rel_robots = "";} 
            
                            if(isset($rel_a) && !empty($rel_a)){
                                //echo $rel_a;
                                $content .= $rel_a;
                            }           
                            if(isset($rel_a) && !empty($rel_a) && isset($rel_robots) && !empty($rel_robots)){
                                //echo " | ";
                                $content .= " | ";
                            }   
                            if(isset($rel_robots) && !empty($rel_robots)){
                                //echo $rel_robots."<br>";
                                $content .= $rel_robots.";";
                            }else { $content .= ";";}
            
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
                                $content .= $anchor.";"; 
                                $anchor = "";
                            }else { $content .= ";";}
                            
                        }
                        $content .= $item[$i][$j].";";
                    }
                    
                }
               $content .= "\n";
            }
        }
    }
    return string_w_tablice($content);     
}
function dodaj_link($tab,$kolumny){
    $content = '';
    $w = 0;
    foreach ($tab as $item){
        for($i=0;$i<count($item);$i++){
            if(!empty($item[$i][1])){
                if ($w == 0){
                    for ($j=0;$j<=($kolumny-1);$j++){                      
                        if ($j == 2){ $content .= "LINK;";}
                        $content .= $item[$i][$j].";";
                        $w++;
                    }
                }else { 
                    for ($j=0;$j<=($kolumny-1);$j++){
                        if (!isset ($item[$i][$j])){$item[$i][$j] = null;}
                        if ($j == 2){$content .= "=HIPERŁĄCZE(\"".$item[$i][1]."\");";}
                        $content .= $item[$i][$j].";";
                    }
                }
                $content .= "\n";
            }
               
        }
    }
    return string_w_tablice($content);     
}

function sprawdz_czy_istnieja($tab,$kolumny){
    $content = '';  
    foreach ($tab as $item){
        for($i=0;$i<count($item);$i++){
            if(!empty($item[$i][1]))
            {
                if(checkOnline($item[$i][1])){ 
                    if(!is_404($item[$i][1])){
                        for ($j=0;$j<=($kolumny-1);$j++){
                        if (!isset ($item[$i][$j])){
                            $item[$i][$j] = null;
                        }
                        $content .= $item[$i][$j].";";
                        }
                    $content .= "\n";
                    }
                }elseif ($item[0]){
                    for ($j=0;$j<=($kolumny-1);$j++){
                        if (!isset ($item[$i][$j])){
                            $item[$i][$j] = null;
                        }
                        $content .= $item[$i][$j].";";
                        }
                    $content .= "\n";
                }
            }   
        }
    }
    return string_w_tablice($content);     
}

function string_w_tablice($content){
    $tab_pom = array();
    $content = explode("\n", $content);
    array_pop($content);
    $content = str_replace(array("\r", "\n"), '', $content);
    
    foreach($content as $line){
        //przypisuje do zmiennej exp zawartość elementów i exploduje po średnikach
        $exp = explode(";", $line);

        //przypisuje zmiennej $tab alias oraz zawartosc talicy exp
        $tab_pom[$exp[0]][] = $exp;
    }
    return $tab_pom;
}

function checkOnline($domain) {
   $curlInit = curl_init($domain);
   curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
   curl_setopt($curlInit,CURLOPT_HEADER,true);
   curl_setopt($curlInit,CURLOPT_NOBODY,true);
   curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);

   //get answer
   $response = curl_exec($curlInit);

   curl_close($curlInit);
   if ($response) return true;
   return false;
}

function is_404($url) {
    $handle = curl_init($url);
    curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

    /* Get the HTML or whatever is linked in $url. */
    $response = curl_exec($handle);

    /* Check for 404 (file not found). */
    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    curl_close($handle);

    /* If the document has loaded successfully without any redirection or error */
    if ($httpCode >= 200 && $httpCode < 300) {
        return false;
    } else {
        return true;
    }
}

function wszystkie_wiersze_normalnie($tab,$kolumny){
    $content = '';
    foreach ($tab as $item){
        for($i=0;$i<count($item);$i++){
            for ($j=0;$j<=($kolumny-1);$j++){
                if (!isset ($item[$i][$j])){
                    $item[$i][$j] = null;
                }
                $content .= $item[$i][$j].";";
            }
            $content .= "\n";
        }
    }
    return string_w_tablice($content);    
}


function domyslny ($ilosc_powt_pierw_kolumny,$ile_wierszy_wypisac,$czy_wypisac_domain = "FALSE",$kolumny,$tab,$czy_usunac = "FALSE"){
    $content = '';
    foreach ($tab as $item){
        if (count($item) >= $ilosc_powt_pierw_kolumny ){
            for($i=0;$i<$ile_wierszy_wypisac;$i++){
                for ($j=0;$j<=($kolumny-1);$j++){
                    if (!isset ($item[$i][$j])){
                        $item[$i][$j] = null;
                    }else {
                        if ($czy_usunac == "TRUE"){
                            if($i == 0 && $j == 0 && $czy_wypisac_domain == "TRUE"){
                                //echo "domain:".$item[$i][$j].";";
                                $content .= "domain:".$item[$i][$j].";";
                            }else {
                                if ($i == 0 && $j == 0 && $czy_wypisac_domain == "FALSE"){
                                    //echo $item[$i][$j].";";
                                    $content .= $item[$i][$j].";";
                                }
                                elseif($i > 0 && $j == 0 && $czy_wypisac_domain == "FALSE"){
                                    //echo ";";
                                    $content .= ";";
                                }
                                elseif ($j == 1 && $czy_wypisac_domain == "TRUE"){
                                    //echo $item[$i][$j].";";
                                    $content .= $item[$i][$j].";";
                                }else{
                                    if($j==0){
                                        //echo ";";
                                        $content .= ";";
                                    }
                                    else{
                                        //echo $item[$i][$j].";";
                                        $content .= $item[$i][$j].";";
                                    }
                                }
                            }
                        }elseif($czy_usunac == "FALSE"){
                            if($i == 0 && $j == 0 && $czy_wypisac_domain == "TRUE"){
                                //echo "domain:".$item[$i][$j].";";
                                $content .= "domain:".$item[$i][$j].";";
                            }else {
                                //echo $item[$i][$j].";";
                                $content .= $item[$i][$j].";";
                            }
                        }
                    }               
                }
                //echo "\n";
                $content .= "\n";
            }       
        }elseif (count($item) <= $ilosc_powt_pierw_kolumny){
            for($i=0;$i<count($item);$i++){
                for ($j=0;$j<=($kolumny-1);$j++){
                    if (!isset ($item[$i][$j])){
                        $item[$i][$j] = null;
                    }
                $content .= $item[$i][$j].";";
                }
            $content .= "\n";
            }      
        }
    }
    return string_w_tablice($content); 
}
?>