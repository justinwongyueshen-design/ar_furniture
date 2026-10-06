document.addEventListener('DOMContentLoaded', () => {
    const viewer = document.getElementById('ar-viewer');
    if (!viewer) return;

    // Handle AR status updates and recovery
    viewer.addEventListener('ar-status', (event) => {
        if (event.detail.status === 'failed') {
            console.warn('AR Session failed to start.');
        }
    });
});