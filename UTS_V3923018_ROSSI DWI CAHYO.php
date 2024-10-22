<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enkripsi & Dekripsi - Caesar dan Vigenere Cipher</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        label {
            font-size: 16px;
            margin-bottom: 5px;
            color: #555;
        }
        textarea, input[type="number"], input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 10px;
        }
        textarea {
            grid-column: 1 / 3;
            height: 120px;
        }
        .button-container {
            grid-column: 1 / 3;
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .btn {
            padding: 10px 30px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .result {
            grid-column: 1 / 3;
            background-color: #f9f9f9;
            padding: 15px;
            margin-top: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
            color: #333;
        }
        .result strong {
            font-size: 18px;
            color: #444;
        }
    </style> 
</head>
<body>
    <div class="container">
        <h1>Enkripsi & Dekripsi - Caesar dan Vigenere Cipher</h1>
        <form method="POST" action="">
            <label for="plain_text">Plain Text:</label>
            <textarea name="plain_text" id="plain_text" placeholder="Masukkan teks yang akan dienkripsi..." required></textarea>

            <label for="caesar_shift">Kunci Numerik (Caesar Cipher):</label>
            <input type="number" name="caesar_shift" id="caesar_shift" value="" required>

            <label for="vigenere_key">Kunci Teks (Vigenere Cipher):</label>
            <input type="text" name="vigenere_key" id="vigenere_key" value="" required>

            <div class="button-container">
                <button class="btn" type="submit" name="encrypt">Enkripsi</button>
                <button class="btn" type="submit" name="decrypt">Dekripsi</button>
            </div>
        </form>

        <?php
        // Fungsi Caesar Cipher dan Vigenere Cipher (tidak diubah)
        function caesar_encrypt($plain_text, $shift) {
            $encrypted = '';
            $shift = $shift % 26;
            for ($i = 0; $i < strlen($plain_text); $i++) {
                $char = $plain_text[$i];
                if (ctype_alpha($char)) {
                    $is_upper = ctype_upper($char);
                    $ascii_offset = $is_upper ? 65 : 97;
                    $new_char = chr(($ascii_offset + (ord($char) - $ascii_offset + $shift) % 26));
                    $encrypted .= $new_char;
                } else {
                    $encrypted .= $char;
                }
            }
            return $encrypted;
        }

        function caesar_decrypt($cipher_text, $shift) {
            $decrypted = '';
            $shift = $shift % 26;
            for ($i = 0; $i < strlen($cipher_text); $i++) {
                $char = $cipher_text[$i];
                if (ctype_alpha($char)) {
                    $is_upper = ctype_upper($char);
                    $ascii_offset = $is_upper ? 65 : 97;
                    $new_char = chr(($ascii_offset + (ord($char) - $ascii_offset - $shift + 26) % 26));
                    $decrypted .= $new_char;
                } else {
                    $decrypted .= $char;
                }
            }
            return $decrypted;
        }

        function vigenere_encrypt($plain_text, $key) {
            $encrypted = '';
            $key = strtoupper($key);
            $key_length = strlen($key);
            $j = 0;
            for ($i = 0; $i < strlen($plain_text); $i++) {
                $char = $plain_text[$i];
                if (ctype_alpha($char)) {
                    $is_upper = ctype_upper($char);
                    $ascii_offset = $is_upper ? 65 : 97;
                    $key_shift = ord($key[$j % $key_length]) - 65;
                    $new_char = chr(($ascii_offset + (ord($char) - $ascii_offset + $key_shift) % 26));
                    $encrypted .= $new_char;
                    $j++;
                } else {
                    $encrypted .= $char;
                }
            }
            return $encrypted;
        }

        function vigenere_decrypt($cipher_text, $key) {
            $decrypted = '';
            $key = strtoupper($key);
            $key_length = strlen($key);
            $j = 0;
            for ($i = 0; $i < strlen($cipher_text); $i++) {
                $char = $cipher_text[$i];
                if (ctype_alpha($char)) {
                    $is_upper = ctype_upper($char);
                    $ascii_offset = $is_upper ? 65 : 97;
                    $key_shift = ord($key[$j % $key_length]) - 65;
                    $new_char = chr(($ascii_offset + (ord($char) - $ascii_offset - $key_shift + 26) % 26));
                    $decrypted .= $new_char;
                    $j++;
                } else {
                    $decrypted .= $char;
                }
            }
            return $decrypted;
        }

        function encrypt_combination($plain_text, $caesar_shift, $vigenere_key) {
            $caesar_encrypted = caesar_encrypt($plain_text, $caesar_shift);
            return vigenere_encrypt($caesar_encrypted, $vigenere_key);
        }

        function decrypt_combination($cipher_text, $caesar_shift, $vigenere_key) {
            $vigenere_decrypted = vigenere_decrypt($cipher_text, $vigenere_key);
            return caesar_decrypt($vigenere_decrypted, $caesar_shift);
        }

        // Proses Form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $plain_text = $_POST['plain_text'];
            $caesar_shift = intval($_POST['caesar_shift']);
            $vigenere_key = $_POST['vigenere_key'];

            if (isset($_POST['encrypt'])) {
                $cipher_text = encrypt_combination($plain_text, $caesar_shift, $vigenere_key);
                echo "<div class='result'><strong>Hasil Enkripsi:</strong><br>$cipher_text</div>";
            }

            if (isset($_POST['decrypt'])) {
                $decrypted_text = decrypt_combination($plain_text, $caesar_shift, $vigenere_key);
                echo "<div class='result'><strong>Hasil Dekripsi:</strong><br>$decrypted_text</div>";
            }
        }
        ?>
    </div>
</body>
</html>
