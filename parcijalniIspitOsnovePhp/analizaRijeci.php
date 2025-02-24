<?php
function analizirajRijec($rijec) {
    $brojSlova = mb_strlen ($rijec, 'UTF-8');
    ($rijec);
    
    $brojSuglasnika = 0;
    $brojSamoglasnika = 0;
    
    $samoglasnici = ['a', 'e', 'i', 'o', 'u'];
    $suglasnici = ['b', 'c', 'č', 'ć', 'd', 'đ', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'r', 's', 'š', 't', 'v', 'w', 'z', 'ž', 'x', 'y'];

    $rijec = strtolower($rijec);

    for ($i = 0; $i < $brojSlova; $i++) {
        $slovo = $rijec[$i];
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

$rijeci = [];
if (file_exists('words.json')) {
    $json_data = file_get_contents('words.json');
    if ($json_data) {
        $rijeci = json_decode($json_data, true);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['rijec'])) {
    $novaRijec = $_POST['rijec'];
    
    $analiza = analizirajRijec($novaRijec);
    
    $rijeci[] = [
        'rijec' => $novaRijec,
        'brojSlova' => $analiza['brojSlova'],
        'brojSuglasnika' => $analiza['brojSuglasnika'],
        'brojSamoglasnika' => $analiza['brojSamoglasnika']
    ];
    
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

    <form method="POST">
        <input type="text" name="rijec" placeholder="Unesite riječ" required>
        <button type="submit">Dodaj riječ</button>
    </form>

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