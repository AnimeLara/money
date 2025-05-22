<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aviator Game (1.1x-1.9x Range)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: white;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .game-container {
            width: 100%;
            max-width: 500px;
            background-color: #1e1e1e;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }
        
        .game-board {
            position: relative;
            height: 300px;
            margin-bottom: 20px;
            background-color: #2a2a2a;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .multiplier-display {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 24px;
            font-weight: bold;
            color: #4CAF50;
        }
        
        .airplane {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 40px;
            transition: bottom 0.1s linear;
        }
        
        .flight-path {
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 2px;
            background-color: rgba(255, 255, 255, 0.2);
            height: 0;
        }
        
        .bet-controls {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .bet-amount {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        input {
            padding: 10px;
            border-radius: 5px;
            border: none;
            background-color: #2a2a2a;
            color: white;
            width: 100px;
        }
        
        button {
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .place-bet {
            background-color: #4CAF50;
            color: white;
        }
        
        .place-bet:hover {
            background-color: #45a049;
        }
        
        .cash-out {
            background-color: #2196F3;
            color: white;
            display: none;
        }
        
        .cash-out:hover {
            background-color: #0b7dda;
        }
        
        .history {
            display: flex;
            gap: 5px;
            margin-top: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .history-item {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 12px;
        }
        
        .win {
            background-color: #4CAF50;
        }
        
        .lose {
            background-color: #f44336;
        }
        
        .balance {
            font-size: 18px;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        .game-message {
            margin-top: 10px;
            font-weight: bold;
            min-height: 20px;
            color: #FFC107;
            text-align: center;
        }
        
        .crash-point {
            position: absolute;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: red;
            display: none;
        }
    </style>
</head>
<body>
    <h1>Aviator Game (1.1x-1.9x Range)</h1>
    
    <div class="game-container">
        <div class="balance">Balance: $<span id="balance">1000</span></div>
        
        <div class="game-board" id="gameBoard">
            <div class="multiplier-display" id="multiplier">1.00x</div>
            <div class="crash-point" id="crashPoint"></div>
            <div class="airplane" id="airplane">✈️</div>
            <div class="flight-path" id="flightPath"></div>
        </div>
        
        <div class="game-message" id="gameMessage"></div>
        
        <div class="bet-controls">
            <div class="bet-amount">
                <label for="betAmount">Bet Amount:</label>
                <input type="number" id="betAmount" min="1" value="10">
            </div>
            
            <button class="place-bet" id="placeBet">PLACE BET</button>
            <button class="cash-out" id="cashOut">CASH OUT (1.00x)</button>
        </div>
        
        <div class="history" id="history"></div>
    </div>

    <script>
        // Game variables
        let balance = 1000;
        let currentBet = 0;
        let currentMultiplier = 1.0;
        let gameRunning = false;
        let cashOutMultiplier = 0;
        let gameInterval;
        let crashPoint = 0;
        let gamesPlayed = 0;
        
        // DOM elements
        const balanceElement = document.getElementById('balance');
        const betAmountElement = document.getElementById('betAmount');
        const placeBetButton = document.getElementById('placeBet');
        const cashOutButton = document.getElementById('cashOut');
        const multiplierElement = document.getElementById('multiplier');
        const gameBoard = document.getElementById('gameBoard');
        const airplane = document.getElementById('airplane');
        const flightPath = document.getElementById('flightPath');
        const gameMessage = document.getElementById('gameMessage');
        const historyElement = document.getElementById('history');
        const crashPointElement = document.getElementById('crashPoint');
        
        // Event listeners
        placeBetButton.addEventListener('click', startGame);
        cashOutButton.addEventListener('click', cashOut);
        
        // Start the game
        function startGame() {
            const betAmount = parseInt(betAmountElement.value);
            
            if (isNaN(betAmount) || betAmount < 1) {
                showMessage('Please enter a valid bet amount');
                return;
            }
            
            if (betAmount > balance) {
                showMessage('Not enough balance');
                return;
            }
            
            if (gameRunning) return;
            
            // Deduct bet amount from balance
            balance -= betAmount;
            currentBet = betAmount;
            updateBalance();
            
            // Reset game state
            currentMultiplier = 1.0;
            cashOutMultiplier = 0;
            gameRunning = true;
            gamesPlayed++;
            
            // Show/hide buttons
            placeBetButton.style.display = 'none';
            cashOutButton.style.display = 'block';
            cashOutButton.textContent = `CASH OUT (1.00x)`;
            
            // Reset visual elements
            multiplierElement.textContent = '1.00x';
            multiplierElement.style.color = '#4CAF50';
            airplane.style.bottom = '0px';
            flightPath.style.height = '0px';
            crashPointElement.style.display = 'none';
            showMessage('Game started! Click CASH OUT to secure your winnings');
            
            // Generate random crash point (98% between 1.1x-1.9x, 2% between 1.9x-3x)
            if (gamesPlayed % 5 !== 0) { // 49 out of 50 games (98%)
                crashPoint = (1.5 + Math.random() * 0.9).toFixed(2); // 1.1-1.9
            } else { // 1 in 50 games (2%)
                crashPoint = (2.8 + Math.random() * 1.1).toFixed(2); // 1.9-3.0
                showMessage("RARE HIGH MULTIPLIER ROUND!");
            }
            
            // Position crash point on game board
            const crashPosition = Math.min((crashPoint / 4) * 100, 100); // Scale to 3x max
            crashPointElement.style.bottom = `${crashPosition}%`;
            
            // Start game loop
            gameInterval = setInterval(updateGame, 100);
        }
        
        // Update game state
        function updateGame() {
            // Increase multiplier
            currentMultiplier += 0.05;
            
            // Update display
            const displayMultiplier = Math.min(currentMultiplier, crashPoint);
            multiplierElement.textContent = displayMultiplier.toFixed(2) + 'x';
            cashOutButton.textContent = `CASH OUT (${displayMultiplier.toFixed(2)}x)`;
            
            // Update airplane position
            const boardHeight = gameBoard.clientHeight;
            const progress = Math.min(displayMultiplier / 3, 1); // Cap at 3x for display
            const newBottom = progress * boardHeight;
            airplane.style.bottom = `${newBottom}px`;
            flightPath.style.height = `${newBottom}px`;
            
            // Change color as multiplier increases
            if (displayMultiplier > 1.3) {
                multiplierElement.style.color = '#FFC107'; // Yellow
            }
            if (displayMultiplier > 1.6) {
                multiplierElement.style.color = '#FF5722'; // Orange
            }
            if (displayMultiplier > 1.9) {
                multiplierElement.style.color = '#F44336'; // Red
            }
            
            // Check for crash
            if (currentMultiplier >= crashPoint) {
                crashPointElement.style.display = 'block';
                endGame(false);
            }
        }
        
        // Cash out
        function cashOut() {
            if (!gameRunning) return;
            
            cashOutMultiplier = currentMultiplier;
            endGame(true);
        }
        
        // End the game
        function endGame(manualCashOut) {
            clearInterval(gameInterval);
            gameRunning = false;
            
            // Calculate winnings
            const multiplierUsed = manualCashOut ? cashOutMultiplier : currentMultiplier;
            const winnings = manualCashOut ? Math.floor(currentBet * multiplierUsed) : 0;
            
            // Update balance
            balance += winnings;
            updateBalance();
            
            // Show result
            if (manualCashOut) {
                showMessage(`Cashed out at ${multiplierUsed.toFixed(2)}x! You won $${winnings}`);
                addToHistory(multiplierUsed.toFixed(2), true);
            } else {
                showMessage(`Crashed at ${crashPoint}x! You lost $${currentBet}`);
                addToHistory(crashPoint, false);
            }
            
            // Reset UI
            placeBetButton.style.display = 'block';
            cashOutButton.style.display = 'none';
        }
        
        // Update balance display
        function updateBalance() {
            balanceElement.textContent = balance;
        }
        
        // Show message
        function showMessage(message) {
            gameMessage.textContent = message;
        }
        
        // Add result to history
        function addToHistory(multiplier, won) {
            const historyItem = document.createElement('div');
            historyItem.className = `history-item ${won ? 'win' : 'lose'}`;
            historyItem.textContent = multiplier;
            historyElement.insertBefore(historyItem, historyElement.firstChild);
            
            // Limit history to 10 items
            if (historyElement.children.length > 10) {
                historyElement.removeChild(historyElement.lastChild);
            }
        }
    </script>
</body>
</html>