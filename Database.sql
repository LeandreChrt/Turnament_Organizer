CREATE DATABASE Turnament;
USE Turnament;

CREATE TABLE total_points (
    id_gen_team int PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name varchar(3),
    gen_win smallint,
    gen_lost smallint,
    gen_points smallint,
    `gen_pv+` smallint,
    `gen_pv-` smallint,
    turnament_wins smallint
);

INSERT INTO total_points VALUES
    (null, '+UN', 0, 0, 0, 0, 0, 0),
    (null, 'ARE', 0, 0, 0, 0, 0, 0),
    (null, 'ART', 0, 0, 0, 0, 0, 0),
    (null, 'CHA', 0, 0, 0, 0, 0, 0),
    (null, 'DRA', 0, 0, 0, 0, 0, 0),
    (null, 'ENE', 0, 0, 0, 0, 0, 0),
    (null, 'ESP', 0, 0, 0, 0, 0, 0),
    (null, 'GOB', 0, 0, 0, 0, 0, 0),
    (null, 'GRI', 0, 0, 0, 0, 0, 0),
    (null, 'GUI', 0, 0, 0, 0, 0, 0),
    (null, 'JES', 0, 0, 0, 0, 0, 0),
    (null, 'KAZ', 0, 0, 0, 0, 0, 0),
    (null, 'MMA', 0, 0, 0, 0, 0, 0),
    (null, 'MUR', 0, 0, 0, 0, 0, 0),
    (null, 'OIS', 0, 0, 0, 0, 0, 0),
    (null, 'SER', 0, 0, 0, 0, 0, 0),
    (null, 'SOR', 0, 0, 0, 0, 0, 0),
    (null, 'VAM', 0, 0, 0, 0, 0, 0),
    (null, 'ZAK', 0, 0, 0, 0, 0, 0),
    (null, 'ZOM', 0, 0, 0, 0, 0, 0);

CREATE TABLE teams (
    id_team int PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_gen_team int,
    win smallint,
    lost smallint,
    points smallint,
    `pv+` smallint,
    `pv-` smallint
);

CREATE TABLE planning (
    id_match int PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_team_1 tinyint,
    score_1 smallint,
    id_team_2 tinyint,
    score_2 smallint,
    id_winner tinyint
);