// Save this as: src/GamePanel.java

import javax.swing.JPanel;
import javax.swing.Timer;
import java.awt.Color;
import java.awt.Dimension;
import java.awt.Graphics;
import java.awt.Font;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.KeyAdapter;
import java.awt.event.KeyEvent;
import java.util.ArrayList;
import java.util.Iterator;
import java.util.Random;
import javax.sound.sampled.Clip;
import java.awt.image.BufferedImage;
import java.awt.Graphics2D;

public class GamePanel extends JPanel implements ActionListener {

    public static final int PANEL_WIDTH = 800;
    public static final int PANEL_HEIGHT = 600;

    private GameState gameState;
    private Basket basket;
    private ArrayList<FallingObject> fallingObjects;
    private Random random;

    private Timer gameLoopTimer;
    private final int GAME_TICK_MS = 16;
    private int gameSecondCounter;
    private final int TICKS_PER_SECOND = 1000 / GAME_TICK_MS;

    private int spawnTimer;
    private int spawnDelay;

    private boolean movingLeft = false;
    private boolean movingRight = false;

    private int stunTimer;
    public final int STUN_DURATION_TICKS = 60 * 1;

    public GamePanel() {
        this.setPreferredSize(new Dimension(PANEL_WIDTH, PANEL_HEIGHT));
        this.setFocusable(true);

        this.gameState = new GameState();
        this.basket = new Basket(350, 500, 100, 20, PANEL_WIDTH);
        this.fallingObjects = new ArrayList<>();
        this.random = new Random();
        this.spawnDelay = 60;
        this.spawnTimer = 0;
        this.gameSecondCounter = 0;
        this.stunTimer = 0;

        this.addKeyListener(new GameKeyAdapter());
        this.gameLoopTimer = new Timer(GAME_TICK_MS, this);
        this.gameLoopTimer.start();

        if (GameLoader.backgroundMusic != null) {
            GameLoader.backgroundMusic.loop(Clip.LOOP_CONTINUOUSLY);
        }
    }

    @Override
    public void actionPerformed(ActionEvent e) {
        updateGame();
        repaint();
    }

    private void updateGame() {
        if (gameState.isGameOver()) {
            if (gameLoopTimer.isRunning()) {
                gameLoopTimer.stop();
                if (GameLoader.backgroundMusic != null) GameLoader.backgroundMusic.stop();
                playSound(GameLoader.gameOverSound);
            }
            return;
        }
        if (gameState.isGameWon()) {
            if (gameLoopTimer.isRunning()) {
                gameLoopTimer.stop();
                if (GameLoader.backgroundMusic != null) GameLoader.backgroundMusic.stop();
                playSound(GameLoader.levelUpSound);
            }
            return;
        }

        if (stunTimer > 0) stunTimer--;
        if (gameState.scoreMultiplierTimer > 0) gameState.scoreMultiplierTimer--;

        if (stunTimer <= 0) {
            if (movingLeft) basket.moveLeft();
            if (movingRight) basket.moveRight();
        }

        spawnTimer++;
        if (spawnTimer >= spawnDelay) {
            spawnTimer = 0;
            spawnNewObject();
        }

        Iterator<FallingObject> iterator = fallingObjects.iterator();
        while (iterator.hasNext()) {
            FallingObject obj = iterator.next();
            obj.update();
            if (obj.isOffScreen(PANEL_HEIGHT)) {
                iterator.remove();
                continue;
            }
            if (basket.getBounds().intersects(obj.getBounds())) {
                obj.applyEffect(gameState);
                if (obj instanceof Anvil || obj instanceof Bomb || obj instanceof Poison || obj instanceof RottenFruit || obj instanceof BrokenClock) {
                    playSound(GameLoader.bombSound);
                } else {
                    playSound(GameLoader.collectSound);
                }
                if (obj instanceof Anvil) {
                    this.stunTimer = STUN_DURATION_TICKS;
                }
                iterator.remove();
            }
        }

        gameSecondCounter++;
        if (gameSecondCounter >= TICKS_PER_SECOND) {
            gameSecondCounter = 0;
            gameState.addTime(-1);
        }

        if (gameState.didLevelUp()) {
            gameState.advanceToNextLevel();

            if (!gameState.isGameWon()) {
                 fallingObjects.clear();
                 playSound(GameLoader.levelUpSound);

                 if (gameState.level == 2) spawnDelay = 45;
                 else if (gameState.level == 3) spawnDelay = 30;
            }
        }
    }

    private void spawnNewObject() {
        int x = random.nextInt(PANEL_WIDTH - 60) + 30;
        int y = -30;
        int speed = random.nextInt(3) + 2;
        int level = gameState.level;
        if (level == 2) speed += 1;
        else if (level == 3) speed += 2;
        int objectType = random.nextInt(100);
        FallingObject newObject = null;
// --- START: PASTE NEW CODE HERE ---
        switch (level) {
            case 1:
                // Level 1: No change
                if (objectType < 60) newObject = new Fruit(x, y, speed);
                else if (objectType < 90) newObject = new Bomb(x, y, speed);
                else newObject = new Heart(x, y, speed);
                break;
            case 2:
                
                if (objectType < 33) newObject = new Fruit(x, y, speed);           
                else if (objectType < 52) newObject = new Bomb(x, y, speed);            
                else if (objectType < 62) newObject = new GoldenFruit(x, y, speed);     
                else if (objectType < 77) newObject = new RottenFruit(x, y, speed);    
                else if (objectType < 87) newObject = new Poison(x, y, speed);          
                else if (objectType < 94) newObject = new Hourglass(x, y, speed);       
                else if (objectType < 97) newObject = new BrokenClock(x, y, speed);   
                else newObject = new Heart(x, y, speed);                                
                
                if (gameState.score > 1250 && random.nextInt(100) < 5) {
                    newObject = new Medkit(random.nextInt(PANEL_WIDTH - 60) + 30, y, speed + 1);
                }
                break;
            case 3:
                // Level 3: Added 5% Heart
                if (objectType < 28) newObject = new Fruit(x, y, speed);            
                else if (objectType < 40) newObject = new Bomb(x, y, speed);            
                else if (objectType < 50) newObject = new GoldenFruit(x, y, speed);     
                else if (objectType < 60) newObject = new Poison(x, y, speed);         
                else if (objectType < 70) newObject = new Anvil(x, y, speed);           
                else if (objectType < 80) newObject = new BrokenClock(x, y, speed);     
                else if (objectType < 90) newObject = new Hourglass(x, y, speed);       
                else if (objectType < 95) newObject = new Star(x, y, speed);            
                else newObject = new Heart(x, y, speed);                                

                if (gameState.score > 4000 && random.nextInt(100) < 5) {
                    newObject = new Medkit(random.nextInt(PANEL_WIDTH - 60) + 30, y, speed + 1);
                }
                break;
        }
        // --- END: PASTE NEW CODE HERE ---
        if (newObject != null) {
            fallingObjects.add(newObject);
        }
    }

    @Override
    protected void paintComponent(Graphics g) {
        super.paintComponent(g);

        BufferedImage bgToDraw = null;
        switch (gameState.level) {
            case 1: bgToDraw = GameLoader.background_lvl1; break;
            case 2: bgToDraw = GameLoader.background_lvl2; break;
            case 3: bgToDraw = GameLoader.background_lvl3; break;
            default: bgToDraw = GameLoader.background_lvl1;
        }

        if (bgToDraw != null) {
            Graphics2D g2d = (Graphics2D) g;
            
            g2d.drawImage(bgToDraw, 0, 0, PANEL_WIDTH, PANEL_HEIGHT, null);
            
        } else {
            g.setColor(Color.BLACK);
            g.fillRect(0, 0, PANEL_WIDTH, PANEL_HEIGHT);
        }

        basket.draw(g);

        for (FallingObject obj : fallingObjects) {
            obj.draw(g);
        }

        drawUI(g);
    }

    private void drawUI(Graphics g) {
        g.setColor(Color.WHITE);
        g.setFont(new Font("Arial", Font.BOLD, 20));

        g.drawString("Score: " + gameState.score, 20, 30);
        g.drawString("Target: " + gameState.targetScore, 20, 55);

        g.drawString("HP: " + gameState.hp + " / " + gameState.maxHp, 350, 30);
        g.drawString("Level: " + gameState.level, 350, 55);

        g.drawString("Time: " + gameState.time, 700, 30);

        g.setFont(new Font("Arial", Font.BOLD, 16));
        int statusY = PANEL_HEIGHT - 30;
        if (gameState.scoreMultiplierTimer > 0) {
            g.setColor(Color.YELLOW);
            int secondsLeft = (gameState.scoreMultiplierTimer / TICKS_PER_SECOND) + 1;
            g.drawString("SCORE x2 (" + secondsLeft + "s)", 20, statusY);
            statusY -= 20;
        }
        if (stunTimer > 0) {
            g.setColor(Color.RED);
            g.drawString("STUNNED!", 20, statusY);
        }

        if (gameState.isGameOver()) {
            g.setColor(Color.RED);
            g.setFont(new Font("Arial", Font.BOLD, 72));
            String msg = "GAME OVER";
            int stringWidth = g.getFontMetrics().stringWidth(msg);
            g.drawString(msg, (PANEL_WIDTH - stringWidth) / 2, PANEL_HEIGHT / 2);
        } else if (gameState.isGameWon()) {
            g.setColor(Color.GREEN);
            g.setFont(new Font("Arial", Font.BOLD, 72));
            String msg = "YOU WIN!";
            int stringWidth = g.getFontMetrics().stringWidth(msg);
            g.drawString(msg, (PANEL_WIDTH - stringWidth) / 2, PANEL_HEIGHT / 2);
        }
    }

    private void playSound(Clip clip) {
        if (clip != null) {
            if (clip.isRunning()) {
                clip.stop();
            }
            clip.setFramePosition(0);
            clip.start();
        }
    }

    private class GameKeyAdapter extends KeyAdapter {
        @Override
        public void keyPressed(KeyEvent e) {
            int keyCode = e.getKeyCode();
            if (keyCode == KeyEvent.VK_LEFT) movingLeft = true;
            if (keyCode == KeyEvent.VK_RIGHT) movingRight = true;
        }

        @Override
        public void keyReleased(KeyEvent e) {
            int keyCode = e.getKeyCode();
            if (keyCode == KeyEvent.VK_LEFT) movingLeft = false;
            if (keyCode == KeyEvent.VK_RIGHT) movingRight = false;
        }
    }
}