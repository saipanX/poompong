// Save this as: src/Star.java

public class Star extends FallingObject {

    public Star(int x, int y, int speed) {
        super(x, y, speed);

        setImage(GameLoader.star);
        

        this.speed += 2;
    }


    @Override
    public void applyEffect(GameState gameState) {
        gameState.activateScoreMultiplier();
    }
}