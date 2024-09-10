async function checkAuthentication() {
    const response = await fetch('../../check_session.php');
    const data = await response.json();

    if (!data.success) {
        window.location.href = '../../index.html';
    }
}
window.onload = checkAuthentication;
