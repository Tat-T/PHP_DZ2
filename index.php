<?php
$filename = "countries.csv";

// Создание файла, если он не существует
if (!file_exists($filename)) {
    file_put_contents($filename, "");
}

// Функция для чтения стран из файла
function getCountries($filename) {
    $countries = [];
    if (($handle = fopen($filename, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",", '"', "\\")) !== FALSE) {
            $countries[] = $data[0];
        }
        fclose($handle);
    }
    return $countries;
}

// Обработка отправки формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $country = trim($_POST["country"]);
    if (!empty($country)) {
        $countries = getCountries($filename);
        
        if (!in_array($country, $countries)) {
            $file = fopen($filename, "a");
            fputcsv($file, [$country]);
            fclose($file);
        }
    }
}

$countries = getCountries($filename);
?>

    <form method="POST">
        <input type="text" name="country" placeholder="Введите название страны" required>
        <button type="submit">Добавить</button>
    </form>
    
    <?php if (!empty($countries)): ?>
        <label for="countries">Выберите страну:</label>
        <select id="countries">
            <?php foreach ($countries as $c): ?>
                <option value="<?php echo htmlspecialchars($c); ?>"><?php echo htmlspecialchars($c); ?></option>
            <?php endforeach; ?>
        </select>
    <?php endif; ?>
