<?php 
// ------------------------------------comando che connette al database-----------------------------------------------// 
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
 
//---------------------------------------Verifica se il form è stato inviato e se user_id è stato impostato------------------------------// 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) { 
    $id = $_POST['delete_id']; 
 
    if (!is_numeric($id)) { 
        echo "L'ID utente deve essere un numero."; 
    } else { 
        // Prepara e esegui la query di eliminazione 
        $stmt = $pdo->prepare("DELETE FROM libri WHERE id = ?"); 
        $stmt->execute([$id]); 
       
    } 
} 


//------------------------------------------Ricerca per titolo o autore---------------------------------------------------//
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['search'])) {
    $search = $_GET['search'];

    // Prepara la query di ricerca per titolo o autore
    $stmt = $pdo->prepare("SELECT * FROM libri WHERE titolo LIKE ? OR autore LIKE ?");
    $stmt->execute(["%$search%", "%$search%"]);

    $results = $stmt->fetchAll();
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
    <title>Document</title> 
    <style> 
        #box { 
            display: flex; 
            justify-content: center; 
            margin-top: 40px; 
          
        } 
 
        body { 
            background-color: #333; 
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

<h1 class="col text-center text-white mt-5">Visualizza e ricerca i libri</h1>
<div id="box"> 
  

    <form style="width: 500px;" method="GET">
        <div class="mb-3">
            <label for="search" class="form-label text-white">Ricerca libro</label>
            <input type="text" class="form-control" name="search" id="search" />
        </div>
        <button type="submit" class="btn btn-primary mb-3">Cerca</button>
       
    </form>
</div>

<?php if (isset($results) && count($results) > 0): ?>
    <h3 class="text-white">Risultati della ricerca:</h3>
    <div class="row">
        <?php foreach ($results as $result): ?>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= $result['titolo'] ?></h5>
                        <p class="card-text">Autore: <?= $result['autore'] ?></p>
                        <p class="card-text">Anno Pubblicazione: <?= $result['anno_pubblicazione'] ?></p>
                        <p class="card-text">Genere: <?= $result['genere'] ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<h3 class="mt-5 text-white">Libri:</h3>
<div class="row">
    <?php 
    // Visualizzazione di tutti i libri nel database
    $stmt = $pdo->query('SELECT * FROM libri'); 
    foreach ($stmt as $row) { ?>
        <div class="col-md-4">
            <div class="card mb-3 ">
                <div class="card-body">
                    <h5 class="card-title"><?= $row['titolo'] ?></h5>
                    <p class="card-text">Autore: <?= $row['autore'] ?></p>
                    <p class="card-text">Anno Pubblicazione: <?= $row['anno_pubblicazione'] ?></p>
                    <p class="card-text">Genere: <?= $row['genere'] ?></p>
                    <!-- pulsanti di modifica ed eliminazione -->
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-primary mr-2">Modifica</a>
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                        <button type="submit" class="btn btn-danger">Elimina</button>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
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
