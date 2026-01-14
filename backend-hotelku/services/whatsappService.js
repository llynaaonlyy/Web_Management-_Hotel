import pkg from 'whatsapp-web.js';
const { Client, LocalAuth } = pkg;
import qrcode from 'qrcode-terminal';
import dotenv from 'dotenv';

dotenv.config();

let whatsappClient = null;
let isReady = false;
let isInitializing = false;

export const initWhatsAppClient = () => {
    if (isInitializing) {
        console.log('⏳ WhatsApp client is already initializing...');
        return;
    }

    if (whatsappClient && isReady) {
        console.log('✅ WhatsApp client is already ready');
        return;
    }

    isInitializing = true;

    console.log('🔄 Initializing WhatsApp client...');

    whatsappClient = new Client({
        authStrategy: new LocalAuth({
            clientId: process.env.WHATSAPP_SESSION_NAME || 'hotelku-wa-session',
            dataPath: './wa-session'
        }),
        puppeteer: {
            headless: true,
            args: [
                '--no-sandbox',
                '--disable-setuid-sandbox',
                '--disable-dev-shm-usage',
                '--disable-accelerated-2d-canvas',
                '--no-first-run',
                '--no-zygote',
                '--disable-gpu'
            ]
        }
    });

    // Event: QR Code
    whatsappClient.on('qr', (qr) => {
        console.log('\n╔═══════════════════════════════════════════════╗');
        console.log('║        📱 SCAN QR CODE WITH WHATSAPP         ║');
        console.log('╚═══════════════════════════════════════════════╝\n');
        qrcode.generate(qr, { small: true });
        console.log('\n💡 Buka WhatsApp → Linked Devices → Scan QR Code di atas');
        console.log('⏰ QR Code berlaku selama 60 detik\n');
    });

    // Event: Authenticated
    whatsappClient.on('authenticated', () => {
        console.log('✅ WhatsApp authenticated successfully');
    });

    // Event: Auth Failure
    whatsappClient.on('auth_failure', (msg) => {
        console.error('❌ WhatsApp authentication failed:', msg);
        isInitializing = false;
        isReady = false;
    });

    // Event: Ready
    whatsappClient.on('ready', () => {
        isReady = true;
        isInitializing = false;
        console.log('\n╔═══════════════════════════════════════════════╗');
        console.log('║   ✅ WHATSAPP CLIENT READY, SIAP KIRIM OTP   ║');
        console.log('╚═══════════════════════════════════════════════╝\n');
        console.log('📨 WhatsApp OTP service is now active');
        console.log('🔔 Bot siap menerima permintaan kirim OTP\n');
    });

    // Event: Disconnected
    whatsappClient.on('disconnected', (reason) => {
        console.log('⚠️  WhatsApp client disconnected:', reason);
        isReady = false;
        isInitializing = false;
        
        // Auto reconnect after 5 seconds
        setTimeout(() => {
            console.log('🔄 Attempting to reconnect WhatsApp...');
            initWhatsAppClient();
        }, 5000);
    });

    // Event: Loading Screen
    whatsappClient.on('loading_screen', (percent, message) => {
        console.log(`⏳ Loading WhatsApp: ${percent}% - ${message}`);
    });

    // Initialize
    whatsappClient.initialize().catch(err => {
        console.error('❌ Failed to initialize WhatsApp:', err.message);
        isInitializing = false;
        isReady = false;
    });
};

export const sendWhatsAppMessage = async (phoneNumber, message) => {
    if (!whatsappClient || !isReady) {
        throw new Error('WhatsApp client belum siap. Tunggu hingga QR Code di-scan.');
    }

    try {
        // Format phone number: remove +, spaces, and ensure it starts with country code
        let formattedNumber = phoneNumber.replace(/[^\d]/g, '');
        
        // If starts with 0, replace with 62 (Indonesia)
        if (formattedNumber.startsWith('0')) {
            formattedNumber = '62' + formattedNumber.substring(1);
        }
        
        // Add @c.us for WhatsApp ID
        const chatId = `${formattedNumber}@c.us`;

        console.log(`📤 Sending WhatsApp message to: ${formattedNumber}`);
        
        await whatsappClient.sendMessage(chatId, message);
        
        console.log(`✅ WhatsApp message sent successfully to ${formattedNumber}`);
        
        return true;
    } catch (error) {
        console.error('❌ Failed to send WhatsApp message:', error.message);
        throw new Error(`Gagal mengirim WhatsApp: ${error.message}`);
    }
};

export const getWhatsAppStatus = () => {
    return {
        isReady,
        isInitializing,
        hasClient: whatsappClient !== null
    };
};

export const disconnectWhatsApp = async () => {
    if (whatsappClient) {
        console.log('🔌 Disconnecting WhatsApp client...');
        try {
            await whatsappClient.destroy();
            whatsappClient = null;
            isReady = false;
            isInitializing = false;
            console.log('✅ WhatsApp client disconnected');
        } catch (error) {
            console.error('❌ Error disconnecting WhatsApp:', error.message);
        }
    }
};

export default {
    initWhatsAppClient,
    sendWhatsAppMessage,
    getWhatsAppStatus,
    disconnectWhatsApp
};