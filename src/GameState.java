

public class GameState {

    public int score;
    public int hp;
    public int time;

    public int level;
    public int targetScore;
    public final int MAX_LEVEL = 3;
    private boolean gameWon;
    public int maxHp;

    public int scoreMultiplierTimer;
    public final int MULTIPLIER_DURATION_TICKS = 60 * 10;

    public GameState() {
        this.score = 0;
        this.hp = 5;
        this.gameWon = false;
        this.scoreMultiplierTimer = 0;
        setupLevel(1);
    }

    private void setupLevel(int newLevel) {
        this.level = newLevel;

        if (newLevel == 1) {
            this.time = 90;
            this.targetScore = 500;
            this.maxHp = 5;
        } else if (newLevel == 2) {
            this.time = 90;
            this.targetScore = 2000;
            this.maxHp = 7;
        } else if (newLevel == 3) {
            this.time = 90;
            this.targetScore = 4000;
            this.maxHp = 10;
        }
    }


    public void addScore(int amount) {
        if (this.scoreMultiplierTimer > 0 && amount > 0) {
            this.score += (amount * 2);
        } else {
            this.score += amount;
        }
        if (this.score < 0) this.score = 0;
    }
    public void addHp(int amount) {
        if (amount > 0 && this.hp >= this.maxHp) return;
        this.hp += amount;
        if (this.hp > this.maxHp) this.hp = this.maxHp;
    }
    public void addTime(int amount) {
        this.time += amount;
    }
    public void activateScoreMultiplier() {
        this.scoreMultiplierTimer = MULTIPLIER_DURATION_TICKS;
    }

    // --- State Checking Methods ---
    public boolean isGameOver() {
        return (this.hp <= 0 || this.time <= 0) && !this.gameWon;
    }

    public boolean didLevelUp() {
        return !this.gameWon && this.score >= this.targetScore;
    }

    public boolean isGameWon() {
        return this.gameWon;
    }

    public void advanceToNextLevel() {
        if (this.level < MAX_LEVEL) {
            setupLevel(this.level + 1);
        }
        else if (this.level == MAX_LEVEL && this.score >= this.targetScore) {
             this.gameWon = true;
        }
    }
}