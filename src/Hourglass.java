// Save this as: src/Hourglass.java

public class Hourglass extends FallingObject {

    public Hourglass(int x, int y, int speed) {
        super(x, y, speed);

        setImage(GameLoader.hourglass);
    }

    @Override
    public void applyEffect(GameState gameState) {
        gameState.addTime(5);
    }
}