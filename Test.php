<?php

function istRichtig($antwort, $loesung)
{
    return strtolower(trim($antwort)) == strtolower($loesung);
}

$datei = "vokabeln.txt";
$vokabeln = [];
$punkte = 0;

if (file_exists($datei)) {
    $vokabeln = file($datei, FILE_IGNORE_NEW_LINES);
}

while (true) {
    echo "\n1. Vokabeln anzeigen\n";
    echo "2. Vokabel hinzufügen\n";
    echo "3. Deutsch → Englisch\n";
    echo "4. Englisch → Deutsch\n";
    echo "5. Punkte anzeigen\n";
    echo "0. Beenden\n";

    $wahl = readline("Deine Wahl: ");

    if (!is_string($wahl) || $wahl == "0") {
        break;
    }

    if (!preg_match('/^[1-5]$/', $wahl)) {
        echo "Bitte wähle eine Zahl von 0 bis 5.\n";
        continue;
    }

    if ($wahl == "1") {
        foreach ($vokabeln as $paar) {
            echo $paar . "\n";
        }
    }

    if ($wahl == "2") {
        $deutsch = trim(readline("Deutsch: "));
        $englisch = trim(readline("Englisch: "));

        if ($deutsch == "" || $englisch == "") {
            echo "Bitte gib beide Wörter ein.\n";
        } elseif (strpos($deutsch . $englisch, ";") !== false) {
            echo "Bitte verwende kein Semikolon.\n";
        } else {
            $paar = $deutsch . ";" . $englisch;
            if (file_put_contents($datei, $paar . "\n", FILE_APPEND) !== false) {
                $vokabeln[] = $paar;
                echo "Gespeichert!\n";
            }
        }
    }

    if ($wahl == "3" || $wahl == "4") {
        if (count($vokabeln) == 0) {
            echo "Füge zuerst eine Vokabel hinzu.\n";
            continue;
        }

        $nummer = array_rand($vokabeln);
        $woerter = explode(";", $vokabeln[$nummer]);

        if ($wahl == "3") {
            $frage = $woerter[0];
            $loesung = $woerter[1];
        } else {
            $frage = $woerter[1];
            $loesung = $woerter[0];
        }

        $antwort = readline("Übersetze $frage: ");

        if (istRichtig($antwort, $loesung)) {
            $punkte++;
            echo "Richtig! +1 Punkt\n";
        } else {
            echo "Die Lösung ist: $loesung\n";
        }
    }

    if ($wahl == "5") {
        echo "Punkte: $punkte\n";
    }
}
