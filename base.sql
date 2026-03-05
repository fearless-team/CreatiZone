CREATE DATABASE challenge_hub;
USE challenge_hub;

CREATE TABLE user (
    id_user     INT PRIMARY KEY AUTO_INCREMENT,
    nom         VARCHAR(25) NOT NULL,
    prenom      VARCHAR(25) NOT NULL,
    email       VARCHAR(50) UNIQUE NOT NULL,
    motdepasse  VARCHAR(255) NOT NULL
);

CREATE TABLE challenge (
    id_challenge  INT AUTO_INCREMENT PRIMARY KEY,
    titre         VARCHAR(255) NOT NULL,
    description   TEXT NOT NULL,
    categorie     VARCHAR(100) NOT NULL,
    deadline      DATE NOT NULL,
    id_user       INT NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    image         VARCHAR(255) DEFAULT NULL,
    FOREIGN KEY (id_user)
        REFERENCES user(id_user)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE submissions (
    id_submission INT AUTO_INCREMENT PRIMARY KEY,
    id_user       INT NOT NULL,
    id_challenge  INT NOT NULL,
    description   TEXT NOT NULL,
    image         VARCHAR(255) DEFAULT NULL,
    link          VARCHAR(255) DEFAULT NULL,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user)
        REFERENCES user(id_user)
        ON DELETE CASCADE,
    FOREIGN KEY (id_challenge)
        REFERENCES challenge(id_challenge)
        ON DELETE CASCADE
);

CREATE TABLE votes (
    id               INT PRIMARY KEY AUTO_INCREMENT,
    id_submission    INT NOT NULL,
    id_user          INT NOT NULL,
    note             TINYINT NOT NULL CHECK (note BETWEEN 1 AND 5),
    voted_at         DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_vote (id_submission, id_user),
    FOREIGN KEY (id_submission)
        REFERENCES submissions(id_submission)
        ON DELETE CASCADE,
    FOREIGN KEY (id_user)
        REFERENCES user(id_user)
        ON DELETE CASCADE
);
CREATE TABLE comments (
    id_comment       INT PRIMARY KEY AUTO_INCREMENT,
    id_submission    INT NOT NULL,
    id_user          INT NOT NULL,
    content          TEXT NOT NULL,
    created_at       DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_submission)
        REFERENCES submissions(id_submission)
        ON DELETE CASCADE,
    FOREIGN KEY (id_user)
        REFERENCES user(id_user)
        ON DELETE CASCADE
)