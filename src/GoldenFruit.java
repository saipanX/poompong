// Save this as: src/GoldenFruit.java

public class GoldenFruit extends FallingObject {

    public GoldenFruit(int x, int y, int speed) {
        super(x, y, speed);

        setImage(GameLoader.goldenFruit);
    }

    @Override
    public void applyEffect(GameState gameState) {
        gameState.addScore(50);
    }
}