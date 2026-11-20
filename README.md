# Vocabulary Trainer

A simple PHP terminal program for practising German and English vocabulary. You can add your own words and practise translating in both directions.

## How to start

Open a terminal in the project folder and run:

```bash
php Vokabeln_Lernen.php
```

## Menu

| Option | Action |
| --- | --- |
| 1 | Show vocabulary and delete a word pair by entering its number |
| 2 | Add a German word and its English translation |
| 3 | Practise German to English |
| 4 | Practise English to German |
| 5 | Show your score |
| 0 | Exit the program |

## How it works

The program picks a random word pair for each question. You get one point for a correct answer. If your answer is wrong, the program shows the correct translation.

Vocabulary is saved in `vokabeln.txt` and loaded when you start the program again. Your score starts at zero each time.
