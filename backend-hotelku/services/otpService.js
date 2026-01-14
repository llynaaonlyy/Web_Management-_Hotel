import crypto from 'crypto';
import dotenv from 'dotenv';

dotenv.config();

// In-memory storage for OTPs (production: use Redis)
const otpStore = new Map();

const OTP_EXPIRY_MS = (process.env.OTP_EXPIRY_MINUTES || 5) * 60 * 1000;
const OTP_LENGTH = parseInt(process.env.OTP_LENGTH) || 6;

/**
 * Generate random OTP
 */
export const generateOTP = () => {
    const otp = crypto.randomInt(100000, 999999).toString();
    return otp.padStart(OTP_LENGTH, '0');
};

/**
 * Store OTP for a phone number
 */
export const storeOTP = (phoneNumber, otp) => {
    const expiryTime = Date.now() + OTP_EXPIRY_MS;
    
    otpStore.set(phoneNumber, {
        otp,
        expiryTime,
        createdAt: Date.now(),
        attempts: 0,
        used: false
    });

    console.log(`📝 OTP stored for ${phoneNumber}`);
    console.log(`⏰ Expires at: ${new Date(expiryTime).toLocaleString('id-ID')}`);
    console.log(`🔢 OTP: ${otp} (for testing)`);
};

/**
 * Verify OTP
 */
export const verifyOTP = (phoneNumber, inputOtp) => {
    const otpData = otpStore.get(phoneNumber);

    if (!otpData) {
        console.log(`❌ No OTP found for ${phoneNumber}`);
        return {
            valid: false,
            message: 'OTP tidak ditemukan atau sudah kadaluarsa'
        };
    }

    // Check if already used
    if (otpData.used) {
        console.log(`❌ OTP already used for ${phoneNumber}`);
        return {
            valid: false,
            message: 'OTP sudah pernah digunakan'
        };
    }

    // Check expiry
    if (Date.now() > otpData.expiryTime) {
        console.log(`❌ OTP expired for ${phoneNumber}`);
        otpStore.delete(phoneNumber);
        return {
            valid: false,
            message: 'OTP sudah kadaluarsa'
        };
    }

    // Increment attempts
    otpData.attempts++;

    // Check max attempts (3)
    if (otpData.attempts > 3) {
        console.log(`❌ Too many attempts for ${phoneNumber}`);
        otpStore.delete(phoneNumber);
        return {
            valid: false,
            message: 'Terlalu banyak percobaan. Silakan minta OTP baru'
        };
    }

    // Verify OTP
    if (otpData.otp !== inputOtp) {
        console.log(`❌ Invalid OTP for ${phoneNumber} (Attempt ${otpData.attempts}/3)`);
        return {
            valid: false,
            message: `OTP tidak valid. Sisa percobaan: ${3 - otpData.attempts}`
        };
    }

    // Mark as used
    otpData.used = true;
    console.log(`✅ OTP verified successfully for ${phoneNumber}`);

    return {
        valid: true,
        message: 'OTP berhasil diverifikasi'
    };
};

/**
 * Delete OTP after successful password reset
 */
export const deleteOTP = (phoneNumber) => {
    const deleted = otpStore.delete(phoneNumber);
    if (deleted) {
        console.log(`🗑️  OTP deleted for ${phoneNumber}`);
    }
    return deleted;
};

/**
 * Clean expired OTPs
 */
export const cleanExpiredOTPs = () => {
    const now = Date.now();
    let cleanedCount = 0;

    for (const [phoneNumber, otpData] of otpStore.entries()) {
        if (now > otpData.expiryTime) {
            otpStore.delete(phoneNumber);
            cleanedCount++;
        }
    }

    if (cleanedCount > 0) {
        console.log(`🧹 Cleaned ${cleanedCount} expired OTP(s)`);
    }
};

/**
 * Get OTP info (for debugging)
 */
export const getOTPInfo = (phoneNumber) => {
    return otpStore.get(phoneNumber);
};

/**
 * Get all active OTPs count (for monitoring)
 */
export const getActiveOTPsCount = () => {
    return otpStore.size;
};

export default {
    generateOTP,
    storeOTP,
    verifyOTP,
    deleteOTP,
    cleanExpiredOTPs,
    getOTPInfo,
    getActiveOTPsCount
};