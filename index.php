<?php

require '_connec.php';

$pdo = new \PDO(DSN, USER, PASS);

$query = "SELECT * FROM friend";
$statement = $pdo->query($query);
$friends = $statement->fetchAll(\PDO::FETCH_ASSOC);

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $data = array_map('trim', $_POST);
    if (!isset($data['firstname']) || empty($data['firstname'])) {
        $errors[] = "veuillez entrer le prénom";
    } elseif (strlen($data['firstname']) > 45) {
        $errors[] = "le prénom fait plus de 45 caractères";
    }
    if (!isset($data['lastname']) || empty($data['lastname'])) {
        $errors[] = "veuillez entrer le nom";
    } elseif (strlen($data['lastname']) > 45) {
        $errors[] = "le nom fait plus de 45 caractères";
    }

    if (empty($errors)) {
        $query = 'INSERT INTO friend (firstname, lastname) VALUES (:firstname, :lastname);';
        $statement = $pdo->prepare($query);

        $statement->bindValue(':firstname', $data['firstname'], \PDO::PARAM_STR);
        $statement->bindValue(':lastname', $data['lastname'], \PDO::PARAM_STR);
        $statement->execute();

        header('Location: /');
    }
    
}

?>

<ul>
    <?php
        foreach ($friends as $friend){
            ?><li><?php
                foreach ($friend as $key => $element){
                    echo ($element . " ");
                }
            ?></li><?php
        } 
    ?>
</ul>

<form action ="" method="post">
    <label for="firstname">Firstname : </label>
    <input type="text" id="firstname" name="firstname"></input>
    <label for="lastname">Lastname : </label>
    <input type="text" id="lastname" name="lastname"></input>
    <button type="submit">Envoyer</button>
</form>