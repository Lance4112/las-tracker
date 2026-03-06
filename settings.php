<link rel="stylesheet" href="theme.css">

<div class="settings-menu" style="position: relative; display: inline-block;">
    <button id="gear-btn" style="background:none; border:none; font-size:1.5rem; cursor:pointer;">⚙️</button>
    
    <div id="settings-content" class="settings-content" style="display:none; position:absolute; right:0; min-width:250px; z-index:1000; padding:20px; box-shadow: 0 8px 30px rgba(0,0,0,0.2);">
        <h3 style="margin-top:0;">Account & UI</h3>
        
        <div style="margin-bottom:15px;">
            <label style="display:block; font-size:0.8rem; margin-bottom:5px;">Theme Mode</label>
            <select id="theme-select" onchange="updateUI('theme', this.value)" style="width:100%; padding:5px;">
                <option value="light">☀️ Light Mode</option>
                <option value="dark">🌙 Dark Mode</option>
            </select>
        </div>

        <div style="margin-bottom:15px;">
            <label style="cursor:pointer;">
                <input type="checkbox" id="grad-toggle" onchange="updateUI('gradient', this.checked)"> Enable Gradient
            </label>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block; font-size:0.8rem; margin-bottom:5px;">Brightness</label>
            <input type="range" id="bright-slider" min="50" max="150" value="100" oninput="updateUI('brightness', this.value)" style="width:100%;">
        </div>

        <hr style="opacity:0.1;">

        <div style="display:flex; flex-direction:column; gap:10px;">
            <a href="profile.php" style="text-decoration:none; color:inherit;">👤 User Profile</a>
            <a href="logout.php" style="text-decoration:none; color:#e74c3c; font-weight:bold;">🚪 Logout System</a>
        </div>
    </div>
</div>

<script>
// Toggle Menu Visibility
document.getElementById('gear-btn').onclick = function() {
    const content = document.getElementById('settings-content');
    content.style.display = content.style.display === 'none' ? 'block' : 'none';
};

// Global UI Controller
function updateUI(type, value) {
    if (type === 'theme') {
        localStorage.setItem('global-theme', value);
    } else if (type === 'gradient') {
        localStorage.setItem('global-grad', value);
    } else if (type === 'brightness') {
        localStorage.setItem('global-bright', value);
    }
    applyGlobalSettings();
}

function applyGlobalSettings() {
    const theme = localStorage.getItem('global-theme') || 'light';
    const grad = localStorage.getItem('global-grad') === 'true';
    const bright = localStorage.getItem('global-bright') || 100;

    // Apply Dark Mode
    if (theme === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
    } else {
        document.documentElement.removeAttribute('data-theme');
    }

    // Apply Gradient
    document.body.classList.toggle('gradient-active', grad);

    // Apply Brightness
    document.body.style.filter = `brightness(${bright}%)`;

    // Sync elements in the settings menu if they exist on this page
    if(document.getElementById('theme-select')) {
        document.getElementById('theme-select').value = theme;
        document.getElementById('grad-toggle').checked = grad;
        document.getElementById('bright-range')?.setAttribute('value', bright);
    }
}

// Run immediately on page load
applyGlobalSettings();

// Close menu if clicked outside
window.onclick = function(event) {
    if (!event.target.matches('#gear-btn') && !event.target.closest('#settings-content')) {
        document.getElementById('settings-content').style.display = 'none';
    }
}
</script>