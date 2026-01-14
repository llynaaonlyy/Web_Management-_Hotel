import express from 'express';
import {
    requestOTP,
    verifyOTPController,
    resetPassword
} from '../controllers/forgotPasswordController.js';

const router = express.Router();

// POST /api/forgot-password/request-otp
router.post('/request-otp', requestOTP);

// POST /api/forgot-password/verify-otp
router.post('/verify-otp', verifyOTPController);

// POST /api/forgot-password/reset-password
router.post('/reset-password', resetPassword);

export default router;