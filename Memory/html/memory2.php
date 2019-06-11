<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Memory Game</title>
    <meta charset="utf-8" />
</head>

<body style="text-align:center;">
    <h1>Memory</h1>

    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
        <input type="submit" name="Spielstart" value="Spiel Neu starten">
    <?php
        $karten;
        $istgedreht;
        $Spielstart = false;
        // Spiel starten bzw. Neu starten
        if (isset($_POST["Spielstart"])) {
            $Spielstart = true;
        }
    ?>
    </form>
    <?php
        if($Spielstart){
            // Der ersten Karte den Wert -1 zuweisen damit keine Fehler entstehen
            $_SESSION["ersteKarte"] = -1;
            // Array mit 8 Kartenpaaren erstellen und mischen
            $karten = array(1,1,2,2,3,3,4,4,5,5,6,6,7,7,8,8);    
            shuffle($karten);
            // Die gemischten Karten in der Session speichern
            $_SESSION["karten"] = $karten;

            // alle Karten als nicht umgedreht initialisieren
            $istgedreht = array();
            for ($i=0;$i<16;$i++){
                $istgedreht[$i] = false;
            }
            // Daten zwischenspeichern in der Session
            $_SESSION["istgedreht"] = $istgedreht;
        } else {
            // Daten aus der Session holen, wenn das Spiel bereits gestartet wurde
            $karten = $_SESSION["karten"];
            $istgedreht = $_SESSION["istgedreht"];
        }

        // Variablen initialisieren
        $karte = -1;
        $reset = false;
        $match = false;
        // Wenn eine Karte angeklickt wird
        if(isset($_POST["karte"])){
            $karte = $_POST["karte"]; // übernimmt den Index der Karte, welche geklickt wurde
            // prüfen ob es die erste Karte ist, die angeklickt wurde
            if($_SESSION["erstKarte"] == -1){
                $_SESSION["erstKarte"] = $karte; // Karte als erste Karte in Session speichern
            } else {
                $reset = true;
                // Wenn beider Karten übereinstimmen bleiben sie Dauerhaft umgedreht 
                if(check($karte,$karten)){
                    $istgedreht[$karte] = true;
                    $istgedreht[$_SESSION["erstKarte"]] = true;
                    $match = true;
                    $_SESSION["istgedreht"] = $istgedreht;
                }
            }        
        }

        // Spielbrett mit 16 Karten
        ?>
        <table align="center"><tbody><tr>
        <?php
        echo "<tr>";
        for ($i=0;$i<16;$i++)
        {
            if($i%4 == 0){
        ?>
        </tr><tr>
        <?php
            }
            echo "<td>";
            if($istgedreht[$i] == false && $i != $karte && $_SESSION["erstKarte"] != $i){
                if(!$reset){
                    ?>
                    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                        <input type="hidden" name="karte" value="<?php echo $i; ?>">
                        <input type='image' src='bg.jpg' value='drehen' width='100' height='100'>
                    </form>
                    <?php
                }else{
                    ?>
                        <input type='image' src='bg.jpg' value='drehen' width='100' height='100'>
                    <?php
                }
            } else {
                echo "<input type='image' src='karte$karten[$i].gif' name='kartennummer' width='100' height='100'>";
            }            
            echo "</td>";            
        } 
        echo "</tr>";
        echo "</tbody>";
        echo "</table>";

        // Ausgabe Meldung und OK Button zum zurückdrehen
        if($reset){
            ?><h1><?php echo  $match ? "Super gleich weiter" : "Merke dir die Karten ganz genau";?></h1><?php
            $_SESSION["erstKarte"] = -1;
            ?>
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                <input type="submit" value="OK">
            </form>
        <?php
        }

    // Funktion zum prüfen ob beide Karten übereinstimmen     
    function check($karte,$karten){
        $erste = $karten[$_SESSION["erstKarte"]];
        return $erste == $karten[$karte];
    }
?>

</body>

</html>