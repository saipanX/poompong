

public class Bomb extends FallingObject {

    public Bomb(int x, int y, int speed) {
        super(x, y, speed);

        setImage(GameLoader.bomb);
    }

    @Override
    public void applyEffect(GameState gameState) {
        gameState.addHp(-1);
        gameState.addScore(-10);
    }
}