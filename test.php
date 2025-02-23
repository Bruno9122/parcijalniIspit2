<?php
// Funkcija za brojanje slova, suglasnika i samoglasnika
function analizirajRijec($rijec) {
    // Broj slova
    $brojSlova = strlen($rijec);
    
    // Inicijalizacija brojača za suglasnike i samoglasnike
    $brojSuglasnika = 0;
    $brojSamoglasnika = 0;
    
    // Lista samoglasnika i suglasnika
    $samoglasnici = ['a', 'e', 'i', 'o', 'u'];
    $suglasnici = ['b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'r', 's', 't', 'v', 'z'];

    // Pretvori riječ u mala slova (pojednostavljuje analizu)
    $rijec = strtolower($rijec);

    // Prolazimo kroz svako slovo u riječi
    for ($i = 0; $i < $brojSlova; $i++) {
        $slovo = $rijec[$i];
        // Provjera da li je samoglasnik ili suglasnik
        if (in_array($slovo, $samoglasnici)) {
            $brojSamoglasnika++;
        } elseif (in_array($slovo, $suglasnici)) {
            $brojSuglasnika++;
        }
    }

    return [
        'brojSlova' => $brojSlova,
        'brojSuglasnika' => $brojSuglasnika,
        'brojSamoglasnika' => $brojSamoglasnika
    ];
}

// Učitavanje postojećih riječi iz JSON datoteke, ako postoji
$rijeci = [];
if (file_exists('words.json')) {
    $json_data = file_get_contents('words.json');
    // Provjeriti da li su podaci ispravno učitani
    if ($json_data) {
        $rijeci = json_decode($json_data, true);
    }
}

// Obrada forme kada je obrazac poslan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['rijec'])) {
    $novaRijec = $_POST['rijec'];
    
    // Analiza nove riječi
    $analiza = analizirajRijec($novaRijec);
    
    // Dodavanje nove riječi u array
    $rijeci[] = [
        'rijec' => $novaRijec,
        'brojSlova' => $analiza['brojSlova'],
        'brojSuglasnika' => $analiza['brojSuglasnika'],
        'brojSamoglasnika' => $analiza['brojSamoglasnika']
    ];
    
    // Spremanje ažuriranog niza u JSON datoteku (dodavanje nove riječi na kraj)
    file_put_contents('words.json', json_encode($rijeci, JSON_PRETTY_PRINT));
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analiza Riječi</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Unos i analiza riječi</h1>

    <!-- Obrazac za unos nove riječi -->
    <form method="POST">
        <input type="text" name="rijec" placeholder="Unesite riječ" required>
        <button type="submit">Dodaj riječ</button>
    </form>

    <!-- Tablica koja prikazuje riječi -->
    <table>
        <thead>
            <tr>
                <th>Riječ</th>
                <th>Broj slova</th>
                <th>Broj suglasnika</th>
                <th>Broj samoglasnika</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rijeci as $rijec): ?>
                <tr>
                    <td><?php echo htmlspecialchars($rijec['rijec']); ?></td>
                    <td><?php echo $rijec['brojSlova']; ?></td>
                    <td><?php echo $rijec['brojSuglasnika']; ?></td>
                    <td><?php echo $rijec['brojSamoglasnika']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
