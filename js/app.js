document.addEventListener('DOMContentLoaded', () => {
    const viewer = document.getElementById('ar-viewer');

    // Handle AR status updates and recovery
    if (viewer) {
        viewer.addEventListener('ar-status', (event) => {
            if (event.detail.status === 'failed') {
                console.warn('AR Session failed to start.');
            }
        });
    }

    const camera = document.getElementById('room-camera');
    if (!camera) return;

    const prompt = document.getElementById('camera-prompt');
    const liveUi = document.getElementById('camera-live-ui');
    const cameraMessage = document.getElementById('camera-message');
    const liveMessage = document.getElementById('camera-live-message');
    const startButton = document.getElementById('start-camera');
    const switchButton = document.getElementById('switch-camera');
    const stopButton = document.getElementById('stop-camera');
    let stream = null;
    let facingMode = 'environment';

    const stopStream = () => {
        if (stream) {
            stream.getTracks().forEach((track) => track.stop());
            stream = null;
        }
        camera.srcObject = null;
    };

    const showError = (message) => {
        prompt.hidden = false;
        liveUi.hidden = true;
        cameraMessage.textContent = message;
    };

    const startCamera = async () => {
        if (!window.isSecureContext || !navigator.mediaDevices?.getUserMedia) {
            showError('Camera access is blocked on this address. Open the site over HTTPS, then allow camera access.');
            return;
        }

        startButton.disabled = true;
        cameraMessage.textContent = 'Waiting for camera permission...';

        try {
            stream = await navigator.mediaDevices.getUserMedia({
                audio: false,
                video: { facingMode: { ideal: facingMode } }
            });
            camera.srcObject = stream;
            await camera.play();
            prompt.hidden = true;
            liveUi.hidden = false;
            liveMessage.textContent = 'Point your phone around the room to preview the space.';
        } catch (error) {
            stopStream();
            if (error.name === 'NotAllowedError' || error.name === 'SecurityError') {
                showError('Camera permission was blocked. Allow camera access in your browser settings and try again.');
            } else if (error.name === 'NotFoundError') {
                showError('No camera was found on this device.');
            } else {
                showError('The camera could not start. Check browser permission and try again.');
            }
        } finally {
            startButton.disabled = false;
        }
    };

    startButton.addEventListener('click', startCamera);
    switchButton.addEventListener('click', async () => {
        facingMode = facingMode === 'environment' ? 'user' : 'environment';
        stopStream();
        await startCamera();
    });
    stopButton.addEventListener('click', () => {
        stopStream();
        liveUi.hidden = true;
        prompt.hidden = false;
        cameraMessage.textContent = 'Camera stopped. Start it again whenever you are ready.';
    });
    window.addEventListener('pagehide', stopStream);
});