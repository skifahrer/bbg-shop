function updateAuthUI(user) {
    const authSection = document.getElementById('auth-section');
    if (authSection && !authSection.querySelector('.fs-2')) {
        authSection.innerHTML = `
            <li class="nav-item active mx-2">
                    <a class="nav-link" href="">Hello ${user.name}!</a>
            </li>
            <li class="nav-item active mx-2">
                <a class="nav-link" href="" onclick="handleLogout(event)">Logout</a>
            </li>
        `;
    }
}
