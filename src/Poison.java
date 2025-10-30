// Save this as: src/Poison.java

public class Poison extends FallingObject {

    public Poison(int x, int y, int speed) {
        super(x, y, speed);

        setImage(GameLoader.poison);
    }

    @Override
    public void applyEffect(GameState gameState) {
        gameState.addHp(-2);
    }
}