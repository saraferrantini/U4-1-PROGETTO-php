<?php 
//--------------------------------------------- comando che connette al database---------------------------//
$host = 'localhost'; 
$db   = 'gestione_libreria'; 
$user = 'root'; 
$pass = ''; 
 
$dsn = "mysql:host=$host;dbname=$db"; 
 
$options = [ 
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
    PDO::ATTR_EMULATE_PREPARES   => false, 
]; 
 
$pdo = new PDO($dsn, $user, $pass, $options); 
 
$errors = []; 
 
if ($_SERVER["REQUEST_METHOD"]== "POST") { 
    $titolo = $_POST["titolo"]; 
    $autore = $_POST["autore"]; 
    $anno_pubblicazione = $_POST["anno_pubblicazione"]; 
    $genere = $_POST["genere"]; 
 
    //----------------------------------------------------Validation------------------------------------------------------------//
    if (empty($titolo)) { 
        $errors['titolo'] = 'Titolo non può essere vuoto'; 
    } 
     
    if (empty($autore)) { 
        $errors['autore'] = 'Autore non può essere vuoto'; 
    } 

    if (!is_numeric($anno_pubblicazione) || $anno_pubblicazione < 0) {
        $errors['anno_pubblicazione'] = 'Anno di pubblicazione non valido';
    }

    if (empty($genere)) { 
        $errors['genere'] = 'Genere non può essere vuoto'; 
    }
 
    //------------------------------------- chiamata al database insrimento-------------------------------------------------//
    // Controlla se ci sono errori prima di procedere con l'inserimento 
    if (empty($errors)) { 
        $stmt = $pdo->prepare("INSERT INTO libri (titolo, autore, anno_pubblicazione, genere) VALUES (:titolo, :autore, :anno_pubblicazione, :genere)"); 
        $stmt->execute([ 
            'titolo' => $titolo, 
            'autore' => $autore, 
            'anno_pubblicazione' => $anno_pubblicazione, 
            'genere' => $genere, 
        ]); 
        echo "Dati inseriti con successo!"; 
        header('Location: index.php'); 
        exit(); 
    } else { 
        echo '<pre>' . print_r($errors, true) . '</pre>'; 
    } 
} 
?> 

<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet" 
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
        crossorigin="anonymous" 
    /> 
    <title>Inserimento Libro</title> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-i/BtMzIpzjZlGLA9bt6fSwGtzkpxQ1Qd32R7S4EgdLMyxVDcjtbimwYYKJtoqFjK6fm/kJOU2/jgWNPfoH0viA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style> 
        #box {
            display: flex;
            justify-content: center;
            margin-top: 3dvb;
        
            border-radius: 10px;
            padding: 10px;
        }

        body {
    text-align: center;
    background-color: #333; 
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}


        form {
            background-color: #333;
        }

        footer {
            background-color: #f8f9fa;
            padding: 20px;
            margin-top: auto;
            text-align: center;
        }
    </style> 
</head> 
<body> 
  <!-- navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container-fluid">
      
        <a class="navbar-brand" href="#">
            <img src="https://img.freepik.com/premium-vector/book-logo-design-icon-vector_9850-5270.jpg" alt="Logo Libreria" height="60">
        </a>
       
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                </li>
               
                <li class="nav-item">
                    <a class="nav-link" href="aggiungi.php">Aggiungi Libro</a> 
                </li>
                
                
            </ul>
        </div>
    </div>
</nav>


<!-- form -->
<h1 class="col text-center text-white mt-5">Inserisci qui il tuo libro</h1>
<div id="box"> 
    <form style="width: 500px" method="POST"> 
        <div class="mb-3"> 
            <label for="titolo" class="form-label text-white" >Titolo</label> 
            <input type="text" class="form-control" name="titolo" id="titolo" /> 
        </div> 
        <div class="mb-3"> 
            <label for="autore" class="form-label text-white">Autore</label> 
            <input type="text" class="form-control" name="autore" id="autore" /> 
        </div> 
        <div class="mb-3"> 
            <label for="anno_pubblicazione" class="form-label text-white">Anno Pubblicazione</label> 
            <input type="number" class="form-control" name="anno_pubblicazione" id="anno_pubblicazione" /> 
        </div>
        <div class="mb-3"> 
            <label for="genere" class="form-label text-white">Genere</label> 
            <input type="text" class="form-control" name="genere" id="genere" /> 
        </div>
    
        <button type="submit" class="btn btn-primary">Inserisci</button> 
    </form> 
</div> 

<!-- footer -->
<footer>
    <p>&copy; 2024 Libreria Online. Tutti i diritti riservati.</p>
    <div class="social-icons">
        <a href="#" target="_blank"><i class="fab fa-facebook"></i></a>
        <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
        <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
        <a href="#" target="_blank"><i class="fab fa-linkedin"></i></a>
    </div>
</footer>


<script 
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
        crossorigin="anonymous" 
></script> 
</body> 
</html>
