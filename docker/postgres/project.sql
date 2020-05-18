-- ##############################################
-- KURS: DT167G
-- Project

-- ##############################################
-- First we create the schema if it not exists
-- ##############################################

CREATE SCHEMA IF NOT EXISTS dt167g ;

-- ##############################################
-- First we create the user table
-- ##############################################
DROP TABLE IF EXISTS dt167g.users CASCADE;

CREATE TABLE dt167g.users (
  id        SERIAL PRIMARY KEY,
  username  text NOT NULL CHECK (username <> ''),
  password  text NOT NULL CHECK (password  <> ''),
  CONSTRAINT unique_user UNIQUE(username)
)
WITHOUT OIDS;

DROP TABLE IF EXISTS dt167g.posts;

CREATE TABLE dt167g.posts (
  id        SERIAL PRIMARY KEY,
  name      text      NOT NULL CHECK (name <> ''),
  message   text      NOT NULL CHECK (message  <> ''),
  iplog     inet      NOT NULL CHECK (iplog <> inet '0.0.0.0'),
  timelog   timestamp DEFAULT now()
)
WITHOUT OIDS;

DROP TABLE IF EXISTS dt167g.likes;

CREATE TABLE dt167g.likes(
      postId     INTEGER     NOT NULL,
      userId        INTEGER     NOT NULL,
      UNIQUE( postId, userId),
      FOREIGN KEY(postId) REFERENCES dt167g.posts(id) ON DELETE CASCADE,
      FOREIGN KEY(userId) REFERENCES dt167g.users(id) ON DELETE CASCADE
  )
WITHOUT OIDS;


-- -- ##############################################
-- -- Then we create the role table
-- -- ##############################################
-- DROP TABLE IF EXISTS dt167g.role CASCADE;
--
-- CREATE TABLE dt167g.role (
--   id        SERIAL PRIMARY KEY,
--   role      text NOT NULL CHECK (role <> ''),
--   roletext  text NOT NULL CHECK (roletext <> ''),
--   CONSTRAINT unique_role UNIQUE(role)
-- )
-- WITHOUT OIDS;

-- ##############################################
-- Now we insert some values
-- ##############################################
-- INSERT INTO dt167g.role (role, roletext) VALUES ('user','Meddlem i föreningen');
-- INSERT INTO dt167g.role (role, roletext) VALUES ('admin','Administratör i föreningen');

-- ##############################################
-- Then we create the role table
-- ##############################################
-- DROP TABLE IF EXISTS dt167g.user_role;
--
-- CREATE TABLE dt167g._user_role (
--   id        SERIAL PRIMARY KEY,
--   user_id integer REFERENCES dt167g.user (id),
--   role_id   integer REFERENCES dt167g.role (id),
--   CONSTRAINT unique_user_role UNIQUE(user_id, role_id)
-- )
-- WITHOUT OIDS;

-- ##############################################
-- Now we insert some values
-- ##############################################
-- INSERT INTO dt167g.user_role (user_id, role_id) VALUES (1,1);
-- INSERT INTO dt167g.user_role (user_id, role_id) VALUES (2,1);
-- INSERT INTO dt167g.user_role (user_id, role_id) VALUES (2,2);



-- ##############################################
-- Things that follows are not part of the laboration
-- but are put here to illustrate the power of stored procedures that
-- PostgreSQL support. Stored procedures can be write in
-- many different languages like PL/SQL, Python, PERL, JAVA, C etc...
-- Feel free to test and update a password in user and see the result in user_changelog
-- First we create a change log table for our user table
-- ##############################################

DROP TABLE IF EXISTS dt167g.user_changelog;

CREATE TABLE dt167g.user_changelog (
  id          SERIAL PRIMARY KEY,
  user_id   integer REFERENCES dt167g.users (id),
  username    text,
  password    text,
  time_change timestamp without time zone DEFAULT now()
)
WITHOUT OIDS;



-- ##############################################
-- Last we insert some values
-- ##############################################

-- INSERT INTO dt167g.guestbook (name, message, iplog, timelog) VALUES ('test','testing','192.168.1.1', '2018-02-02 12:21:21');


-- ##############################################
-- Then we create a trigger function to use for logging of all changes
-- made when updateing user tabel
-- ##############################################

-- First we have to create the language we are going to write our function.
CREATE EXTENSION IF NOT EXISTS plpgsql;

-- When we have created required language we can create our function
DROP FUNCTION IF EXISTS dt167g.save_user_change();

CREATE OR REPLACE FUNCTION dt167g.save_user_change()
  RETURNS trigger
AS
$BODY$
DECLARE
BEGIN
  IF OLD.username != NEW.username     OR
     OLD.password != NEW.password THEN
    INSERT INTO dt167g.user_changelog
    (user_id, username, password)
    VALUES (OLD.id, OLD.username, OLD.password);
  END IF;

  RETURN NEW;
END;
$BODY$
LANGUAGE 'plpgsql' VOLATILE;

-- ##############################################
-- Create TRIGGER
-- Now we create trigger so we use our function to log changes in user
-- ##############################################

DROP TRIGGER IF EXISTS user_change ON dt167g.users CASCADE;

CREATE TRIGGER user_change
AFTER UPDATE
  ON dt167g.users
FOR EACH ROW
EXECUTE PROCEDURE dt167g.save_user_change();