<?php
$filename = "countries.csv";
include "header.php";

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
            fputcsv($file, [$country], ",", '"', "\\");
            fclose($file);
        }
    }
}

$countries = getCountries($filename);
?>
<div class="container">
        <div class="row mt-5">
            <div class="col-md-6 offset-md-3">
                <form method="POST">
                    <div class="form-group d-flex align-items-center">
                        <input type="text" class="form-control" name="country" placeholder="Введите название страны" required>
                    </div>
                        <button type="submit"  class="form-control btn btn-success mt-3 text-center">Добавить</button>
                </form>
                <?php if (!empty($countries)): ?>
                    <div class="form-group d-flex align-items-center">
                        <label for="countries">Выберите страну:</label>
                    </div>
                    <select id="countries">
                        <?php foreach ($countries as $c): ?>
                            <option value="<?php echo htmlspecialchars($c); ?>"><?php echo htmlspecialchars($c); ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
            </div>
        </div>
</div>
<?php
include "footer.php";
?>