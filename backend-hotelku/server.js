import express from 'express';
import cors from 'cors';
import dotenv from 'dotenv';
import bodyParser from 'body-parser';
import path from 'path';
import { fileURLToPath } from 'url';
import { initWhatsAppClient, disconnectWhatsApp } from './services/whatsappService.js';
import { cleanExpiredOTPs } from './services/otpService.js';
import forgotPasswordRoutes from './routes/forgotPasswordRoutes.js';

dotenv.config();

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const app = express();
const PORT = process.env.PORT || 5000;

// Middleware
app.use(cors({
    origin: process.env.FRONTEND_URL || '*',
    credentials: true
}));

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// Serve static files from public directory
app.use(express.static(path.join(__dirname, 'public')));

// Health check endpoint
app.get('/api/health', (req, res) => {
    res.status(200).json({
        success: true,
        message: 'Backend Hotelku is running',
        timestamp: new Date().toISOString(),
        database: 'MySQL - hotelku'
    });
});

// Routes
app.use('/api/forgot-password', forgotPasswordRoutes);

// Serve forgot password page
app.get('/forgot-password', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'forgot-password.html'));
});

// 404 Handler
app.use((req, res) => {
    res.status(404).json({
        success: false,
        message: 'Endpoint tidak ditemukan',
        path: req.path,
        method: req.method
    });
});

// Error Handler
app.use((err, req, res, next) => {
    console.error('❌ Error:', err);
    res.status(500).json({
        success: false,
        message: 'Terjadi error di server',
        error: process.env.NODE_ENV === 'development' ? err.message : undefined
    });
});

// Start Server
const server = app.listen(PORT, () => {
    console.log('\n╔═══════════════════════════════════════════════╗');
    console.log('║   🏨 HOTELKU BACKEND SERVER WITH WA OTP      ║');
    console.log('╚═══════════════════════════════════════════════╝');
    console.log(`\n📍 Server running at: http://localhost:${PORT}`);
    console.log(`🔗 Forgot Password: http://localhost:${PORT}/forgot-password`);
    console.log(`💾 Database: ${process.env.DB_NAME} (MySQL)`);
    console.log(`📱 WhatsApp Session: ${process.env.WHATSAPP_SESSION_NAME}`);
    console.log('\n⏳ Initializing WhatsApp Bot...\n');

    // Initialize WhatsApp Client
    initWhatsAppClient();

    // Clean expired OTPs every 5 minutes
    setInterval(() => {
        cleanExpiredOTPs();
    }, 5 * 60 * 1000);
});

// Error Handling
server.on('error', (error) => {
    console.error('❌ Server error:', error.message);
    if (error.code === 'EADDRINUSE') {
        console.log(`\n⚠️  Port ${PORT} sudah dipakai!`);
        console.log('💡 Solusi: Ubah PORT di .env atau stop aplikasi lain\n');
    }
});

// Graceful Shutdown
process.on('SIGINT', async () => {
    console.log('\n\n⏹️  Shutting down server gracefully...');
    await disconnectWhatsApp();
    server.close(() => {
        console.log('✅ Server closed');
        process.exit(0);
    });
});

process.on('SIGTERM', async () => {
    console.log('\n\n⏹️  Shutting down server gracefully...');
    await disconnectWhatsApp();
    server.close(() => {
        console.log('✅ Server closed');
        process.exit(0);
    });
});

export default app;