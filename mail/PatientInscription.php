<?php
// Connexion à la base de données
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'telekine';  // Nom de ta base de données
$conn = new mysqli($host, $user, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération des valeurs du formulaire
    $nom = $_POST['name'];
    $prenom = $_POST['firstname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation du formulaire
    if (empty($nom) || empty($prenom) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
        echo "Tous les champs doivent être remplis.";
    } elseif ($password !== $confirm_password) {
        echo "Les mots de passe ne correspondent pas.";
    } else {
        // Hashage du mot de passe
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insertion dans la base de données
        $stmt = $conn->prepare("INSERT INTO utilisateurs (nom, prenom, email, phone, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nom, $prenom, $email, $phone, $hashed_password);

        // Exécution de la requête
        if ($stmt->execute()) {
            echo "Inscription réussie !";
        } else {
            echo "Erreur : " . $stmt->error;
        }

        // Fermeture de la requête préparée
        $stmt->close();
    }
}

// Fermeture de la connexion à la base de données
$conn->close();
?>
