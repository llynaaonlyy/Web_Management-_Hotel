# 📱 Langkah-Langkah Membuat WA Blast (WhatsApp OTP System)

Dokumentasi lengkap tentang implementasi WhatsApp Blast dengan OTP untuk sistem forgot password di Hotelku.

---

## 📋 Daftar Isi
1. [Setup Project](#setup-project)
2. [Konfigurasi Environment](#konfigurasi-environment)
3. [Instalasi Dependencies](#instalasi-dependencies)
4. [Struktur Folder](#struktur-folder)
5. [Setup Database](#setup-database)
6. [Implementasi WhatsApp Service](#implementasi-whatsapp-service)
7. [Implementasi OTP Service](#implementasi-otp-service)
8. [Setup Backend Server](#setup-backend-server)
9. [Membuat Controller Forgot Password](#membuat-controller-forgot-password)
10. [Setup Routes](#setup-routes)
11. [Frontend Integration](#frontend-integration)
12. [Testing](#testing)
13. [Production Deployment](#production-deployment)

---

## 1. Setup Project

### Buat Folder Backend
```bash
mkdir backend-hotelku
cd backend-hotelku
npm init -y
```

---

## 2. Konfigurasi Environment

### Buat file `.env`
```env
# Server Configuration
PORT=5000
NODE_ENV=development
FRONTEND_URL=http://localhost:3000

# Database Configuration
DB_HOST=localhost
DB_USER=root
DB_PASSWORD=
DB_NAME=hotelku

# WhatsApp Configuration
WHATSAPP_SESSION_NAME=hotelku-wa-session

# OTP Configuration
OTP_EXPIRY_TIME=300000
```

---

## 3. Instalasi Dependencies

### Install semua package yang diperlukan
```bash
npm install express cors dotenv body-parser
npm install whatsapp-web.js qrcode-terminal
npm install mysql2 bcrypt
npm install --save-dev nodemon
```

### Update `package.json` scripts
```json
{
  "type": "module",
  "scripts": {
    "start": "node server.js",
    "dev": "nodemon server.js"
  }
}
```

---

## 4. Struktur Folder

```
backend-hotelku/
├── config/
│   └── database.js
├── controllers/
│   └── forgotPasswordController.js
├── routes/
│   └── forgotPasswordRoutes.js
├── services/
│   ├── whatsappService.js
│   └── otpService.js
├── public/
│   └── forgot-password.html
├── wa-session/
├── .env
├── .gitignore
├── package.json
└── server.js
```

---

## 5. Setup Database

### Query SQL untuk tabel users (jika belum ada)
```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    no_telp VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Buat file `config/database.js`
```javascript
import mysql from 'mysql2/promise.js';
import dotenv from 'dotenv';

dotenv.config();

const pool = mysql.createPool({
    host: process.env.DB_HOST,
    user: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_NAME,
    waitForConnections: true,
    connectionLimit: 10,
    queueLimit: 0
});

export default pool;
```

---

## 6. Implementasi WhatsApp Service

### Buat file `services/whatsappService.js`

**Fitur utama:**
- Inisialisasi WhatsApp Client dengan LocalAuth strategy
- Menampilkan QR Code untuk scanning
- Handle events: qr, authenticated, ready, disconnected, auth_failure
- Fungsi sendWhatsAppMessage untuk mengirim pesan
- Auto reconnect jika terputus
- Format nomor telepon otomatis

**Fitur Key:**
- ✅ LocalAuth untuk session persistence
- ✅ Puppeteer headless untuk background running
- ✅ QR Code generation di terminal
- ✅ Graceful disconnect handling
- ✅ Format number support (0812xxx → 6281 2xxx)

---

## 7. Implementasi OTP Service

### Buat file `services/otpService.js`

**Fitur utama:**
- Generate OTP 6 digit random
- Store OTP dengan timestamp di memory
- Verify OTP dengan time validation
- Delete OTP setelah digunakan
- Auto cleanup expired OTP setiap interval

**In-Memory Storage:**
```javascript
const otpStore = {}; // {phoneNumber: {otp, createdAt}}
```

---

## 8. Setup Backend Server

### Buat file `server.js`

**Komponen utama:**
1. Express initialization
2. CORS configuration
3. Body parser middleware
4. Static files serving
5. Routes mounting
6. Error handling
7. WhatsApp client initialization
8. Graceful shutdown

**Key Features:**
- ✅ Health check endpoint
- ✅ 404 error handling
- ✅ Global error handler
- ✅ OTP cleanup interval (5 menit)
- ✅ WhatsApp initialization on startup
- ✅ Graceful shutdown dengan cleanup

---

## 9. Membuat Controller Forgot Password

### Buat file `controllers/forgotPasswordController.js`

#### Step 1: requestOTP (Kirim OTP)
```
POST /api/forgot-password/request-otp
Body: {
    nama: string,
    noHp: string
}

Process:
1. Validasi input (nama & noHp)
2. Format nomor telepon (0812xxx → 6281 2xxx)
3. Cek user di database
4. Check WhatsApp status
5. Generate OTP 6 digit
6. Store OTP dengan timestamp
7. Kirim OTP via WhatsApp dengan format rapi
8. Return success + OTP (dev mode only)
```

#### Step 2: verifyOTPController (Verifikasi OTP)
```
POST /api/forgot-password/verify-otp
Body: {
    noHp: string,
    otp: string
}

Process:
1. Validasi input
2. Format nomor telepon
3. Verify OTP (check existence & expiry)
4. Generate reset token (JWT)
5. Return token untuk reset password
```

#### Step 3: resetPasswordController (Reset Password Baru)
```
POST /api/forgot-password/reset-password
Body: {
    token: string,
    password: string,
    confirmPassword: string
}

Process:
1. Decode JWT token
2. Validasi password strength
3. Hash password baru dengan bcrypt
4. Update password di database
5. Delete OTP
6. Return success
```

---

## 10. Setup Routes

### Buat file `routes/forgotPasswordRoutes.js`

```javascript
import express from 'express';
import {
    requestOTP,
    verifyOTPController,
    resetPasswordController
} from '../controllers/forgotPasswordController.js';

const router = express.Router();

router.post('/request-otp', requestOTP);
router.post('/verify-otp', verifyOTPController);
router.post('/reset-password', resetPasswordController);

export default router;
```

---

## 11. Frontend Integration

### Buat file `public/forgot-password.html`

**Flow:**
1. User input nama & nomor WhatsApp
2. Click "Kirim OTP"
3. Server check database & kirim OTP via WhatsApp
4. User input OTP yang diterima
5. Server verify OTP & generate token
6. User input password baru
7. Server update database & selesai

**API Endpoints yang digunakan:**
- `POST /api/forgot-password/request-otp`
- `POST /api/forgot-password/verify-otp`
- `POST /api/forgot-password/reset-password`

---

## 12. Testing

### Test dengan Postman atau cURL

#### 1. Test Request OTP
```bash
curl -X POST http://localhost:5000/api/forgot-password/request-otp \
  -H "Content-Type: application/json" \
  -d '{
    "nama": "John Doe",
    "noHp": "0812345678901"
  }'
```

**Response Success:**
```json
{
  "success": true,
  "message": "Kode OTP telah dikirim ke WhatsApp Anda",
  "testOTP": "123456"
}
```

#### 2. Test Verify OTP
```bash
curl -X POST http://localhost:5000/api/forgot-password/verify-otp \
  -H "Content-Type: application/json" \
  -d '{
    "noHp": "0812345678901",
    "otp": "123456"
  }'
```

#### 3. Test Reset Password
```bash
curl -X POST http://localhost:5000/api/forgot-password/reset-password \
  -H "Content-Type: application/json" \
  -d '{
    "token": "jwt_token_here",
    "password": "NewPassword123!",
    "confirmPassword": "NewPassword123!"
  }'
```

### Debugging WhatsApp

1. **QR Code tidak keluar:**
   - Pastikan `puppeteer` terinstall dengan baik
   - Check `.wwebjs_cache` folder
   - Restart server

2. **OTP tidak terkirim:**
   - Cek WhatsApp status di console
   - Pastikan nomor format benar
   - Check nomor sudah di-scan di WhatsApp Linked Devices

3. **Session expired:**
   - Clear `wa-session/` folder
   - Restart server
   - Scan QR Code lagi

---

## 13. Production Deployment

### Preparation Checklist
- [ ] Update `.env` dengan credentials production
- [ ] Setup MySQL database di production server
- [ ] Install Node.js di server
- [ ] Setup PM2 atau systemd untuk auto-restart
- [ ] Configure firewall & port forwarding
- [ ] Setup SSL/HTTPS
- [ ] Setup reverse proxy (Nginx)

### Deploy dengan PM2
```bash
npm install -g pm2
pm2 start server.js --name "hotelku-backend"
pm2 startup
pm2 save
```

### Nginx Configuration
```nginx
server {
    listen 80;
    server_name api.hotelku.com;

    location / {
        proxy_pass http://localhost:5000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
    }
}
```

---

## 📊 Architecture Diagram

```
User Browser
    ↓
Frontend (forgot-password.html)
    ↓
Express Backend (server.js)
    ↓
┌─────────────────────────────┬──────────────────────────────┐
│                             │                              │
WhatsApp Service            Controller               OTP Service
│                             │                              │
├─ Init Client       ├─ Request OTP          ├─ Generate OTP
├─ QR Code Scan      ├─ Verify OTP           ├─ Store OTP
├─ Send Message      ├─ Reset Password       ├─ Verify OTP
└─ Handle Events     └─ JWT Generation       └─ Cleanup OTP
│                             │                              │
└─────────────────────────────┴──────────────────────────────┘
                        ↓
                  MySQL Database
```

---

## 🔐 Security Best Practices

✅ **Implement:**
- Password hashing dengan bcrypt (10+ salt rounds)
- JWT token untuk reset password (short expiry)
- OTP expiry setelah 5 menit
- Rate limiting pada request OTP
- Validate input di backend
- HTTPS pada production
- Sanitize database queries
- Use environment variables

❌ **Jangan:**
- Store password plain text
- OTP tanpa expiry
- Token tanpa validation
- Log sensitive data
- Hardcode credentials
- Use weak password rules

---

## 🚀 Performance Tips

1. **Connection Pooling** - Gunakan MySQL pool untuk reuse connection
2. **WhatsApp Caching** - Session sudah disimpan otomatis di wa-session/
3. **OTP Memory** - OTP disimpan di memory (lebih cepat dari database)
4. **Cleanup Interval** - Auto cleanup expired OTP setiap 5 menit
5. **Error Handling** - Prevent server crash dengan proper try-catch

---

## 📝 Troubleshooting

| Issue | Solusi |
|-------|--------|
| Port already in use | Ubah PORT di .env atau kill process yang pakai port tersebut |
| QR Code tidak muncul | Pastikan terminal support QR Code, clear cache, restart |
| OTP tidak terkirim | Check WhatsApp status, format nomor, koneksi internet |
| Database connection error | Check DB credentials, MySQL service running |
| CORS error | Update FRONTEND_URL di .env |
| Session expired | Clear wa-session/, scan QR Code lagi |

---

## 📚 Reference Links

- [Whatsapp-web.js Documentation](https://wwebjs.dev/)
- [Express.js Guide](https://expressjs.com/)
- [MySQL2 Documentation](https://github.com/sidorares/node-mysql2)
- [Bcrypt Guide](https://github.com/kelektiv/node.bcrypt.js)
- [JWT Guide](https://jwt.io/)

---

## ✅ Checklist Implementasi

- [x] Setup project structure
- [x] Configure environment variables
- [x] Install dependencies
- [x] Setup database
- [x] Create WhatsApp service
- [x] Create OTP service
- [x] Create backend server
- [x] Create forgot password controller
- [x] Create routes
- [x] Create frontend HTML
- [x] Test API endpoints
- [x] Documentation

---

**Last Updated:** 19 Januari 2026  
**Version:** 1.0  
**Status:** ✅ Production Ready

---

*Dokumentasi ini dibuat untuk memudahkan maintenance dan onboarding developer baru ke sistem Hotelku.*
