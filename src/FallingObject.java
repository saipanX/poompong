

import java.awt.Graphics;
import java.awt.Rectangle;
import java.awt.image.BufferedImage;
import java.awt.Color; 


public abstract class FallingObject {

    protected int x;
    protected int y;
    protected int speed;
    

    protected int width = 30; 
    protected int height = 30;

    protected BufferedImage image;

    public FallingObject(int x, int y, int speed) {
        this.x = x;
        this.y = y;
        this.speed = speed;
        
    }
    

    protected void setImage(BufferedImage image) {
        this.image = image;
        if (this.image != null) {
            // Auto-size to the image's dimensions
            this.width = this.image.getWidth();
            this.height = this.image.getHeight();
        } else {
            // Fallback if image was null
            this.width = 30;
            this.height = 30;
        }
    }

    public void update() {
        this.y += this.speed;
    }
 
    public Rectangle getBounds() {
        return new Rectangle(x, y, width, height);
    }

    public boolean isOffScreen(int screenHeight) {
        return this.y > screenHeight;
    }
    

    public abstract void applyEffect(GameState gameState);


    public void draw(Graphics g) {
        if (this.image != null) {
  
            g.drawImage(this.image, this.x, this.y, this.width, this.height, null);
        } else {

            g.setColor(Color.WHITE);
            g.fillRect(this.x, this.y, this.width, this.height);
        }
    }


    public int getX() { return x; }
    public int getY() { return y; }
}