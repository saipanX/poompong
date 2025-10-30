// Save this as: src/Medkit.java

public class Medkit extends FallingObject {

    public Medkit(int x, int y, int speed) {
        super(x, y, speed);
        // --- UPDATED: Use the new method ---
        setImage(GameLoader.medkit);
        // (The auto-sizing will use the image's real size)
    }

    @Override
    public void applyEffect(GameState gameState) {
        gameState.addHp(3);
    }
}