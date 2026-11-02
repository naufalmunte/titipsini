<?php

namespace App\Services;

class QrisService
{
    public function generate($stringQris, $nominal)
    {
        // 1. Bersihkan string asli dan buang 4 digit CRC terakhir
        $payload = substr(trim($stringQris), 0, -4);

        // 2. Siapkan Nominal Baru (Tag 54)
        $val = (string)intval($nominal);
        $len = str_pad(strlen($val), 2, "0", STR_PAD_LEFT);
        $newNominal = "54" . $len . $val;

        // 3. Cari posisi Tag 58 (Mata Uang '5802ID') sebagai jangkar
        $pos58 = strpos($payload, '5802ID');
        
        if ($pos58 !== false) {
            // Kita ambil bagian sebelum 5802ID
            $before58 = substr($payload, 0, $pos58);
            $after58 = substr($payload, $pos58);
            
            // Hapus Tag 54 lama tepat sebelum Tag 58 (jika ada) agar tidak duplikat
            $before58 = preg_replace("/54[0-9]{2}[0-9]+$/", "", $before58);
            
            // Gabungkan kembali dengan nominal baru
            $payload = $before58 . $newNominal . $after58;
        } else {
            // Jika tidak ketemu, selipkan saja sebelum tag 63
            $payload .= $newNominal;
        }

        // 4. Hitung CRC16 CCITT-FALSE yang akurat
        return $payload . $this->crc16($payload);
    }

    protected function crc16($payload)
    {
        $res = 0xFFFF;
        for ($i = 0; $i < strlen($payload); $i++) {
            $res ^= ord($payload[$i]) << 8;
            for ($j = 0; $j < 8; $j++) {
                $res = ($res & 0x8000) ? ($res << 1) ^ 0x1021 : ($res << 1);
            }
        }
        $res &= 0xFFFF;
        return strtoupper(str_pad(dechex($res), 4, '0', STR_PAD_LEFT));
    }
}