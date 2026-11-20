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
    $choice = readline("Deine Wahl: ");

    if (!is_string($choice) or $choice === "0") {
        break;
    }

//choice 1

    if ($choice === "1") {
        if (count($words) === 0) {
            echo "Die Liste ist leer.\n";
            continue;
        }

//visualisation of vokabeln.txt

        foreach ($words as $index => $line) {
            $number = $index + 1;
            echo "$number. " . trim($line) . "\n";
        }

        $number = readline("Welche Nummer löschen? [Enter = zurück]: ");

//if your awnser doesn`t match the list then continue

        if ($number === "" or $number === false) {
            continue;
        }

//delete words from Vocab

        if (preg_match('/^[1-9][0-9]*$/', $number) && $number <= count($words)) {
            array_splice($words, (int) $number - 1, 1);
            file_put_contents($file, implode("", $words));
            echo "Vokabel gelöscht.\n";
        } else {
            echo "Diese Nummer gibt es nicht.\n";
        }

//choice 2
    } elseif ($choice === "2") {
        $german = trim(readline("Deutsch: "));
        $english = trim(readline("Englisch: "));

        if ($german === "" or $english === "") {
            echo "Bitte gib beide Wörter ein.\n";

//NO! Semikolon
        } elseif (strpos($german . $english, ";") !== false) {
            echo "Bitte verwende kein Semikolon.\n";

//words saved in file (vokabeln.txt)
        } else {
            $line = "$german;$english\n";
            file_put_contents($file, $line, FILE_APPEND);
            $words[] = $line;
            echo "Vokabel gespeichert.\n";
        }

//choice 3/4
    } elseif ($choice === "3" or $choice === "4") {
        if (count($words) === 0) {
            echo "Füge zuerst eine Vokabel hinzu.\n";
            continue;
        }

//shows words

        $index = array_rand($words);
        $pair = explode(";", trim($words[$index]));
        $question = $pair[0];
        $solution = $pair[1];

        if ($choice === "4") {
            $question = $pair[1];
            $solution = $pair[0];
        }

        $answer = readline("Übersetze $question: ");

//checks Awnser (if right = 1 Point) (if wrong = 0 Points + solution)


        if (checkAnswer($answer, $solution)) {
            $points++;
            echo "Richtig! +1 Punkt\n";
        } else {
            echo "Lösung: $solution\n";
        }

//shows your Points
    } elseif ($choice === "5") {
        echo "Punkte: $points\n";
    } else {
        echo "Bitte wähle eine Zahl von 0 bis 5.\n";
    }
}
