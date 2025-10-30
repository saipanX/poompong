

import javax.imageio.ImageIO;
import javax.sound.sampled.*;
import java.awt.image.BufferedImage;
import java.io.IOException;
import java.net.URL;

public class GameLoader {


    public static BufferedImage background_lvl1;
    public static BufferedImage background_lvl2;
    public static BufferedImage background_lvl3;

    
    public static BufferedImage basket;
    public static BufferedImage fruit;
    public static BufferedImage bomb;
    public static BufferedImage heart;
    public static BufferedImage goldenFruit;
    public static BufferedImage rottenFruit;
    public static BufferedImage poison;
    public static BufferedImage hourglass;
    public static BufferedImage brokenClock;
    public static BufferedImage medkit;
    public static BufferedImage anvil;
    public static BufferedImage star;

    // --- Audio Assets ---
    public static Clip backgroundMusic;
    public static Clip collectSound;
    public static Clip bombSound;
    public static Clip levelUpSound;
    public static Clip gameOverSound;

    public static void loadAssets() {
        System.out.println("Loading assets...");
        try {

            background_lvl1 = loadImage("/res/images/background_lvl1.png");
            background_lvl2 = loadImage("/res/images/background_lvl2.png");
            background_lvl3 = loadImage("/res/images/background_lvl3.png");

            
            basket = loadImage("/res/images/basket.png");
            fruit = loadImage("/res/images/fruit.png");
            bomb = loadImage("/res/images/bomb.png");
            heart = loadImage("/res/images/heart.png");
            goldenFruit = loadImage("/res/images/golden_fruit.png");
            rottenFruit = loadImage("/res/images/rotten_fruit.png");
            poison = loadImage("/res/images/poison.png");
            hourglass = loadImage("/res/images/hourglass.png");
            brokenClock = loadImage("/res/images/broken_clock.png");
            medkit = loadImage("/res/images/medkit.png");
            anvil = loadImage("/res/images/anvil.png");
            star = loadImage("/res/images/star.png");


            backgroundMusic = loadSound("/res/audio/background_music.wav");
            collectSound = loadSound("/res/audio/collect.wav");
            bombSound = loadSound("/res/audio/bomb.wav");
            levelUpSound = loadSound("/res/audio/level_up.wav");
            gameOverSound = loadSound("/res/audio/game_over.wav");
            
            System.out.println("Assets loaded successfully!");

        } catch (Exception e) {
            System.err.println("Error loading assets!");
            e.printStackTrace();
            System.exit(1);
        }
    }


    private static BufferedImage loadImage(String path) throws IOException {
        URL url = GameLoader.class.getResource(path);
        if (url == null) {
            throw new IOException("Cannot find resource: " + path);
        }
        return ImageIO.read(url);
    }

    private static Clip loadSound(String path) {
        try {
            URL url = GameLoader.class.getResource(path);
            if (url == null) {
                throw new IOException("Cannot find resource: " + path);
            }
            AudioInputStream audioIn = AudioSystem.getAudioInputStream(url);
            Clip clip = AudioSystem.getClip();
            clip.open(audioIn);
            return clip;
        } catch (Exception e) {
            System.err.println("Warning: Could not load sound: " + path);
            e.printStackTrace();
            return null; 
        }
    }
}