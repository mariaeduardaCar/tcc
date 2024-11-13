

function animate() {
    requestAnimationFrame(animate);
    ctx.fillStyle = 'rgba(0, 0, 0, 0.03)'; // 透明度を0.1に調整
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    waves.forEach((wave, index) => {
        ctx.beginPath();
        ctx.moveTo(0, wave.y);
        for (let i = 0; i < canvas.width; i++) {
            const yOffset = Math.sin(i * wave.length + wave.phase) * wave.amplitude * Math.sin(wave.phase);
            ctx.lineTo(i, wave.y + yOffset);
        }
        ctx.strokeStyle = `hsl(${index / 2 + 170}, 100%, 50%)`;
        ctx.stroke();
        wave.phase += wave.frequency;
    });
}

animate();

window.addEventListener('resize', () => {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    waves.forEach((wave) => {
        wave.y = (canvas.height / 2) - 100 + Math.random() * 200; 
    });
});