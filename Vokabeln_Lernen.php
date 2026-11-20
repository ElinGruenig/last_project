<?php

function isCorrect($answer, $solution)
{
    $answer = strtolower(trim($answer));
    $solution = strtolower($solution);

    return $answer === $solution;
}

$file = "vokabeln.txt";
$vocabulary = [];
$points = 0;

if (file_exists($file)) {
    $vocabulary = file($file);

    if ($vocabulary === false) {
        exit("Die Vokabeldatei konnte nicht gelesen werden.\n");
    }
}

while (true) {
    echo "\n--- Vokabeltrainer ---\n";
    echo "1. Vokabeln anzeigen und löschen\n";
    echo "2. Vokabel hinzufügen\n";
    echo "3. Deutsch → Englisch üben\n";
    echo "4. Englisch → Deutsch üben\n";
    echo "5. Punktestand anzeigen\n";
    echo "0. Beenden\n";

    $choice = readline("Deine Wahl: ");

    if (!is_string($choice) || $choice === "0") {
        break;
    }

    if ($choice === "1") {
        if ($vocabulary === []) {
            echo "Die Liste ist leer.\n";
            continue;
        }

        foreach ($vocabulary as $index => $line) {
            $number = $index + 1;
            echo "$number. " . trim($line) . "\n";
        }

        $input = readline("Nummer zum Löschen [Enter = zurück]: ");

        if ($input === "" || $input === false) {
            continue;
        }

        $number = (int) $input;

        if (!preg_match('/^[1-9][0-9]*$/', $input) || $number > count($vocabulary)) {
            echo "Diese Nummer gibt es nicht.\n";
            continue;
        }

        $updatedList = $vocabulary;
        array_splice($updatedList, $number - 1, 1);

        if (file_put_contents($file, implode("", $updatedList)) === false) {
            echo "Die Vokabel konnte nicht gelöscht werden.\n";
        } else {
            $vocabulary = $updatedList;
            echo "Vokabel gelöscht.\n";
        }
    } elseif ($choice === "2") {
        $german = trim((string) readline("Deutsch: "));
        $english = trim((string) readline("Englisch: "));

        if ($german === "" || $english === "") {
            echo "Bitte gib beide Wörter ein.\n";
        } elseif (strpos($german . $english, ";") !== false) {
            echo "Bitte gib die Wörter ohne Semikolon ein.\n";
        } else {
            $line = "$german;$english\n";

            if (file_put_contents($file, $line, FILE_APPEND) === false) {
                echo "Die Vokabel konnte nicht gespeichert werden.\n";
            } else {
                $vocabulary[] = $line;
                echo "Vokabel gespeichert.\n";
            }
        }
    } elseif ($choice === "3" || $choice === "4") {
        if ($vocabulary === []) {
            echo "Füge zuerst eine Vokabel hinzu.\n";
            continue;
        }

        $index = array_rand($vocabulary);
        $words = explode(";", trim($vocabulary[$index]));

        if ($choice === "3") {
            $question = $words[0];
            $solution = $words[1];
        } else {
            $question = $words[1];
            $solution = $words[0];
        }

        $answer = readline("Übersetze $question: ");

        if ($answer === false) {
            break;
        }

        if (isCorrect($answer, $solution)) {
            $points++;
            echo "Richtig! +1 Punkt\n";
        } else {
            echo "Die Lösung ist: $solution\n";
        }
    } elseif ($choice === "5") {
        echo "Punkte: $points\n";
    } else {
        echo "Bitte wähle eine Nummer von 0 bis 5.\n";
    }
}

echo "Bis bald!\n";