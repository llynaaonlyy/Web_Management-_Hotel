import bcrypt from 'bcrypt';
import db from '../config/database.js';
import { generateOTP, storeOTP, verifyOTP, deleteOTP } from '../services/otpService.js';
import { sendWhatsAppMessage, getWhatsAppStatus } from '../services/whatsappService.js';

/**
 * Request OTP - Step 1
 */
export const requestOTP = async (req, res) => {
    try {
        const { nama, noHp } = req.body;

        console.log('\n📥 Request OTP received');
        console.log(`👤 Nama: ${nama}`);
        console.log(`📱 No HP: ${noHp}`);

        // Validation
        if (!nama || !noHp) {
            console.log('❌ Validation failed: Missing fields');
            return res.status(400).json({
                success: false,
                message: 'Nama dan nomor WhatsApp harus diisi'
            });
        }

        if (nama.length < 3) {
            return res.status(400).json({
                success: false,
                message: 'Nama minimal 3 karakter'
            });
        }

        // Format phone number
        let formattedPhone = noHp.replace(/[^\d]/g, '');
        if (formattedPhone.startsWith('0')) {
            formattedPhone = '62' + formattedPhone.substring(1);
        }
        if (!formattedPhone.startsWith('62')) {
            formattedPhone = '62' + formattedPhone;
        }

        console.log(`📞 Formatted phone: ${formattedPhone}`);

        // Check if user exists in database
        const [users] = await db.query(
            'SELECT * FROM users WHERE nama = ? AND no_telp = ?',
            [nama, formattedPhone]
        );

        if (users.length === 0) {
            console.log('❌ User not found in database');
            return res.status(404).json({
                success: false,
                message: 'Data tidak ditemukan. Pastikan nama dan nomor WhatsApp sudah terdaftar'
            });
        }

        const user = users[0];
        console.log(`✅ User found: ${user.email}`);

        // Check WhatsApp status
        const waStatus = getWhatsAppStatus();
        if (!waStatus.isReady) {
            console.log('❌ WhatsApp client not ready');
            return res.status(503).json({
                success: false,
                message: 'WhatsApp bot belum siap. Silakan tunggu beberapa saat atau hubungi admin'
            });
        }

        // Generate OTP
        const otp = generateOTP();
        storeOTP(formattedPhone, otp);

        // Send OTP via WhatsApp
        try {
            const message = `🔐 *Hotelku - Kode OTP*\n\n` +
                          `Halo *${nama}*,\n\n` +
                          `Kode OTP Anda: *${otp}*\n\n` +
                          `Kode ini berlaku selama 5 menit.\n` +
                          `Jangan bagikan kode ini kepada siapapun.\n\n` +
                          `Jika Anda tidak meminta kode ini, abaikan pesan ini.\n\n` +
                          `Salam,\n*Tim Hotelku*`;

            await sendWhatsAppMessage(formattedPhone, message);

            console.log('✅ OTP sent successfully');

            return res.status(200).json({
                success: true,
                message: 'Kode OTP telah dikirim ke WhatsApp Anda',
                testOTP: process.env.NODE_ENV === 'development' ? otp : undefined
            });

        } catch (waError) {
            console.error('❌ WhatsApp send error:', waError.message);
            return res.status(500).json({
                success: false,
                message: 'Gagal mengirim OTP. Pastikan nomor WhatsApp aktif'
            });
        }

    } catch (error) {
        console.error('❌ Error in requestOTP:', error);
        return res.status(500).json({
            success: false,
            message: 'Terjadi kesalahan server'
        });
    }
};

/**
 * Verify OTP - Step 2
 */
export const verifyOTPController = async (req, res) => {
    try {
        const { noHp, otp } = req.body;

        console.log('\n📥 Verify OTP received');
        console.log(`📱 No HP: ${noHp}`);
        console.log(`🔢 OTP: ${otp}`);

        // Validation
        if (!noHp || !otp) {
            return res.status(400).json({
                success: false,
                message: 'Nomor WhatsApp dan OTP harus diisi'
            });
        }

        if (otp.length !== 6 || isNaN(otp)) {
            return res.status(400).json({
                success: false,
                message: 'OTP harus 6 digit angka'
            });
        }

        // Format phone number
        let formattedPhone = noHp.replace(/[^\d]/g, '');
        if (formattedPhone.startsWith('0')) {
            formattedPhone = '62' + formattedPhone.substring(1);
        }

        // Verify OTP
        const verification = verifyOTP(formattedPhone, otp);

        if (!verification.valid) {
            console.log('❌ OTP verification failed');
            return res.status(400).json({
                success: false,
                message: verification.message
            });
        }

        console.log('✅ OTP verified successfully');

        return res.status(200).json({
            success: true,
            message: 'OTP berhasil diverifikasi'
        });

    } catch (error) {
        console.error('❌ Error in verifyOTP:', error);
        return res.status(500).json({
            success: false,
            message: 'Terjadi kesalahan server'
        });
    }
};

export const resetPassword = async (req, res) => {
    try {
        const { noHp, newPassword, confirmPassword } = req.body;

        console.log('\n📥 Reset Password received');
        console.log(`📱 No HP: ${noHp}`);

        if (!noHp || !newPassword || !confirmPassword) {
            return res.status(400).json({
                success: false,
                message: 'Semua field harus diisi'
            });
        }

        if (newPassword.length < 6) {
            return res.status(400).json({
                success: false,
                message: 'Password minimal 6 karakter'
            });
        }

        if (newPassword !== confirmPassword) {
            return res.status(400).json({
                success: false,
                message: 'Password tidak cocok'
            });
        }

        let formattedPhone = noHp.replace(/[^\d]/g, '');
        if (formattedPhone.startsWith('0')) {
            formattedPhone = '62' + formattedPhone.substring(1);
        }

        const [users] = await db.query(
            'SELECT * FROM users WHERE no_telp = ?',
            [formattedPhone]
        );

        if (users.length === 0) {
            return res.status(404).json({
                success: false,
                message: 'User tidak ditemukan'
            });
        }

        const hashedPassword = await bcrypt.hash(newPassword, 10);

        await db.query(
            'UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?',
            [hashedPassword, users[0].id]
        );

        deleteOTP(formattedPhone);

        return res.status(200).json({
            success: true,
            message: 'Password berhasil diubah'
        });

    } catch (error) {
        console.error(error);
        return res.status(500).json({
            success: false,
            message: 'Terjadi kesalahan server'
        });
    }
};

/**
 * Reset Password - Step 3
 
export const resetPassword = async (req, res) => {
    try {
        const { noHp, newPassword, confirmPassword } = req.body;

        console.log('\n📥 Reset Password received');
        console.log(`📱 No HP: ${noHp}`);

        // Validation
        if (!noHp || !newPassword || !confirmPassword) {
            return res.status(400).json({
                success: false,
                message: 'Semua field harus diisi'
            });
        }

        if (newPassword.length < 6) {
            return res.status(400).json({
                success: false,
                message: 'Password minimal 6 karakter'
            });
        }

        if (newPassword !== confirmPassword) {
            return res.status(400).json({
                success: false,
                message: 'Password tidak cocok'
            });
        }

        // Format phone number
        let formattedPhone = noHp.replace(/[^\d]/g, '');
        if (formattedPhone.startsWith('0')) {
            formattedPhone = '62' + formattedPhone.substring(1);
        }

        // Verify OTP one more time
        // const verification = verifyOTP(formattedPhone, otp);
        // if (!verification.valid) {
        //     return res.status(400).json({
        //         success: false,
        //         message: verification.message
        //     });
        // }

        // Get user from database
        const [users] = await db.query(
            'SELECT * FROM users WHERE no_telp = ?',
            [formattedPhone]
        );

        if (users.length === 0) {
            return res.status(404).json({
                success: false,
                message: 'User tidak ditemukan'
            });
        }

        const user = users[0];

        // Hash new password
        const hashedPassword = await bcrypt.hash(newPassword, 10);

        // Update password in database
        await db.query(
            'UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?',
            [hashedPassword, user.id]
        );

        // Delete OTP after successful reset
        deleteOTP(formattedPhone);

        console.log(`✅ Password updated successfully for user: ${user.email}`);
        console.log(`📧 User email: ${user.email}`);
        console.log(`🔑 New password hash: ${hashedPassword.substring(0, 20)}...`);

        return res.status(200).json({
            success: true,
            message: 'Password berhasil diubah. Silakan login dengan password baru Anda'
        });

    } catch (error) {
        console.error('❌ Error in resetPassword:', error);
        return res.status(500).json({
            success: false,
            message: 'Terjadi kesalahan server'
        });
    }
}; */

export default {
    requestOTP,
    verifyOTPController,
    resetPassword
};