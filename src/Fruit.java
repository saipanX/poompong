

public class Fruit extends FallingObject {

    public Fruit(int x, int y, int speed) {
        super(x, y, speed);

        setImage(GameLoader.fruit);
    }

    @Override
    public void applyEffect(GameState gameState) {
        gameState.addScore(15); 
    }
}