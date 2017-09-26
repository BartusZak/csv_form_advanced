<?php
//Fatal error: Allowed memory size of 16777216 bytes exhausted (tried to allocate 256 bytes) i
ini_set('memory_limit', '256M');
//TO DO LIST
// - dodać czytanie ile jest max kolumn
// - dodawanie selektywne po kolumnach

require_once 'functions.php';
//echo "<pre>";
//echo var_dump($_FILES['plik']);
//echo "</pre>";

//max available uploaded file size in MB
$max_file_size = 2;

ini_set('max_execution_time', 300); //300 seconds = 5 minutes

$kolumny = ((isset($_POST['kolumny']) && !empty($_POST['kolumny']))? $_POST['kolumny'] : 21);
$uploaded_file_name = ((isset($_FILES['plik']['name']) && !empty($_FILES['plik']['name']))? filter_var($_FILES['plik']['name'], FILTER_SANITIZE_STRING) : "plik.csv");
$uploaded_file_ext = pathinfo($uploaded_file_name, PATHINFO_EXTENSION);  

//available extensions
$mimes = array('csv','txt');
$mimes_print = '';

//storing available extensions in var to display later
foreach($mimes as $ext){ $mimes_print .= $ext." ";}

//if the file exist
if (isset($_FILES['plik'])){  
    if ($_FILES['plik']['size'] <= ($max_file_size)*1024*1024){
        switch($_FILES['plik']['error']){
            case 0:{
                if(isset($_FILES['plik']['name']) && !empty($_FILES['plik']['name'])){
                    if(in_array($uploaded_file_ext,$mimes) ) {
                        
                        //dodaje zmienna przechowujaca tablice
                        $tab = array();
                        
                        //wczytuje zawartość pliku do zmiennej
                        $content = file_get_contents($_FILES['plik']['tmp_name']);
                        
                        //zamieniam string w tablice
                        $tab = string_w_tablice($content);
                        
                        //ustawiam nazwe nowego pliku
                        $new_file = ((isset($_POST['new_file']) && !empty($_POST['new_file']))? (filter_var($_POST['new_file'], FILTER_SANITIZE_STRING)) : "min_".$uploaded_file_name);
                                     
                        //jesli sosob minifikacji - domyślny
                        if(isset($_POST['domyslny'])){
                            if(isset($_POST['ilosc_powt']) && !empty($_POST['ilosc_powt']) && isset($_POST['ile_wierszy']) && !empty($_POST['ile_wierszy'])){
                                if($_POST['ilosc_powt'] >= $_POST['ile_wierszy']){
                                    $tab = domyslny($_POST['ilosc_powt'], $_POST['ile_wierszy'], $_POST['czy_wypisac_domain'], $kolumny, $tab, $_POST['czy_usunac_duplikaty']);                               
                                }else{
                                    echo "<div class='alert alert-danger'><strong>Ilość wystąpień</strong> zawartości nie może być mniejsza od <strong>ilości wierszy</strong> które chcesz wyświetlić!</div>";
                                    break;
                                }
                            }else{
                                $tab = domyslny(4, 1, "TRUE", $kolumny, $tab, "FALSE");
                            }
                            

                        }else{
                            
                                $tab = wszystkie_wiersze_normalnie($tab, $kolumny);
                        }
                        
                        //jesli zaznaczony checkbox "Dodatkowe opcje -> Sprawdz czy istieja"
                        if(isset($_POST['weryfikacja'])){
                            $tab = sprawdz_czy_istnieja($tab, $kolumny);
                        }
                        //jesli zaznaczony checkbox "Dodatkowe opcje -> Dodaj Link"
                        if(isset($_POST['dodaj_link'])){
                            $tab = dodaj_link($tab, $kolumny);
                            $kolumny++;
                        }
                        
                        if(isset($_POST['dodaj_anchor_i_rel'])){
                            $domain = filter_var($_POST['adres_domeny'], FILTER_SANITIZE_STRING);
                            $tab = dodaj_anchor_i_rel($tab, $kolumny,$domain);
                            $kolumny += 2;
                        }
                        
                        if (isset($tab) && !empty($tab)){
                            foreach ($tab as $item){
                                for($i=0;$i<count($item);$i++){
                                    for ($j=0;$j<=($kolumny-1);$j++){
                                        if (!isset ($item[$i][$j])){
                                            $item[$i][$j] = null;
                                        }
                                        echo $item[$i][$j].";";
                                    }
                                    echo "\n";
                                }
                            }
                            header("Content-type: text/csv");
                            header('Content-Disposition: attachment;filename="'.$new_file.'";');
                            exit;
                        }else {
                            echo "<div class='alert alert-danger'>Pusta tablica (powiadom administratora)!</div>";
                        }

                    }else{
                        echo "<div class='alert alert-danger'>Niedozwolone rozszerzenie piku!</div>";
                        echo "<div class='alert alert-info'>Dozwolone rozszerzenia pliku: <strong>".$mimes_print."</strong>.</div>";
                    }
                }else{
                    echo "Plik który chcesz przesłać musi mieć nazwę!";
                }
                break;
            }
            case 1:{
                echo "<div class='alert alert-danger'>Za duży plik (php.ini)</div>";
            break;
            }
            case 2:{
                echo "<div class='alert alert-danger'>Zbyt duży plik <strong>".$max_file_size_uploaded."</strong> KB.</div>";
                echo "<div class='alert alert-info'>Dozwolona wielkość pliku: <strong>".$max_file_size."</strong> KB.</div>";
                break;
            }
            case 3:{
                echo "<div class='alert alert-danger'>Uszkodzony plik <strong>".$uploaded_file_name."</strong>.</div>";
                break;
            }
            case 4:{
                echo "<div class='alert alert-danger'>Nie wybrano pliku!</div>";
                break;
            }
            default:{
                echo "<div class='alert alert-danger'>Błąd!</div>";
            }
        }
     }else{
         echo "<div class='alert alert-danger'>Zbyt duży plik <strong>".$uploaded_file_name."</strong>.</div>";
         echo "<div class='alert alert-info'>Dozwolona wielkość pliku: <strong>".($max_file_size*1024)."</strong> KB .<br>Wielkość przesłanego pliku: <strong>".round((($_FILES['plik']['size'])/1024), 0)."</strong> KB</div>";
     }
     }
?>