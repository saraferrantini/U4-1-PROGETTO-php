<?php 
$host = "localhost"; 
$db = "gestione_libreria"; 
$user = "root"; 
$pass = ""; 
$dsn = "mysql:host=$host;dbname=$db"; 
$options = [ 
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
    PDO::ATTR_EMULATE_PREPARES => false, 
]; 
 
try { 
    $pdo = new PDO($dsn, $user, $pass, $options); 
} catch (PDOException $e) { 
    die("Errore di connessione al database: " . $e->getMessage()); 
} 
 
// Controllo se è stato passato un ID valido tramite GET 
$id = $_GET['id'] ?? null; 
if (!$id) { 
    die("ID non fornito."); 
} 
 
// Carico i dati del libro da modificare 
$stmt = $pdo->prepare("SELECT * FROM libri WHERE id = ?"); 
$stmt->execute([$id]); 
$libro = $stmt->fetch(); 
 
// Gestione del salvataggio dei dati modificati 
if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $titolo = $_POST['titolo']; 
    $autore = $_POST['autore']; 
    $anno_pubblicazione = $_POST['anno_pubblicazione']; 
    $genere = $_POST['genere']; 
 
    // Eseguo l'aggiornamento del record nel database 
    $stmt = $pdo->prepare("UPDATE libri SET titolo = ?, autore = ?, anno_pubblicazione = ?, genere = ? WHERE id = ?"); 
    $stmt->execute([$titolo, $autore, $anno_pubblicazione, $genere, $id]); 
 
    // Reindirizzo alla pagina di visualizzazione dei libri dopo l'aggiornamento 
    header('Location: index.php'); 
    exit(); 
} 
?> 
 
<!DOCTYPE html> 
<html lang="it"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Modifica Libro</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> 
    <style> 
        #box { 
            display: flex; 
            justify-content: center; 
            margin-top: 100px; 
          
        } 
 
        body { 
            background-color: #333; 
        } 
 
        form { 
            background-color: #333; 
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
    <div class="container mt-5"> 
        <h1  class="text-white">Modifica Libro</h1> 
        <form method="POST"> 
            <div class="mb-3"> 
                <label for="titolo" class="form-label text-white">Titolo</label> 
                <input type="text" class="form-control" id="titolo" name="titolo" value="<?php echo htmlspecialchars($libro['titolo']); ?>"> 
            </div> 
            <div class="mb-3"> 
                <label for="autore" class="form-label text-white">Autore</label> 
                <input type="text" class="form-control" id="autore" name="autore" value="<?php echo htmlspecialchars($libro['autore']); ?>"> 
            </div> 
            <div class="mb-3"> 
                <label for="anno_pubblicazione" class="form-label text-white">Anno Pubblicazione</label> 
                <input type="text" class="form-control" id="anno_pubblicazione" name="anno_pubblicazione" value="<?php echo htmlspecialchars($libro['anno_pubblicazione']); ?>"> 
            </div> 
            <div class="mb-3"> 
                <label for="genere" class="form-label text-white">Genere</label> 
                <input type="text" class="form-control" id="genere" name="genere" value="<?php echo htmlspecialchars($libro['genere']); ?>"> 
            </div> 
            <button type="submit" class="btn btn-primary">Salva Modifiche</button> 
        </form> 
    </div> 
</body> 
</html>