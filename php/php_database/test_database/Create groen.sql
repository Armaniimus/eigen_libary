DROP DATABASE if EXISTS Project_over_de_rhein;
CREATE DATABASE Project_over_de_rhein;
USE Project_over_de_rhein;

CREATE TABLE opdrachten(
    Opdrachtnummer INT AUTO_INCREMENT NOT NULL,
    Werkinstuctie VARCHAR(500) NOT NULL,
    Datum_uitvoering DATE NOT NULL,
    Kabelleverancier VARCHAR(80) NOT NULL,
    Waarnemingen VARCHAR(300) NOT NULL,
    Handtekening LONGBLOB NOT NULL,
    Aantal_bedrijfsuren Decimal(8,2) NOT NULL,
    Afleg_Redenen VARCHAR(300) NOT NULL,

    PRIMARY KEY (Opdrachtnummer)
);

INSERT INTO opdrachten (Werkinstuctie, Datum_uitvoering, Kabelleverancier, Waarnemingen, Handtekening, Aantal_bedrijfsuren, Afleg_Redenen)
VALUES
('W_instr 1', CURRENT_TIMESTAMP, 'Kabelleverancier 1', 'waarneming 1', 'Handtekening 1', 5.2, 'Afleg_Redenen 1'),
('W_instr 2', CURRENT_TIMESTAMP, 'Kabelleverancier 2', 'waarneming 2', 'Handtekening 2', 5.2, 'Afleg_Redenen 2'),
('W_instr 3', CURRENT_TIMESTAMP, 'Kabelleverancier 3', 'waarneming 3', 'Handtekening 3', 5.2, 'Afleg_Redenen 3'),
('W_instr 4', CURRENT_TIMESTAMP, 'Kabelleverancier 4', 'waarneming 4', 'Handtekening 4', 5.2, 'Afleg_Redenen 4');

CREATE TABLE Kabelchecklisten(
    KabelID INT AUTO_INCREMENT NOT NULL,
    Opdrachtnummer INT NOT NULL,
    Draadbreuk_6D INT NOT NULL,
    Draadbreuk_30D INT NOT NULL,
    Beschadiging_buitenzijde INT NOT NULL,
    Beschadiging_Roest_Corrosie INT NOT NULL,
    Verminderde_Kabeldiameter INT NOT NULL,
    Positie_Meetpunten INT NOT NULL,
    Beschadiging_Totaal INT NOT NULL,
    Type_Beschadiging_Roest INT NOT NULL,

    PRIMARY KEY (KabelID),
    FOREIGN KEY (Opdrachtnummer) REFERENCES opdrachten(Opdrachtnummer)
);

INSERT INTO Kabelchecklisten(Opdrachtnummer, Draadbreuk_6D, Draadbreuk_30D, Beschadiging_buitenzijde, Beschadiging_Roest_Corrosie, Verminderde_Kabeldiameter, Positie_Meetpunten, Beschadiging_Totaal, Type_Beschadiging_Roest)
VALUES
(1,1,11,1,1,1,1,1,9),
(1,1,24,5,6,1,1,4,9),

(2,2,22,2,2,2,2,2,9),
(2,9,23,8,2,2,5,4,9),

(3,3,31,3,3,3,3,3,9),

(4,4,44,4,4,4,4,4,9),
(4,1,44,2,8,5,4,9,9),
(4,3,24,1,3,5,7,9,2),
(4,5,43,2,8,5,2,8,1);
