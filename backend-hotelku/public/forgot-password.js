// Configuration
const API_BASE_URL = 'http://localhost:5000/api/forgot-password';
const OTP_EXPIRY_SECONDS = 5 * 60; // 5 minutes

// State
let currentStep = 1;
let otpData = {
    nama: '',
    noHp: '',
    otp: ''
};
let timerInterval = null;
let otpTimeLeft = OTP_EXPIRY_SECONDS;

// ============================================
// UTILITY FUNCTIONS
// ============================================

function showMessage(elementId, message, type = 'error') {
    const element = document.getElementById(elementId);
    element.textContent = message;
    element.classList.remove('hidden');
    
    if (type === 'success' || type === 'info') {
        setTimeout(() => {
            element.classList.add('hidden');
        }, 5000);
    }
}

function hideAllMessages() {
    ['error1', 'success1', 'info1', 'error2', 'success2', 'error3', 'success3'].forEach(id => {
        document.getElementById(id).classList.add('hidden');
    });
}

function goToStep(stepNumber) {
    // Hide all steps
    document.getElementById('step1').classList.remove('active');
    document.getElementById('step2').classList.remove('active');
    document.getElementById('step3').classList.remove('active');
    document.getElementById('stepSuccess').classList.remove('active');
    
    hideAllMessages();
    
    // Reset step dots
    document.getElementById('stepDot1').classList.remove('active', 'completed');
    document.getElementById('stepDot2').classList.remove('active', 'completed');
    document.getElementById('stepDot3').classList.remove('active', 'completed');
    
    // Show current step
    if (stepNumber === 1) {
        document.getElementById('step1').classList.add('active');
        document.getElementById('stepDot1').classList.add('active');
    } else if (stepNumber === 2) {
        document.getElementById('step2').classList.add('active');
        document.getElementById('stepDot1').classList.add('completed');
        document.getElementById('stepDot2').classList.add('active');
    } else if (stepNumber === 3) {
        document.getElementById('step3').classList.add('active');
        document.getElementById('stepDot1').classList.add('completed');
        document.getElementById('stepDot2').classList.add('completed');
        document.getElementById('stepDot3').classList.add('active');
    } else if (stepNumber === 'success') {
        document.getElementById('stepSuccess').classList.add('active');
        document.getElementById('stepDot1').classList.add('completed');
        document.getElementById('stepDot2').classList.add('completed');
        document.getElementById('stepDot3').classList.add('completed');
    }
    
    currentStep = stepNumber;
    window.scrollTo(0, 0);
}

function formatPhoneNumber(phone) {
    // Remove all non-digit characters
    let cleaned = phone.replace(/\D/g, '');
    
    // If starts with 0, replace with 62
    if (cleaned.startsWith('0')) {
        cleaned = '62' + cleaned.substring(1);
    }
    
    // If doesn't start with 62, add it
    if (!cleaned.startsWith('62')) {
        cleaned = '62' + cleaned;
    }
    
    return cleaned;
}

function formatTimer(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = seconds % 60;
    return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
}

function startOTPTimer() {
    otpTimeLeft = OTP_EXPIRY_SECONDS;
    
    if (timerInterval) {
        clearInterval(timerInterval);
    }
    
    document.getElementById('timer').textContent = formatTimer(otpTimeLeft);
    document.getElementById('timer').style.color = '#667eea';
    
    timerInterval = setInterval(() => {
        otpTimeLeft--;
        
        if (otpTimeLeft <= 0) {
            clearInterval(timerInterval);
            document.getElementById('timer').textContent = '00:00';
            document.getElementById('timer').style.color = '#f44336';
            document.getElementById('otp').disabled = true;
            showMessage('error2', 'OTP sudah kadaluarsa. Silakan minta OTP baru.', 'error');
            document.getElementById('resendBtn').disabled = false;
        } else {
            document.getElementById('timer').textContent = formatTimer(otpTimeLeft);
            
            // Change color when less than 1 minute
            if (otpTimeLeft < 60) {
                document.getElementById('timer').style.color = '#f44336';
            }
        }
    }, 1000);
}

function stopOTPTimer() {
    if (timerInterval) {
        clearInterval(timerInterval);
        timerInterval = null;
    }
}

function togglePassword(inputId, element) {
    const passwordInput = document.getElementById(inputId);
    const icon = element.querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    } else {
        passwordInput.type = 'password';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    }
}

function redirectToLogin() {
    window.location.href = 'http://localhost:8080/login';
}

function backToStep1() {
    stopOTPTimer();
    document.getElementById('form-step1').reset();
    document.getElementById('form-step2').reset();
    document.getElementById('form-step3').reset();
    goToStep(1);
}

// ============================================
// STEP 1: REQUEST OTP
// ============================================

document.getElementById('form-step1').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const nama = document.getElementById('nama').value.trim();
    const noHp = document.getElementById('noHp').value.trim();
    
    // Validation
    if (!nama) {
        showMessage('error1', 'Nama harus diisi', 'error');
        return;
    }
    
    if (nama.length < 3) {
        showMessage('error1', 'Nama minimal 3 karakter', 'error');
        return;
    }
    
    if (!noHp) {
        showMessage('error1', 'Nomor WhatsApp harus diisi', 'error');
        return;
    }
    
    if (noHp.replace(/\D/g, '').length < 10) {
        showMessage('error1', 'Nomor WhatsApp tidak valid', 'error');
        return;
    }
    
    const formattedPhone = formatPhoneNumber(noHp);
    otpData.nama = nama;
    otpData.noHp = formattedPhone;
    
    const button = document.getElementById('form-step1').querySelector('button');
    const btnText = document.getElementById('btnText1');
    button.disabled = true;
    btnText.innerHTML = '<span class="loading"></span> Mengirim...';
    
    try {
        hideAllMessages();
        
        console.log('📤 Sending OTP request...');
        console.log('Nama:', nama);
        console.log('No HP:', formattedPhone);
        
        const response = await fetch(`${API_BASE_URL}/request-otp`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                nama: nama,
                noHp: formattedPhone
            })
        });
        
        const data = await response.json();
        console.log('📥 Response:', data);
        
        if (data.success) {
            if (data.testOTP) {
                showMessage('info1', `✓ OTP untuk testing: ${data.testOTP}`, 'info');
            } else {
                showMessage('success1', '✓ ' + data.message, 'success');
            }
            
            setTimeout(() => {
                goToStep(2);
                startOTPTimer();
            }, 1500);
        } else {
            showMessage('error1', data.message || 'Gagal mengirim OTP', 'error');
        }
    } catch (error) {
        console.error('❌ Error:', error);
        showMessage('error1', 'Terjadi kesalahan: ' + error.message, 'error');
    } finally {
        button.disabled = false;
        btnText.innerHTML = 'Minta Kode OTP';
    }
});

// ============================================
// STEP 2: VERIFY OTP
// ============================================

document.getElementById('form-step2').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const otp = document.getElementById('otp').value.trim();
    
    // Validation
    if (!otp) {
        showMessage('error2', 'Kode OTP harus diisi', 'error');
        return;
    }
    
    if (otp.length !== 6 || isNaN(otp)) {
        showMessage('error2', 'Kode OTP harus 6 digit angka', 'error');
        return;
    }
    
    if (otpTimeLeft <= 0) {
        showMessage('error2', 'OTP sudah kadaluarsa. Silakan minta OTP baru.', 'error');
        return;
    }
    
    otpData.otp = otp;
    
    const button = document.getElementById('form-step2').querySelector('button');
    const btnText = document.getElementById('btnText2');
    button.disabled = true;
    btnText.innerHTML = '<span class="loading"></span> Verifikasi...';
    
    try {
        hideAllMessages();
        
        console.log('📤 Verifying OTP...');
        console.log('No HP:', otpData.noHp);
        console.log('OTP:', otp);
        
        const response = await fetch(`${API_BASE_URL}/verify-otp`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                noHp: otpData.noHp,
                otp: otp
            })
        });
        
        const data = await response.json();
        console.log('📥 Response:', data);
        
        if (data.success) {
            stopOTPTimer();
            showMessage('success2', '✓ OTP berhasil diverifikasi', 'success');
            
            setTimeout(() => {
                goToStep(3);
            }, 1500);
        } else {
            showMessage('error2', data.message || 'OTP tidak valid', 'error');
        }
    } catch (error) {
        console.error('❌ Error:', error);
        showMessage('error2', 'Terjadi kesalahan: ' + error.message, 'error');
    } finally {
        button.disabled = false;
        btnText.innerHTML = 'Verifikasi OTP';
    }
});

// ============================================
// STEP 3: RESET PASSWORD
// ============================================

document.getElementById('form-step3').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    // Validation
    if (!newPassword) {
        showMessage('error3', 'Sandi baru harus diisi', 'error');
        return;
    }
    
    if (newPassword.length < 6) {
        showMessage('error3', 'Sandi minimal 6 karakter', 'error');
        return;
    }
    
    if (newPassword !== confirmPassword) {
        showMessage('error3', 'Sandi tidak cocok', 'error');
        return;
    }
    
    const button = document.getElementById('form-step3').querySelector('button');
    const btnText = document.getElementById('btnText3');
    button.disabled = true;
    btnText.innerHTML = '<span class="loading"></span> Menyimpan...';
    
    try {
        hideAllMessages();
        
        console.log('📤 Resetting password...');
        console.log('No HP:', otpData.noHp);
        
        const response = await fetch(`${API_BASE_URL}/reset-password`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                noHp: otpData.noHp,
                otp: otpData.otp,
                newPassword: newPassword,
                confirmPassword: confirmPassword
            })
        });
        
        const data = await response.json();
        console.log('📥 Response:', data);
        
        if (data.success) {
            console.log('✅ Password berhasil diubah!');
            goToStep('success');
        } else {
            showMessage('error3', data.message || 'Gagal mengubah sandi', 'error');
        }
    } catch (error) {
        console.error('❌ Error:', error);
        showMessage('error3', 'Terjadi kesalahan: ' + error.message, 'error');
    } finally {
        button.disabled = false;
        btnText.innerHTML = 'Ubah Sandi';
    }
});

// ============================================
// RESEND OTP
// ============================================

async function resendOTP() {
    const button = document.getElementById('resendBtn');
    button.disabled = true;
    button.textContent = 'Mengirim...';
    
    try {
        console.log('📤 Resending OTP...');
        
        const response = await fetch(`${API_BASE_URL}/request-otp`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                nama: otpData.nama,
                noHp: otpData.noHp
            })
        });
        
        const data = await response.json();
        console.log('📥 Response:', data);
        
        if (data.success) {
            showMessage('success2', '✓ OTP baru telah dikirim', 'success');
            document.getElementById('otp').value = '';
            document.getElementById('otp').disabled = false;
            startOTPTimer();
            
            if (data.testOTP) {
                console.log('🔢 Test OTP:', data.testOTP);
            }
        } else {
            showMessage('error2', data.message || 'Gagal mengirim ulang OTP', 'error');
            button.disabled = false;
        }
    } catch (error) {
        console.error('❌ Error:', error);
        showMessage('error2', 'Terjadi kesalahan: ' + error.message, 'error');
        button.disabled = false;
    } finally {
        button.textContent = 'Kirim Ulang OTP';
    }
}

// ============================================
// INPUT FORMATTERS
// ============================================

document.getElementById('otp').addEventListener('input', (e) => {
    e.target.value = e.target.value.replace(/[^0-9]/g, '');
    
    if (e.target.value.length === 6) {
        e.target.parentElement.style.boxShadow = '0 0 0 3px rgba(102, 126, 234, 0.2)';
    } else {
        e.target.parentElement.style.boxShadow = 'none';
    }
});

document.getElementById('noHp').addEventListener('input', (e) => {
    e.target.value = e.target.value.replace(/[^0-9]/g, '');
});

// ============================================
// INITIALIZATION
// ============================================

console.log('✅ Forgot Password Script Loaded');
console.log('📡 API URL:', API_BASE_URL);
console.log('🔗 Login URL: http://localhost:8080/login');