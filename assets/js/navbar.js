const toggleSidebar = document.getElementById('toggleSidebar');
const sidebar = document.getElementById('sidebar');
const mainContent = document.getElementById('mainContent');

// Toggle sidebar visibility
toggleSidebar.addEventListener('click', () => {
    sidebar.classList.toggle('closed');
    mainContent.classList.toggle('full');
});
