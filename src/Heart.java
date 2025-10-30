

public class Heart extends FallingObject {

    public Heart(int x, int y, int speed) {
        super(x, y, speed);

        setImage(GameLoader.heart);
    }

    @Override
    public void applyEffect(GameState gameState) {
        gameState.addHp(1);
    }
}