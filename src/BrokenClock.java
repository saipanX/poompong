
public class BrokenClock extends FallingObject {

    public BrokenClock(int x, int y, int speed) {
        super(x, y, speed);

        setImage(GameLoader.brokenClock);
    }


    @Override
    public void applyEffect(GameState gameState) {
        if (gameState.level == 3) {
            gameState.addTime(-10); // Level 3
        } else {
            gameState.addTime(-5);  // Level 2
        }
    }
}