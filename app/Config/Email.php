<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail = 'noreply@hotelku.com';
    public string $fromName = 'Hotelku';
    public string $recipients = '';

    /**
     * The "user agent"
     */
    public string $userAgent = 'CodeIgniter';

    /**
     * The mail sending protocol: mail, sendmail, smtp
     */
    public string $protocol = 'smtp';

    /**
     * The server path to Sendmail.
     */
    public string $mailPath = '/usr/sbin/sendmail';

    /**
     * SMTP Server Hostname
     * 
     * Untuk Gmail: smtp.gmail.com
     * Untuk Outlook: smtp-mail.outlook.com
     * Untuk Yahoo: smtp.mail.yahoo.com
     */
    public string $SMTPHost = 'smtp.gmail.com';

    /**
     * SMTP Username
     * Gunakan email Anda
     */
    public string $SMTPUser = 'anajariyatunkhasanah1@gmail.com';

    /**
     * SMTP Password
     * Untuk Gmail, gunakan App Password (bukan password akun)
     * Cara membuat: https://support.google.com/accounts/answer/185833
     */
    public string $SMTPPass = 'pgpx rkrz pcao kulq';

    /**
     * SMTP Port
     * Port 587 untuk TLS
     * Port 465 untuk SSL
     */
    
    public int $SMTPPort = 587;

    /**
     * SMTP Timeout (in seconds)
     */
    public int $SMTPTimeout = 5;

    /**
     * Enable persistent SMTP connections
     */
    public bool $SMTPKeepAlive = false;

    /**
     * SMTP Encryption.
     * 
     * @var string '', 'tls' or 'ssl'. 'tls' will issue a STARTTLS command
     *             to the server. 'ssl' means implicit SSL. Connection on port
     *             465 should set this to ''.
     */
    public string $SMTPCrypto = 'tls';

    /**
     * Enable word-wrap
     */
    public bool $wordWrap = true;

    /**
     * Character count to wrap at
     */
    public int $wrapChars = 76;

    /**
     * Type of mail, either 'text' or 'html'
     */
    public string $mailType = 'html';

    /**
     * Character set (utf-8, iso-8859-1, etc.)
     */
    public string $charset = 'UTF-8';

    /**
     * Whether to validate the email address
     */
    public bool $validate = true;

    /**
     * Email Priority. 1 = highest. 5 = lowest. 3 = normal
     */
    public int $priority = 3;

    /**
     * Newline character. (Use "\r\n" to comply with RFC 822)
     */
    public string $CRLF = "\r\n";

    /**
     * Newline character. (Use "\r\n" to comply with RFC 822)
     */
    public string $newline = "\r\n";

    /**
     * Enable BCC Batch Mode.
     */
    public bool $BCCBatchMode = false;

    /**
     * Number of emails in each BCC batch
     */
    public int $BCCBatchSize = 200;

    /**
     * Enable notify message from server
     */
    public bool $DSN = false;
}

// ============================================
// CATATAN KONFIGURASI EMAIL
// ============================================
/*
UNTUK MENGGUNAKAN GMAIL:

1. Buat App Password di Google Account:
   - Buka https://myaccount.google.com/security
   - Cari "2-Step Verification" dan aktifkan
   - Cari "App passwords"
   - Pilih "Mail" dan "Other (Custom name)"
   - Beri nama "Hotelku"
   - Copy password yang digenerate (16 karakter)

2. Update konfigurasi di app/Config/Email.php:
   $SMTPUser = 'your-email@gmail.com';
   $SMTPPass = 'your-16-char-app-password';

3. Pastikan extension openssl aktif di php.ini

UNTUK TESTING LOKAL (tanpa kirim email asli):
Bisa gunakan Mailtrap.io:
   $SMTPHost = 'smtp.mailtrap.io';
   $SMTPUser = 'your-mailtrap-username';
   $SMTPPass = 'your-mailtrap-password';
   $SMTPPort = 2525;
*/