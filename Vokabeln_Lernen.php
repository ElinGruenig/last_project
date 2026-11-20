<?php

//checks if awnser is right

function checkAnswer($answer, $solution)
{
    return strtolower(trim($answer)) === strtolower($solution);
}

//variables definition

$file = "vokabeln.txt";
$words = [];
$points = 0;
$choice = readline("Deine Wahl: ");

//checks if file exists

if (file_exists($file)) {
    $words = file($file);
}

//call Menu

while (true) {
    echo "\n1. Anzeigen und löschen\n";
    echo "2. Hinzufügen\n";
    echo "3. Deutsch → Englisch\n";
    echo "4. Englisch → Deutsch\n";
    echo "5. Punkte\n";
    echo "0. Beenden\n";

    if (!is_string($choice) and $choice === "0") {
        break;
    }


    if ($choice === "1") {
        if (count($words) === 0) {
            echo "Die Liste ist leer.\n";
            continue;
        }

        foreach ($words as $index => $line) {
            $number = $index + 1;
            echo "$number. " . trim($line) . "\n";
        }

        $number = readline("Welche Nummer löschen? [Enter = zurück]: ");

        if ($number === "" || $number === false) {
            continue;
        }

        if (preg_match('/^[1-9][0-9]*$/', $number) && $number <= count($words)) {
            array_splice($words, (int) $number - 1, 1);
            file_put_contents($file, implode("", $words));
            echo "Vokabel gelöscht.\n";
        } else {
            echo "Diese Nummer gibt es nicht.\n";
        }
    } elseif ($choice === "2") {
        $german = trim(readline("Deutsch: "));
        $english = trim(readline("Englisch: "));

        if ($german === "" || $english === "") {
            echo "Bitte gib beide Wörter ein.\n";
        } elseif (strpos($german . $english, ";") !== false) {
            echo "Bitte verwende kein Semikolon.\n";
        } else {
            $line = "$german;$english\n";
            file_put_contents($file, $line, FILE_APPEND);
            $words[] = $line;
            echo "Vokabel gespeichert.\n";
        }
    } elseif ($choice === "3" || $choice === "4") {
        if (count($words) === 0) {
            echo "Füge zuerst eine Vokabel hinzu.\n";
            continue;
        }

        $index = array_rand($words);
        $pair = explode(";", trim($words[$index]));
        $question = $pair[0];
        $solution = $pair[1];

        if ($choice === "4") {
            $question = $pair[1];
            $solution = $pair[0];
        }

        $answer = readline("Übersetze $question: ");

        if (checkAnswer($answer, $solution)) {
            $points++;
            echo "Richtig! +1 Punkt\n";
        } else {
            echo "Lösung: $solution\n";
        }
    } elseif ($choice === "5") {
        echo "Punkte: $points\n";
    } else {
        echo "Bitte wähle eine Zahl von 0 bis 5.\n";
    }
}
