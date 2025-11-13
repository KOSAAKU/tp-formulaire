<?php

$profiles = [
    ['nom' => 'Alice Dupont', 'email' => 'alice@ipssi.net', 'password' => 'alice123', 'sexe' => 'Femme', 'ville' => 'Paris', 'loisir' => ['Lecture', 'Natation']],
    ['nom' => 'Bob Martin',  'email' => 'bob@ipssi.net', 'password' => 'bob123', 'sexe' => 'Homme',  'ville' => 'Lyon',  'loisir' => ['Football', 'Cinéma']],
    ['nom' => 'Carla Rossi', 'email' => 'carla@ipssi.net', 'password' => 'carla123', 'sexe' => 'Femme', 'ville' => 'Nice',  'loisir' => ['Randonnée']],
    ['nom' => 'David Lee',   'email' => 'david@ipssi.net', 'password' => 'david123', 'sexe' => 'Homme',  'ville' => 'Paris', 'loisir' => ['Cuisine', 'Lecture']],
    ['nom' => 'Eva Morel',   'email' => 'eva@ipssi.net', 'password' => 'eva123', 'sexe' => 'Femme', 'ville' => 'Lyon',  'loisir' => ['Natation', 'Voyage']],

];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // return an HTTP 405 Method Not Allowed response
    header($_SERVER["SERVER_PROTOCOL"] . " 405 Method Not Allowed", true, 405);
    echo "405 Method Not Allowed";
    exit;
}

$requiredFields = ['nom', 'email', 'password', 'sexe', 'ville', 'loisir'];
$allowedSexe = ['Homme', 'Femme'];

foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        header($_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request", true, 400);
        $error = "400 Bad Request: Missing field '$field'";
        echo $error;
        header("Location: ./index.html?message=" . urlencode($error));
        exit;
    }
}

$nom = trim(htmlspecialchars($_POST['nom']));
$email = trim(htmlspecialchars($_POST['email']));
$password = trim(htmlspecialchars($_POST['password']));
$sexe = trim(htmlspecialchars($_POST['sexe']));
$ville = trim(htmlspecialchars($_POST['ville']));
$loisir = trim(htmlspecialchars($_POST['loisir']));

// check fields aren't empty after trimming
if (empty($nom) || empty($email) || empty($password) || empty($sexe) || empty($ville) || empty($loisir)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request", true, 400);
    $error = "400 Bad Request: All fields must be non-empty.";
    echo $error;
    header("Location: ./index.html?message=" . urlencode($error));
    exit;
}

if (strlen($nom) < 2 || strlen($nom) > 16) {
    header($_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request", true, 400);
    $error = "400 Bad Request: 'nom' must be between 2 and 16 characters.";
    echo $error;
    header("Location: ./index.html?message=" . urlencode($error));
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request", true, 400);
    $error = "400 Bad Request: Invalid email format.";
    echo $error;
    header("Location: ./index.html?message=" . urlencode($error));
    exit;
}

if (strlen($password) < 6 || strlen($password) > 64) {
    header($_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request", true, 400);
    $error = "400 Bad Request: 'password' must be at least 6 characters long.";
    echo $error;
    header("Location: ./index.html?message=" . urlencode($error));
    exit;
}

if (!in_array($sexe, $allowedSexe)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request", true, 400);
    $error = "400 Bad Request: 'sexe' must be either 'Homme' or 'Femme'.";
    echo $error;
    header("Location: ./index.html?message=" . urlencode($error));  
    exit;
}

$profileFound = false;
foreach ($profiles as $profile) {
    if ($profile['nom'] === $nom && 
    $profile['email'] === $email &&
    $profile['password'] === $password &&
    $profile['sexe'] === $sexe && 
    $profile['ville'] === $ville) {
        $loisirMatch = false;
        foreach ($profile['loisir'] as $l) {
            if (in_array($l, $profile['loisir'])) {
                $loisirMatch = true;
                break;
            }
        }
        if ($loisirMatch) {
            $profileFound = true;
            break;
        }
    }
}

if (!$profileFound) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404);
    $error = "404 Not Found: Profile not found.";
    echo $error;
    header("Location: ./index.html?message=" . urlencode($error));
    exit;
} else {
    header($_SERVER["SERVER_PROTOCOL"] . " 200 OK", true, 200);
    $success = "200 OK: Profile found successfully.";
    echo $success;
    header("Location: ./index.html?message=" . urlencode($success));
    exit;
}