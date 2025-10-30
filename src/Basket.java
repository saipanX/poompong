

import java.awt.Graphics;

import java.awt.Rectangle;

public class Basket {
    private int x;
    private int y;
    private int width;
    private int height;
    private int moveSpeed;
    private int gameWidth;


    public Basket(int startX, int y, int width, int height, int gameWidth) {
        this.x = startX;
        this.y = y;
        
        if (GameLoader.basket != null) {
            this.width = GameLoader.basket.getWidth();
            this.height = GameLoader.basket.getHeight();
        } else {

            this.width = width;
            this.height = height;
        }

        
        this.moveSpeed = 10;
        this.gameWidth = gameWidth;
    }


    public void moveLeft() {
        this.x -= this.moveSpeed;
        if (this.x < 0) {
            this.x = 0;
        }
    }
    public void moveRight() {
        this.x += this.moveSpeed;
        if (this.x + this.width > this.gameWidth) {
            this.x = this.gameWidth - this.width;
        }
    }


    public void draw(Graphics g) {
        if (GameLoader.basket != null) {
            g.drawImage(GameLoader.basket, this.x, this.y, this.width, this.height, null);
        }
    }


    public Rectangle getBounds() {
        return new Rectangle(this.x, this.y, this.width, this.height);
    }
    public int getX() { return x; }
    public int getY() { return y; }
}