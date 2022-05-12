<?php
# PHP 7.4.3 (cli) (built: Mar  2 2022 15:36:52) ( NTS )
declare(strict_types=1);

// Global variables
$success = false;
$secretNumber;
$gameStarted = false;
$entryAreNumbers = false;
$min;
$max;
$attemps = [];
$messages = [
    'number.notanumber' => "Bitte nur Z A H L E N eingeben.\n",
    'number.islower' => "Die gesuchte Zahl ist kleiner.\n",
    'number.ishigher' => "Die gesuchte Zahl ist grösser.\n",
    'number.repeated' => "Mit dieser Zahl haben Sie es schon einmal versucht!\n",
    'number.arenotinorder' => "Die kleinere Zahl muss kleiner sein als die größere Zahl!\n",
    'number.outofrange' => "Die eingegebene Zahl befindet sich ausserhalb des von ihnen definierten Bereich.\n"
];

function validateNumber(string $number)
{
    global $messages, $attemps, $secretNumber, $min, $max, $success;

    if (is_numeric($number)) {

        if ($number < $min || $number > $max) {
            echo $messages['number.outofrange'];
            return;
        }
        
        if (count($attemps) > 1 && in_array($number, $attemps)) {
            echo $messages['number.repeated'];
            return;
        }        

        if ($number > $secretNumber) {
            echo $messages['number.islower'];
            return;
        }
        
        if ($number < $secretNumber) {
            echo $messages['number.ishigher'];
            return;
        }
    } else {
        echo $messages['number.notanumber'];
    }
}

function startGame()
{
    global $secretNumber, $entryAreNumbers, $messages, $min, $max;

    echo "
================
= Zahlen Raten =
================

";

    while (!$entryAreNumbers) {
        $min = readline("Bitte die Untergräanze wählen: ");
        $max = readline("Bitte die Obergränze wählen: ");

        if (is_numeric($min) && is_numeric($max)) {
            if ($min < $max){
                $entryAreNumbers = true;
                $secretNumber = rand((int) $min, (int) $max);
                play();
            } else {
                echo $messages['number.arenotinorder'];
            }
        } else {
            echo $messages['number.notanumber'];
        }
    }
}


function play()
{
    global $attemps, $min, $max, $success, $secretNumber;
    echo "Bitte erraten sie die gesuchte Zahl, sie befindet sich zwischen {$min} und {$max}. \n";
    
    while (!$success) {
        $attempNumber = count($attemps) + 1;
        $attempNumberFormated = sprintf("%02d", $attempNumber);
        $attemp = readline("Ihr {$attempNumberFormated}. Versuch: ");
        $success = validateNumber($attemp);
        if ($attemp == $secretNumber) {
            $success = true;
            echo "Glückwunsch die von Ihnen eingegebene Zahl ( {$attemp} ) stimmt mit der gesuchten Zahl überein.\n";
        }
        $attemps[] = $attemp;
    }
}

startGame();