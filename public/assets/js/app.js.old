document.addEventListener('DOMContentLoaded', function() {
    // Éléments du minuteur
    const timerDisplay = document.querySelector('.timer');
    const startButton = document.querySelector('.start-timer');
    const stopButton = document.querySelector('.stop-timer');
    const resetButton = document.querySelector('.reset-timer');

    if (!timerDisplay || !startButton || !stopButton || !resetButton) {
        return; // Si on n'est pas sur une page avec un minuteur, on sort
    }

    let timeLeft = startButton.dataset.time * 60; // Temps en secondes
    let timerId = null;

    // Fonction pour formater le temps
    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        return `${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;
    }

    // Fonction pour mettre à jour l'affichage
    function updateDisplay() {
        timerDisplay.textContent = formatTime(timeLeft);
    }

    // Fonction pour démarrer le minuteur
    function startTimer() {
        if (timerId === null) {
            startButton.disabled = true;
            stopButton.disabled = false;
            resetButton.disabled = false;

            timerId = setInterval(() => {
                timeLeft--;
                updateDisplay();

                if (timeLeft <= 0) {
                    clearInterval(timerId);
                    timerId = null;
                    // Jouer un son ou montrer une notification
                    alert('Temps écoulé !');
                    resetTimer();
                }
            }, 1000);
        }
    }

    // Fonction pour arrêter le minuteur
    function stopTimer() {
        if (timerId !== null) {
            clearInterval(timerId);
            timerId = null;
            startButton.disabled = false;
            stopButton.disabled = true;
        }
    }

    // Fonction pour réinitialiser le minuteur
    function resetTimer() {
        stopTimer();
        timeLeft = startButton.dataset.time * 60;
        updateDisplay();
        startButton.disabled = false;
        stopButton.disabled = true;
        resetButton.disabled = true;
    }

    // Initialisation de l'affichage
    updateDisplay();

    // Ajout des écouteurs d'événements
    startButton.addEventListener('click', startTimer);
    stopButton.addEventListener('click', stopTimer);
    resetButton.addEventListener('click', resetTimer);
});
