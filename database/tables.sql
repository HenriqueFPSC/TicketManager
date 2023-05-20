PRAGMA foreign_keys = ON;
.mode columns
.headers on
.nullvalue NULL

--------

CREATE TABLE IF NOT EXISTS User (
    username STRING PRIMARY KEY NOT NULL,
    name STRING NOT NULL,
    email STRING UNIQUE NOT NULL,
    password STRING NOT NULL,
    salt STRING NOT NULL,
    userType STRING NOT NULL CHECK (userType IN('Client', 'Agent', 'Admin'))
);

--------

CREATE TABLE IF NOT EXISTS Department (
    name STRING PRIMARY KEY NOT NULL,
    abbrev STRING UNIQUE NOT NULL
);

--------

CREATE TABLE IF NOT EXISTS Ticket (
    id INTEGER PRIMARY KEY NOT NULL,
    publisher STRING NOT NULL REFERENCES User(username),
    department STRING NOT NULL REFERENCES Department(username),
    publishDate DATETIME NOT NULL,
    subject STRING NOT NULL,
    text STRING NOT NULL
);

--------

CREATE TABLE IF NOT EXISTS TicketStatus (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    ticketId INTEGER NOT NULL REFERENCES Ticket(id),
    agentUsername STRING REFERENCES User(username),
    date DATETIME NOT NULL,
    priority STRING NOT NULL CHECK (priority IN('Normal', 'High', 'Urgent')),
    status STRING NOT NULL CHECK (status IN('Unassigned', 'Assigned', 'Done'))
);

--------

CREATE TABLE IF NOT EXISTS TicketComment (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    ticketId INTEGER NOT NULL REFERENCES Ticket(id),
    user STRING REFERENCES User(username),
    date DATETIME NOT NULL,
    text STRING NOT NULL
);

--------

CREATE TABLE IF NOT EXISTS Hashtag (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    ticketId INTEGER NOT NULL REFERENCES Ticket(id),
    hashtag STRING NOT NULL
);

--------

CREATE TABLE IF NOT EXISTS FAQ (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    question STRING NOT NULL,
    answer STRING NOT NULL
);

--------

CREATE TABLE IF NOT EXISTS AgentInDepartment (
    agentUsername STRING NOT NULL REFERENCES User(username),
    department STRING NOT NULL REFERENCES Department(name)
);

-------

CREATE TABLE IF NOT EXISTS Preferences (
    username STRING PRIMARY KEY REFERENCES User(username) NOT NULL,
    filterNormal BOOLEAN NOT NULL DEFAULT TRUE,
    filterHigh BOOLEAN NOT NULL DEFAULT TRUE,
    filterUrgent BOOLEAN NOT NULL DEFAULT TRUE,
    filterUnassigned BOOLEAN NOT NULL DEFAULT TRUE,
    filterAssigned BOOLEAN NOT NULL DEFAULT TRUE,
    filterDone BOOLEAN NOT NULL DEFAULT TRUE,
    filterDateFrom DATETIME NOT NULL DEFAULT '2020-01-01 00:00:00',
    filterDateTo DATETIME NOT NULL DEFAULT '2030-01-01 00:00:00'
);

CREATE VIEW LatestStatus AS SELECT id, ticketId, agentUsername, max(date) as date, priority, status FROM TicketStatus GROUP BY ticketId;

--------------
-- TRIGGERS --
--------------

CREATE TRIGGER IF NOT EXISTS UserPreferences
AFTER INSERT ON User
BEGIN
    INSERT INTO Preferences(username) VALUES (NEW.username);
END;

-------

CREATE TRIGGER IF NOT EXISTS DeleteUser
BEFORE DELETE ON User
BEGIN
    DELETE FROM Preferences WHERE username = OLD.username;
    DELETE FROM AgentInDepartment WHERE username = OLD.username;
END;

-------

CREATE TRIGGER IF NOT EXISTS DeleteDepartment
BEFORE DELETE ON Department
BEGIN
    DELETE FROM Ticket WHERE department = OLD.name;
    DELETE FROM AgentInDepartment WHERE department = OLD.name;
END;

-------

CREATE TRIGGER IF NOT EXISTS DeleteTicket
BEFORE DELETE ON Ticket
BEGIN
    DELETE FROM TicketStatus WHERE ticketId = OLD.id;
    DELETE FROM TicketComment WHERE ticketId = OLD.id;
    DELETE FROM Hashtag WHERE ticketId = OLD.id;
END;
