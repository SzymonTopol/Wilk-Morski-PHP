<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style_wilk.css">
</head>
<body>
    
</body>
</html>

<form method="post">
<?php
session_start();

if (!isset($_SESSION['stos_kart'])) {
    $_SESSION['stos_kart'] = ['cz_st_1','cz_st_2','cz_st_3', 'cz_st_4', 'cz_st_5', 'cz_st_6',
    'nb_st_1','nb_st_2','nb_st_3', 'nb_st_4', 'nb_st_5', 'nb_st_6',
    'zi_st_1','zi_st_2','zi_st_3', 'zi_st_4', 'zi_st_5', 'zi_st_6',
    'po_st_1','po_st_2','po_st_3', 'po_st_4', 'po_st_5', 'po_st_6',
    
    'cz_tow_1','cz_tow_2','cz_tow_3', 'cz_tow_4', 'cz_tow_5', 'cz_tow_6',
    'nb_tow_1','nb_tow_2','nb_tow_3', 'nb_tow_4', 'nb_tow_5', 'nb_tow_6',
    'zi_tow_1','zi_tow_2','zi_tow_3', 'zi_tow_4', 'zi_tow_5', 'zi_tow_6',
    'po_tow_1','po_tow_2','po_tow_3', 'po_tow_4', 'po_tow_5', 'po_tow_6'];
}

if (!isset($_SESSION['dostawa'])) {
    $_SESSION['dostawa'] = 0;
}

if (!isset($_SESSION['ostatnia_dostawa'])) {
    $_SESSION['ostatnia_dostawa'] = false;
}

if (!isset($_SESSION['odkryte_karty'])) {
    $_SESSION['odkryte_karty'] = [];
}

$ilosc_graczy = 4;
for ($i=0; $i < $ilosc_graczy; $i++) { 
    if (!isset($_SESSION['karty_w_rece'][$i])) {
        $_SESSION['karty_w_rece'][$i] = [];
    }
}

for ($i=0; $i < $ilosc_graczy; $i++) { 
    if (!isset($_SESSION['dostarczone_karty'][$i])) {
        $_SESSION['dostarczone_karty'][$i] = [];
    }
}

if (!isset($_SESSION['aktualny_gracz'])) {
    $_SESSION['aktualny_gracz'] = 0;
}

################## Początek gry ##################

if (isset($_POST['start'])) {

    $_SESSION['stos_kart'] = ['cz_st_1','cz_st_2','cz_st_3', 'cz_st_4', 'cz_st_5', 'cz_st_6',
    'nb_st_1','nb_st_2','nb_st_3', 'nb_st_4', 'nb_st_5', 'nb_st_6',
    'zi_st_1','zi_st_2','zi_st_3', 'zi_st_4', 'zi_st_5', 'zi_st_6',
    'po_st_1','po_st_2','po_st_3', 'po_st_4', 'po_st_5', 'po_st_6',
    
    'cz_tow_1','cz_tow_2','cz_tow_3', 'cz_tow_4', 'cz_tow_5', 'cz_tow_6',
    'nb_tow_1','nb_tow_2','nb_tow_3', 'nb_tow_4', 'nb_tow_5', 'nb_tow_6',
    'zi_tow_1','zi_tow_2','zi_tow_3', 'zi_tow_4', 'zi_tow_5', 'zi_tow_6',
    'po_tow_1','po_tow_2','po_tow_3', 'po_tow_4', 'po_tow_5', 'po_tow_6'];
    $_SESSION['odkryte_karty'] = [];
    $_SESSION['dostawa'] = 0;
    $_SESSION['ostatnia_dostawa'] = false;

    for ($i=0; $i < $ilosc_graczy; $i++) { 
            $_SESSION['karty_w_rece'][$i] = [];
            $_SESSION['dostarczone_karty'][$i] = [];
    }

    # Początkowe 4 karty
    for ($i = 0; $i < 4; $i++) {
        $rand_temp = mt_rand(0, count($_SESSION['stos_kart']) - 1);
        array_push($_SESSION['odkryte_karty'], $_SESSION['stos_kart'][$rand_temp]);
        unset($_SESSION['stos_kart'][$rand_temp]);
        $_SESSION['stos_kart'] = array_values($_SESSION['stos_kart']);
    }

    $_SESSION['aktualny_gracz'] = 0;
}

if (isset($_POST['zakoncz_dostawe'])){ //fragment kodu do zrobienia dla wszystkich graczy dostawy, nie ruszać do pętli, bo nie działa
    $_SESSION['dostawa'] +=1; //na koniec tury
    $_SESSION['aktualny_gracz'] +=1;
    if($_SESSION['aktualny_gracz'] > 3){
        $_SESSION['aktualny_gracz'] = 0;
    }
}

$koniec=false;

for ($i=0; $i < $ilosc_graczy; $i++) { //warunek zakończenia - oddanie pełnego zestawu kart
    if(count($_SESSION['dostarczone_karty'][$i])==8){
        $koniec=true;
        }
}

if (count($_SESSION['stos_kart']) < 16 and count($_SESSION['stos_kart']) == mt_rand(1, count($_SESSION['stos_kart']))) {
    $_SESSION['ostatnia_dostawa'] = true;

}

################## branie kart ##################

$przyciski = ['nr1', 'nr2', 'nr3', 'nr4'];

    foreach ($przyciski as $wybor) {
        if (isset($_POST[$wybor])) {
            $i = (int)substr($wybor, 2) - 1;

            array_push($_SESSION['karty_w_rece'][$_SESSION['aktualny_gracz']], $_SESSION['odkryte_karty'][$i]);
            unset($_SESSION['odkryte_karty'][$i]);

            $rand_temp = mt_rand(0, count($_SESSION['stos_kart']) - 1);
            $_SESSION['odkryte_karty'][$i] = $_SESSION['stos_kart'][$rand_temp];
            ksort($_SESSION['odkryte_karty']);
            unset($_SESSION['stos_kart'][$rand_temp]);
            $_SESSION['stos_kart'] = array_values($_SESSION['stos_kart']);
            $_SESSION['aktualny_gracz'] +=1;
            if($_SESSION['aktualny_gracz'] > 3){
                $_SESSION['aktualny_gracz'] = 0;
            }
        }
    }

    if (isset($_POST['losowa'])) {
        $rand_temp = mt_rand(0, count($_SESSION['stos_kart']) - 1);
        array_push($_SESSION['karty_w_rece'][$_SESSION['aktualny_gracz']], $_SESSION['stos_kart'][$rand_temp]);
        unset($_SESSION['stos_kart'][$rand_temp]);
        $_SESSION['stos_kart'] = array_values($_SESSION['stos_kart']);

        $_SESSION['aktualny_gracz'] +=1;
            if($_SESSION['aktualny_gracz'] > 3){
                $_SESSION['aktualny_gracz'] = 0;
            }
    }

    ################## Odrzucanie kart ##################

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        foreach ($_SESSION['karty_w_rece'][$_SESSION['aktualny_gracz']] as $karta){
            if (isset($_POST[$karta]) && $_SESSION['aktualny_gracz'] === $_SESSION['aktualny_gracz']) {
                unset($_SESSION['karty_w_rece'][$_SESSION['aktualny_gracz']][array_search($karta, $_SESSION['karty_w_rece'][$_SESSION['aktualny_gracz']])]);
                $_SESSION['karty_w_rece'][$_SESSION['aktualny_gracz']] = array_values($_SESSION['karty_w_rece'][$_SESSION['aktualny_gracz']]);
                $_SESSION['aktualny_gracz'] +=1;
                if($_SESSION['aktualny_gracz'] > 3){
                    $_SESSION['aktualny_gracz'] = 0;
                }
            }
        }
    }

####################################### MAIN #######################################
if($_SESSION['dostawa']==12 or (($_SESSION['dostawa']==4 or $_SESSION['dostawa']==8) and $koniec)){//koniec gry

    $wynik_koncowy = [];
    $pelny_zestaw = [];
    
    
    for ($x=0; $x < $ilosc_graczy; $x++) { 
        $punkty = 0;
        for ($i=0; $i < count($_SESSION['dostarczone_karty'][$x]); $i++) { 
            $nowe = explode("_",$_SESSION['dostarczone_karty'][$x][$i]);
            $punkty += (int)$nowe[2];
        }

        for ($i=0; $i < count($_SESSION['karty_w_rece'][$x]); $i++) { 
            $nowe = explode("_",$_SESSION['karty_w_rece'][$x][$i]);
            $punkty -= (int)$nowe[2];
        }

        array_push($wynik_koncowy,$punkty);

        if (count($_SESSION['dostarczone_karty'][$x]) == 8) {
            array_push($pelny_zestaw,true);
        }else {
            array_push($pelny_zestaw, false);
        }

    }

    ############# Nie mam pojęcia co tu się dzieje, czyli rozstrzyganie zwycięzcy #############
    
    function sortowanie($a, $b) {
        global $wynik_koncowy, $pelny_zestaw;
        
        if ($pelny_zestaw[$a] && !$pelny_zestaw[$b]) {
            return -1;
        } elseif (!$pelny_zestaw[$a] && $pelny_zestaw[$b]) {
            return 1;
        }
        
        if ($wynik_koncowy[$a] == $wynik_koncowy[$b]) {
            return 0;
        }
        return ($wynik_koncowy[$a] < $wynik_koncowy[$b]) ? 1 : -1;
    }

    $nr_gracza = range(0, count($wynik_koncowy) - 1);

    usort($nr_gracza, 'sortowanie');

    foreach ($nr_gracza as $key => $index) {
        $miejsce = $key + 1;
        $gracz_nr = $index + 1;
        $punkty = $wynik_koncowy[$index];
        $czy_pelny_zestaw = $pelny_zestaw[$index] ? "tak" : "nie";
    
        echo "Miejsce $miejsce: zajął gracz nr. $gracz_nr - Zdobył : $punkty, punktów. Pełny zestaw: $czy_pelny_zestaw\n <br>";
    }

}elseif((count($_SESSION['stos_kart']) ==  32 and $_SESSION['dostawa'] < 4) //mechanika dostaw
or (count($_SESSION['stos_kart']) ==  16 and $_SESSION['dostawa'] < 8)
or ($_SESSION['ostatnia_dostawa'])){

    echo '<div id="dostawa">';
    if(isset($_POST['dostarcz']) and !empty($_POST["do_oddania"])){ //tymczasowe przypisanie  i sprawdzenie wszystkiego
        $tymczasowka=$_POST["do_oddania"];
        if (count($tymczasowka) != 2) {
            echo "NIEPRAWIDŁOWA ILOŚĆ ZACZNACZONYCH KART! ZAZNACZ DOKŁADNIE DWIE KARTY! <br><br>";
        }else{
            $pierwsza = explode("_",$tymczasowka[0]);
            $druga = explode("_",$tymczasowka[1]);
            $czy_powtarza_sie = false;
            $sprawdzana=[];
            if (!empty($_SESSION['dostarczone_karty'][$_SESSION['aktualny_gracz']])){
                foreach ($_SESSION['dostarczone_karty'][$_SESSION['aktualny_gracz']] as $dostarczona) {
                    $nowa = explode("_",$dostarczona);
                    array_push($sprawdzana,$nowa[0]);
                }

                for ($i=0; $i < count($sprawdzana); $i++) { 
                    if ($sprawdzana[$i] == $pierwsza[0]) {
                        $czy_powtarza_sie = true;
                    }
                }
            }

            if ($pierwsza[0] != $druga[0]) { //czy kolory są takie same
                echo "Przekazywane karty nie mają tych samych kolorów! <br><br>";
            }elseif($pierwsza[1] == $druga[1]){ //czy jeden to towar, a drugi to statek
                echo "Przekazuj jednocześnie statek i towar!<br><br>";
            }elseif($czy_powtarza_sie){ //wymowne
                echo "Dostarczyłeś już taki kolor!<br><br>";
            }else{ //przeszło sprawdzenie
                array_push($_SESSION['dostarczone_karty'][$_SESSION['aktualny_gracz']] , $tymczasowka[0]);
                array_push($_SESSION['dostarczone_karty'][$_SESSION['aktualny_gracz']] , $tymczasowka[1]);
                for ($i=0; $i < 2; $i++) {
                    $pozycja = array_search($tymczasowka[$i],$_SESSION['karty_w_rece'][$_SESSION['aktualny_gracz']]);
                    unset($_SESSION['karty_w_rece'][$_SESSION['aktualny_gracz']][$pozycja]);
                }
                $_SESSION['karty_w_rece'][$_SESSION['aktualny_gracz']] = array_values($_SESSION['karty_w_rece'][$_SESSION['aktualny_gracz']]);
            }

        }
        
    }

    echo "Czas na dostawę dla gracza ".($_SESSION['aktualny_gracz']+1).' (Zaznacz dwie karty tego samego koloru i kliknij Dostarcz)';
    echo '</div>';

    echo '<div id="dostarczane_karty">';
    foreach ($_SESSION['karty_w_rece'][$_SESSION['aktualny_gracz']] as $karta){

        if ($_SESSION['aktualny_gracz'] === $_SESSION['aktualny_gracz']) {
            echo '<div class="karta">';
            echo '<img src="'.$karta.'.png" alt="'.$karta.'">';
            echo ' <input type="checkbox" name="do_oddania[]" value="'.$karta.'">';
            echo ' </div>';
        }
    }
    echo '</div>';

    echo '<div id="dostarcz">';
    echo '<input type="submit" name="dostarcz" value="Dostarcz">';
    echo '</div>';

    echo '<div id="dostarczone_karty">';
    if (!empty($_SESSION['dostarczone_karty'][$_SESSION['aktualny_gracz']])) {

        foreach ($_SESSION['dostarczone_karty'][$_SESSION['aktualny_gracz']] as $karta){
        if ($_SESSION['aktualny_gracz'] === $_SESSION['aktualny_gracz']) {
            echo '<div class="d_karta">';
            echo '<img src="'.$karta.'.png" alt="'.$karta.'">';
            echo ' </div>';
        }
        }
    }
    echo ' </div>';

    echo '<div id="zakoncz_dostawe">';
    echo '<br><br><input type="submit" name="zakoncz_dostawe" value="Zakończ swoją dostawę">';
    echo '</div>';

}else{ #główna część rozgrywki

    ################## Wyświetlanie odkrytych kart ##################
    echo '<div id="odkryte">';
    echo "<h3>Odkryte karty: </h3>";

    foreach ($_SESSION['odkryte_karty'] as $karta){

        // Sprawdzenie, czy przycisk "Usuń kartę" powinien być dostępny dla aktualnego gracza
        if ($_SESSION['aktualny_gracz'] === $_SESSION['aktualny_gracz']) {
           
            echo '<div class="karta"><img src="'.$karta.'.png" alt="'.$karta.'"> </div>';
            
        }
    }
    echo'<input type="submit" name="nr1" value="Weź pierwszą kartę">';
    echo'<input type="submit" name="nr2" value="Weź drugą kartę">';
    echo'<input type="submit" name="nr3" value="Weź trzecią kartę">';
    echo'<input type="submit" name="nr4" value="Weź czwartą kartę">';
    echo '<br>';
    echo'<br><input type="submit" name="losowa" value="Weź kartę z góry stosu kart">';
    echo '</div>';


    ################## Wyświetlanie kart graczy ##################

    echo '<div id="karty_gracza">';
    echo '<br>';
    echo "Aktualny gracz: " . ($_SESSION['aktualny_gracz'] + 1);
    echo '<br>';
    foreach ($_SESSION['karty_w_rece'][$_SESSION['aktualny_gracz']] as $karta){

        // Sprawdzenie, czy przycisk "Usuń kartę" powinien być dostępny dla aktualnego gracza
        if ($_SESSION['aktualny_gracz'] === $_SESSION['aktualny_gracz']) {
            echo '<div class="karta"><img src="'.$karta.'.png" alt="'.$karta.'">';
            echo '<input type="submit" name="'.$karta.'" value="Odrzuć"> </div>';
        }
    }
    echo '</div>';
    
}

echo'<div id="rozpocznij"><input type="submit" name = "start" value="Rozpocznij nową grę"></div>';

############# DEBUG CHECKS #############

if (false) {
echo "<br><br><br><br><br>#########################################################################################";
echo "<br>#################### SPRAWDZANIE WARTOŚCI (gracze nie powinni widzieć) ####################";
echo "<br>#########################################################################################";

echo "<br><br>";
echo "Zawartość stosu kart <br>";
print_r($_SESSION['stos_kart']);
echo "<br>W stosie kart jest: ".count($_SESSION['stos_kart']);

echo "<br><br>";
echo "Stan dostawy: ".$_SESSION['dostawa'];

echo "<br><br>";
echo "Czy to ostatnia dostawa? ";
echo $_SESSION['ostatnia_dostawa'] ? 'true': 'false';

echo "<br><br>";
echo "Odkryte karty: ";
print_r($_SESSION['odkryte_karty']);

echo "<br><br>";
echo "Ilość graczy: ".$ilosc_graczy;

echo "<br><br>";
echo "Aktualny gracz: ".$_SESSION['aktualny_gracz']+1;

echo "<br><br>";
echo "Karty w rękach graczy:";

for ($i=0; $i < $ilosc_graczy; $i++) { 
    echo "<br>Gracz ".$i." --> ";
    print_r($_SESSION['karty_w_rece'][$i]);
}

echo "<br><br>";
echo "Oddane karty graczy:";

for ($i=0; $i < $ilosc_graczy; $i++) { 
    echo "<br>Gracz ".$i." --> ";
    print_r($_SESSION['dostarczone_karty'][$i]);
}

echo "<br><br>";
echo "Czy nastąpił przyśpieszony (ktoś oddał 8 kart) koniec gry? ";
echo $koniec ? 'true': 'false';
}

?>
    
</form>
