CREATE DATABASE chess;
  USE chess
  CREATE TABLE chessdata (
    id INT NOT NULL PRIMARY KEY,
    turn INT NOT NULL,
    next_source VARCHAR(2),
    next_target VARCHAR(2)
  )
