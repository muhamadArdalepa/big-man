<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="api/select2-tim-teknisi" method="get">
        <div class="form-check">
            <label>
                <input type="checkbox" name="anggota[]" value="Berenang"> Berenang
            </label>
            <br>
            <label>
                <input type="checkbox" name="anggota[]" value="Bersepeda"> Bersepeda
            </label>
            <br>
            <label>
                <input type="checkbox" name="anggota[]" value="Membaca"> Membaca
            </label>
            <br>
            <label>
                <input type="checkbox" name="anggota[]" value="Memasak"> Memasak
            </label>
            <br>
            <input type="submit" value="Submit">
    </form>
</body>
</html>