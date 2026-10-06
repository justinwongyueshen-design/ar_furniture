function generateQRCode(url) {
    const container = document.getElementById('qrcode');
    if (!container) return;
    container.innerHTML = "";
    new QRCode(container, {
        text: url,
        width: 140,
        height: 140,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });
}