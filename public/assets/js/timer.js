class Timer {
    constructor() {
        this.initialized = false;
        this.timeLeft = 0;
        this.timerId = null;
        this.alarmInterval = null;
        this.audio = new Audio('/public/assets/sounds/alarms.mp3');
        this.audio.loop = true;
    }

    init() {
        if (this.initialized) return;

        // DOM elements - Utilisation des classes au lieu des IDs
        this.timerDisplay = document.querySelector('.timer-display');
        this.startButton = document.querySelector('.start-timer');
        this.stopButton = document.querySelector('.stop-timer');
        this.resetButton = document.querySelector('.reset-timer');

        console.log('Timer elements:', {
            display: this.timerDisplay,
            start: this.startButton,
            stop: this.stopButton,
            reset: this.resetButton
        });

        if (!this.timerDisplay || !this.startButton || !this.stopButton || !this.resetButton) {
            console.error('Some timer elements are missing');
            return;
        }

        // Bind event listeners
        this.startButton.addEventListener('click', () => {
            const minutes = parseInt(this.startButton.dataset.time || 0);
            console.log('Starting timer with minutes:', minutes);
            this.setTime(minutes);
            this.start();
        });
        this.stopButton.addEventListener('click', () => this.stop());
        this.resetButton.addEventListener('click', () => this.reset());

        this.initialized = true;
        console.log('Timer initialized successfully');
    }

    setTime(minutes) {
        if (!this.initialized) this.init();
        console.log('Setting time to minutes:', minutes);
        this.timeLeft = minutes * 60;
        this.updateDisplay();
    }

    start() {
        if (!this.initialized) this.init();
        console.log('Starting timer with timeLeft:', this.timeLeft);
        if (this.timerId === null && this.timeLeft > 0) {
            this.startButton.disabled = true;
            this.stopButton.disabled = false;
            this.resetButton.disabled = false;
            
            // Calculer l'heure de fin
            const endTime = new Date().getTime() + (this.timeLeft * 1000);
            
            this.timerId = setInterval(() => {
                const now = new Date().getTime();
                const remaining = Math.max(0, Math.ceil((endTime - now) / 1000));
                
                if (remaining !== this.timeLeft) {
                    this.timeLeft = remaining;
                    this.updateDisplay();
                    
                    if (this.timeLeft === 0) {
                        this.timerComplete();
                    }
                }
            }, 100);
        } else {
            console.log('Timer not started because:', {
                hasTimerId: this.timerId !== null,
                timeLeft: this.timeLeft
            });
        }
    }

    stop() {
        if (!this.initialized) return;
        if (this.timerId !== null) {
            clearInterval(this.timerId);
            this.timerId = null;
            this.startButton.disabled = false;
            this.stopButton.disabled = true;
            this.stopAlarm();
        }
    }

    reset() {
        if (!this.initialized) return;
        this.stop();
        this.timeLeft = 0;
        this.updateDisplay();
        this.stopAlarm();
        this.resetButton.disabled = true;
    }

    playAlarm() {
        try {
            this.audio.currentTime = 0; // Redémarre le son depuis le début
            this.audio.play().catch(error => {
                console.error('Erreur lors de la lecture du son:', error);
            });
        } catch (error) {
            console.error('Erreur lors de la lecture du son:', error);
        }
    }

    stopAlarm() {
        try {
            this.audio.pause();
            this.audio.currentTime = 0;
        } catch (error) {
            console.error('Erreur lors de l\'arrêt du son:', error);
        }
    }

    timerComplete() {
        console.log('Timer complete');
        this.stop();
        this.startButton.disabled = false;
        this.playAlarm();
    }

    updateDisplay() {
        if (!this.initialized) return;
        if (this.timerDisplay) {
            const minutes = Math.floor(this.timeLeft / 60);
            const seconds = this.timeLeft % 60;
            
            // Mise à jour des spans minutes et secondes
            const minutesSpan = this.timerDisplay.querySelector('.minutes');
            const secondsSpan = this.timerDisplay.querySelector('.seconds');
            
            if (minutesSpan && secondsSpan) {
                minutesSpan.textContent = String(minutes).padStart(2, '0');
                secondsSpan.textContent = String(seconds).padStart(2, '0');
            }
            
            document.title = this.timeLeft > 0 ? 
                `(${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}) MinutOeuf` : 
                'MinutOeuf';
        }
    }
}

// Créer une instance globale du timer
window.timer = new Timer();

// Initialiser le timer une fois que le DOM est complètement chargé
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM loaded, initializing timer');
    window.timer.init();
});
