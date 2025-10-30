// Save this as: src/RottenFruit.java

public class RottenFruit extends FallingObject {

    public RottenFruit(int x, int y, int speed) {
        super(x, y, speed);

        setImage(GameLoader.rottenFruit);
    }

    @Override
    public void applyEffect(GameState gameState) {
        gameState.addScore(-25);
    }
}