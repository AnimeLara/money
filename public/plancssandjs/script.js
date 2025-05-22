 const multiplierDisplay = document.getElementById('multiplier');
  const status = document.getElementById('status');
  const progress = document.getElementById('progress');
  const plane = document.getElementById('plane');
  const placeBetBtn = document.getElementById('placeBetBtn');
  const cashOutBtn = document.getElementById('cashOutBtn');

  let multiplier = 1.0;
  let playing = true;
  let crashPoint = 0;

  function generateCrashPoint() {
    const rand = Math.random();
    if (rand < 0.4) {
      // 40% chance crash between 1.1 and 1.9
      return Math.random() * (1.9 - 1.1) + 1.1;
    } else {
      // 60% chance crash between 2.0 and 5.0
      return Math.random() * (5.0 - 2.0) + 2.0;
    }
  }

  function resetGame() {
    multiplier = 1.00;
    crashPoint = generateCrashPoint();
    playing = true;
    multiplierDisplay.style.color = '#70f570';
    multiplierDisplay.textContent = multiplier.toFixed(2) + 'x';
    status.textContent = '';
    placeBetBtn.disabled = true;
    cashOutBtn.disabled = false;
    plane.style.transform = 'translate(0px, 0px) rotate(0deg)';
    progress.style.width = '0%';
  }

  function updatePlanePosition() {
    // horizontal movement: progress from 0% to 100% linearly with multiplier
    // maximum multiplier considered for full width is 5x
    const maxMultiplier = 5.0;
    let progressPercent = Math.min((multiplier / maxMultiplier) * 100, 100);
    progress.style.width = progressPercent + '%';

    // vertical movement: plane goes slightly up and down in sine wave as multiplier increases
    // amplitude = 20px, frequency = 2*pi per 5 multiplier
    let verticalOffset = 20 * Math.sin((multiplier / maxMultiplier) * 2 * Math.PI);
    // rotate slightly based on vertical movement for effect
    let rotation = verticalOffset;

    plane.style.transform = `translateX(${progressPercent}%) translateY(${verticalOffset}px) rotate(${rotation}deg)`;
  }

  function gameLoop() {
    if (!playing) return;

    multiplier += 0.01;

    multiplierDisplay.textContent = multiplier.toFixed(2) + 'x';

    updatePlanePosition();

    if (multiplier >= crashPoint) {
      // game crashed
      playing = false;
      multiplierDisplay.style.color = '#ff4c4c';
      status.textContent = `Crash! at ${crashPoint.toFixed(2)}x`;
      cashOutBtn.disabled = true;
      placeBetBtn.disabled = false;
    } 

    requestAnimationFrame(gameLoop);
  }

  cashOutBtn.addEventListener('click', () => {
    if (playing) {
      playing = false;
      multiplierDisplay.style.color = '#ffd700'; // gold color for cashed out
      status.textContent = `Cashed Out at ${multiplier.toFixed(2)}x`;
      cashOutBtn.disabled = true;
      placeBetBtn.disabled = false;
    }
  });

  placeBetBtn.addEventListener('click', () => {
    resetGame();
    gameLoop();
  });

  // Start game on load
  resetGame();
  gameLoop();