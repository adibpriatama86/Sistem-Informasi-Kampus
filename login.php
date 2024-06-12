<?php

// ambil pesan jika ada
if (isset($_GET["pesan"])) {
    $pesan = $_GET["pesan"];
}

// cek apakah form telah di submit
if (isset($_POST["submit"])) {
    // form telah disubmit, proses data

    // ambil nilai form
    $username = htmlentities(strip_tags(trim($_POST["username"])));
    $password = htmlentities(strip_tags(trim($_POST["password"])));

    // siapkan variabel untuk menampung pesan error
    $pesan_error = "";

    // cek apakah "username" sudah diisi atau tidak
    if (empty($username)) {
        $pesan_error .= "Username belum diisi <br>";
    }

    // cek apakah "password" sudah diisi atau tidak
    if (empty($password)) {
        $pesan_error .= "Password belum diisi <br>";
    }

    // buat koneksi ke mysql dari file connection.php
    include("connection.php");

    // filter dengan mysqli_real_escape_string
    $username = mysqli_real_escape_string($link, $username);
    $password = mysqli_real_escape_string($link, $password);

    // generate hashing
    $password_sha1 = sha1($password);

    // cek apakah username dan password ada di tabel admin
    $query = "SELECT * FROM admin WHERE username = '$username' AND password = '$password_sha1'";
    $result = mysqli_query($link, $query);

    if (mysqli_num_rows($result) == 0) {
        // data tidak ditemukan, buat pesan error
        $pesan_error .= "Username dan/atau Password tidak sesuai";
    }

    // bebaskan memory
    mysqli_free_result($result);

    // tutup koneksi dengan database MySQL
    mysqli_close($link);

    // jika lolos validasi, set session
    if ($pesan_error === "") {
        session_start();
        $_SESSION["nama"] = $username;
        header("Location: tampil_mahasiswa.php");
    }
} else {

    // form belum disubmit atau halaman ini tampil untuk pertama kali
    // berikan nilai awal untuk semua isian form
    $pesan_error = "";
    $username = "";
    $password = "";
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Sistem Informasi Mahasiswa</title>
    <link rel="icon" href="favicon.png" type="image/png">
    <style>
        body {
            background-color: #f3f2f1;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 18px;
            text-align: center;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333333;
        }

        .error {
            background-color: #fdecea;
            color: #d93025;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #d93025;
            border-radius: 4px;
            text-align: left;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            width: 100%;
            text-align: left;
            margin-bottom: 5px;
            font-weight: 600;
        }

        input[type=text],
        input[type=password] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type=submit] {
            width: 100%;
            padding: 12px;
            background-color: #0078d4;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s;
        }

        input[type=submit]:hover {
            background-color: #005a9e;
        }

        .pesan {
            background-color: #e7f3fe;
            color: #31708f;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #bce8f1;
            border-radius: 4px;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Selamat Datang</h1>
        <h3>Sistem Informasi Kampusku</h3>
        <?php
        // tampilkan pesan jika ada
        if (isset($pesan)) {
            echo "<div class=\"pesan\">$pesan</div>";
        }

        // tampilkan error jika ada
        if ($pesan_error !== "") {
            echo "<div class=\"error\">$pesan_error</div>";
        }
        ?>

        <form action="login.php" method="post">
            <fieldset>
                <legend>Login</legend>
                <p>
                    <label for="username">Username : </label>
                    <input type="text" name="username" id="username" value="<?php echo $username ?>">
                </p>
                <p>
                    <label for="password">Password : </label>
                    <input type="password" name="password" id="password" value="<?php echo $username ?>">
                </p>
                <p>
                    <input type="submit" name="submit" value="Log In">
                </p>
            </fieldset>
        </form>
    </div>
</body>

</html>