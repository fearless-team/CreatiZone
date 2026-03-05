-- Create badges table
CREATE TABLE IF NOT EXISTS badges (
  id_badge    INT AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(100) NOT NULL,
  description VARCHAR(255) NOT NULL,
  icon        VARCHAR(10)  DEFAULT '🏅'
);

-- Create user_badges table
CREATE TABLE IF NOT EXISTS user_badges (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  user_id     INT NOT NULL,
  badge_id    INT NOT NULL,
  awarded_at  DATETIME DEFAULT NOW(),
  FOREIGN KEY (user_id)  REFERENCES user(id_user)   ON DELETE CASCADE,
  FOREIGN KEY (badge_id) REFERENCES badges(id_badge) ON DELETE CASCADE,
  UNIQUE KEY unique_user_badge (user_id, badge_id)
);

-- Insert default badges
INSERT INTO badges (id_badge, name, description, icon) VALUES
(1, 'Première participation', 'Soumettre sa première participation', '🥇'),
(2, '10 participations',      'Atteindre 10 participations au total', '🏆'),
(3, 'Participation populaire','Obtenir 5 votes sur une participation', '⭐')
ON DUPLICATE KEY UPDATE name=VALUES(name);