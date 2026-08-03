<section class="auth-container">
    <div class="auth-wrapper">
        <!-- Role Selection -->
        <div id="roleSelectionView" class="auth-view active">
            <div class="auth-card">
                <h1 class="auth-title">Welcome to Food Hub</h1>
                <p class="auth-subtitle">Select your account type</p>
                
                <div class="role-selection">
                    <button class="role-card" onclick="selectRole('customer')">
                        <div class="role-icon">🛵</div>
                        <h3>Customer</h3>
                        <p>Order delicious food from restaurants</p>
                    </button>
                    
                    <button class="role-card" onclick="selectRole('restaurant_manager')">
                        <div class="role-icon">🏪</div>
                        <h3>Restaurant Manager</h3>
                        <p>Menu, orders, reviews, analytics</p>
                    </button>
                    
                    <button class="role-card" onclick="selectRole('delivery_man')">
                        <div class="role-icon">🚗</div>
                        <h3>Delivery Agent</h3>
                        <p>Accept deliveries, update status, track earnings</p>
                    </button>
                    
                    <button class="role-card" onclick="selectRole('admin')">
                        <div class="role-icon">⚙️</div>
                        <h3>Platform Admin</h3>
                        <p>User management, platform oversight, reports</p>
                    </button>
                </div>
            </div>
        </div>

        <!-- Auth Forms -->
        <div id="authView" class="auth-view">
            <div class="auth-card">
                <div class="auth-header">
                    <button class="back-btn" onclick="backToRoles()">← Back</button>
                    <h2 id="authTitle" class="auth-form-title">Login</h2>
                </div>

                <!-- Error Message -->
                <div id="errorMessage" class="alert alert-error" style="display: none;"></div>

                <!-- Tab Navigation -->
                <div class="auth-tabs">
                    <button type="button" class="tab-btn active" onclick="switchTab('login', event)">Login</button>
                    <button type="button" class="tab-btn" onclick="switchTab('register', event)">Register</button>
                </div>

                <!-- Login Form -->
                <form id="loginForm" class="tab-content active" onsubmit="handleLogin(event)">
                    <div class="form-group">
                        <label for="loginEmail">Email Address</label>
                        <input type="email" id="loginEmail" name="email" placeholder="Enter your email" required>
                    </div>

                    <div class="form-group">
                        <label for="loginPassword">Password</label>
                        <input type="password" id="loginPassword" name="password" placeholder="Enter your password" required>
                    </div>

                    <button type="submit" class="btn-primary" id="loginBtn">Login</button>
                    <p class="form-hint">Don't have an account? <a href="#" onclick="switchTab('register'); return false;">Register here</a></p>
                </form>

                <!-- Register Form -->
                <form id="registerForm" class="tab-content" onsubmit="handleRegister(event)">
                    <div class="form-group">
                        <label for="regName">Full Name</label>
                        <input type="text" id="regName" name="name" placeholder="Enter your name" required>
                    </div>

                    <div class="form-group">
                        <label for="regEmail">Email Address</label>
                        <input type="email" id="regEmail" name="email" placeholder="Enter your email" required>
                    </div>

                    <div class="form-group">
                        <label for="regPhone">Phone Number (Optional)</label>
                        <input type="tel" id="regPhone" name="phone" placeholder="Enter your phone number">
                    </div>

                    <div class="form-group">
                        <label for="regPassword">Password</label>
                        <input type="password" id="regPassword" name="password" placeholder="Create a password (min 6 characters)" required>
                    </div>

                    <div class="form-group">
                        <label for="regConfirm">Confirm Password</label>
                        <input type="password" id="regConfirm" name="confirm_password" placeholder="Confirm your password" required>
                    </div>

                    <button type="submit" class="btn-primary" id="registerBtn">Create Account</button>
                    <p class="form-hint">Already have an account? <a href="#" onclick="switchTab('login'); return false;">Login here</a></p>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
let selectedRole = null;

function selectRole(role) {
    selectedRole = role;
    const roleNames = {
        'customer': 'Customer',
        'restaurant_manager': 'Restaurant Manager',
        'delivery_man': 'Delivery Agent',
        'admin': 'Platform Admin'
    };
    
    document.getElementById('authTitle').textContent = 'Login as ' + roleNames[role];
    document.getElementById('roleSelectionView').classList.remove('active');
    document.getElementById('authView').classList.add('active');
    switchTab('login');
    clearErrors();
}

function backToRoles() {
    document.getElementById('authView').classList.remove('active');
    document.getElementById('roleSelectionView').classList.add('active');
    clearForm('loginForm');
    clearForm('registerForm');
    clearErrors();
}

function switchTab(tab, event) {
    // Update tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    if (event && event.target) {
        event.target.classList.add('active');
    }
    
    // Update forms
    document.querySelectorAll('.tab-content').forEach(form => form.classList.remove('active'));
    
    if (tab === 'login') {
        document.getElementById('loginForm').classList.add('active');
        document.getElementById('authTitle').textContent = document.getElementById('authTitle').textContent.replace('Register', 'Login').replace('Sign up', 'Login');
    } else {
        document.getElementById('registerForm').classList.add('active');
        document.getElementById('authTitle').textContent = document.getElementById('authTitle').textContent.replace('Login', 'Register').replace('Sign up', 'Register');
    }
    
    clearErrors();
}

function clearForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.reset();
    }
}

function clearErrors() {
    const errorDiv = document.getElementById('errorMessage');
    if (errorDiv) {
        errorDiv.textContent = '';
        errorDiv.style.display = 'none';
    }
}

function handleLogin(event) {
    event.preventDefault();
    
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    const btn = document.getElementById('loginBtn');
    const originalText = btn.textContent;
    
    btn.disabled = true;
    btn.textContent = 'Logging in...';
    
    if (!selectedRole) {
        showError('Please select an account type before signing in.');
        btn.disabled = false;
        btn.textContent = originalText;
        return;
    }

    const data = {
        email: email,
        password: password,
        role: selectedRole
    };
    
    fetch('auth_ajax.php?action=login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            // Redirect based on role
            const redirects = {
                'admin': 'index.php?route=admin',
                'restaurant_manager': 'index.php?route=restaurant',
                'delivery_man': 'index.php?route=delivery',
                'customer': 'index.php?route=home'
            };
            window.location.href = redirects[result.role] || 'index.php?route=home';
        } else {
            showError(result.message);
            btn.disabled = false;
            btn.textContent = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('An error occurred. Please try again.');
        btn.disabled = false;
        btn.textContent = originalText;
    });
}

function handleRegister(event) {
    event.preventDefault();
    
    const name = document.getElementById('regName').value;
    const email = document.getElementById('regEmail').value;
    const phone = document.getElementById('regPhone').value;
    const password = document.getElementById('regPassword').value;
    const confirm = document.getElementById('regConfirm').value;
    const btn = document.getElementById('registerBtn');
    const originalText = btn.textContent;
    
    btn.disabled = true;
    btn.textContent = 'Creating account...';
    
    if (!selectedRole) {
        showError('Please select an account type before registering.');
        btn.disabled = false;
        btn.textContent = originalText;
        return;
    }

    const data = {
        name: name,
        email: email,
        phone: phone,
        password: password,
        confirm_password: confirm,
        role: selectedRole
    };
    
    fetch('auth_ajax.php?action=register', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            // Redirect based on role
            const redirects = {
                'admin': 'index.php?route=admin',
                'restaurant_manager': 'index.php?route=restaurant',
                'delivery_man': 'index.php?route=delivery',
                'customer': 'index.php?route=home'
            };
            window.location.href = redirects[result.role] || 'index.php?route=home';
        } else {
            showError(result.message);
            btn.disabled = false;
            btn.textContent = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('An error occurred. Please try again.');
        btn.disabled = false;
        btn.textContent = originalText;
    });
}

function showError(message) {
    const errorDiv = document.getElementById('errorMessage');
    errorDiv.textContent = message;
    errorDiv.style.display = 'block';
    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}
</script>
