

public class Anvil extends FallingObject {

    public Anvil(int x, int y, int speed) {
        super(x, y, speed);

        setImage(GameLoader.anvil);
        

        this.speed += 1;
    }


    @Override
    public void applyEffect(GameState gameState) {
        gameState.addHp(-1);
    }
}