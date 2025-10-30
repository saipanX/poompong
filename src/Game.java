

import javax.swing.JFrame; 

public class Game {

    public static void main(String[] args) {
        

        GameLoader.loadAssets();

        
        JFrame window = new JFrame("Fruit Catches Game"); 
        window.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        window.setResizable(false);

        GamePanel gamePanel = new GamePanel(); 
        window.add(gamePanel);
        window.pack();
        window.setLocationRelativeTo(null);
        window.setVisible(true);
        
        gamePanel.requestFocusInWindow();
    }
}