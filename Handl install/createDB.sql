CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE DATABASE ivc;
CREATE TABLE IF NOT EXISTS Status 
(
  idStatus INT NOT NULL,
  Description CHARACTER VARYING(50) NOT NULL,
  PRIMARY KEY (idStatus)
);
CREATE UNIQUE INDEX IdStatus_UNIQUE ON Status(idStatus ASC);

CREATE TABLE IF NOT EXISTS Posts 
(
  idPost INT NOT NULL,
  Description CHARACTER VARYING(100) NOT NULL,
  PRIMARY KEY (idPost)
);
CREATE UNIQUE INDEX IdPosts_UNIQUE ON Posts(idPost ASC);

CREATE TABLE IF NOT EXISTS Divisions 
(
  idDivision SERIAL NOT NULL,
  Description CHARACTER VARYING(200) NOT NULL,
  Briefly CHARACTER VARYING(50) NOT NULL,
  PRIMARY KEY (idDivision)
);
CREATE UNIQUE INDEX IdDivision_UNIQUE ON Divisions(idDivision ASC);

CREATE TABLE IF NOT EXISTS Groups 
(
  idGroup SERIAL NOT NULL,
  Description CHARACTER VARYING(200) NOT NULL,
  PRIMARY KEY (idGroup)
);
CREATE UNIQUE INDEX IdGroup_UNIQUE ON Groups(idGroup ASC);

CREATE TABLE IF NOT EXISTS Users 
(
  idUser serial NOT NULL,
  Post INT NOT NULL,
  Status INT NOT NULL,
  LastName CHARACTER VARYING(100) NOT NULL,
  FirstName CHARACTER VARYING(100) NOT NULL,
  Patronimic CHARACTER VARYING(100) NOT NULL,
  Division INT NOT NULL,
  user_group INT NOT NULL,
  PRIMARY KEY (idUser),
  CONSTRAINT StatusUsr
    FOREIGN KEY (Status)
    REFERENCES Status (idStatus)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT PostUsr
    FOREIGN KEY (Post)
    REFERENCES Posts (idPost)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT DiviUsr
    FOREIGN KEY (Division)
    REFERENCES Divisions (idDivision)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT GrUsr
    FOREIGN KEY (user_group)
    REFERENCES Groups (idGroup)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);
CREATE INDEX DivisionUser_idx ON Users (Division ASC);
CREATE INDEX StatusUsr_idx ON Users (Status ASC);
CREATE INDEX PostUsr_idx ON Users (Post ASC);
CREATE INDEX GrUsr_idx ON Users (user_group ASC);

CREATE TABLE IF NOT EXISTS AuthorizationInfo
(
  idUser INT NOT NULL,
  Password CHARACTER VARYING NOT NULL,
  Login CHARACTER VARYING NOT NULL,
  PRIMARY KEY (idUser),
  CONSTRAINT AuthorizationUser
    FOREIGN KEY (idUser)
    REFERENCES Users (idUser)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);
CREATE UNIQUE INDEX idUser_UNIQUE ON AuthorizationInfo (idUser ASC);

CREATE TABLE IF NOT EXISTS UserEntryLog 
(
  id bigserial NOT NULL,
  IdUser INT NOT NULL,
  DateEntry timestamp without time zone NOT NULL,
  DateEscape timestamp without time zone NOT NULL,
  Hash uuid DEFAULT uuid_generate_v4() NOT NULL,
  Ip CHARACTER VARYING(15) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT Log
    FOREIGN KEY (IdUser)
    REFERENCES AuthorizationInfo (idUser)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);
CREATE UNIQUE INDEX id_UNIQUE ON UserEntryLog (id ASC);
CREATE INDEX Log_idx ON UserEntryLog (IdUser ASC);
-----------------
CREATE TABLE IF NOT EXISTS PageList 
(
  id INT NOT NULL,
  Name CHARACTER VARYING(45) NOT NULL,
  URL CHARACTER VARYING NOT NULL,
  accesslevel INT NOT NULL,
  PRIMARY KEY (id) 
);
CREATE UNIQUE INDEX PageId_UNIQUE ON PageList (id ASC);

CREATE TABLE IF NOT EXISTS ActionTypes 
(
  id INT NOT NULL,
  Name CHARACTER VARYING NOT NULL,
  PRIMARY KEY (id) 
);
CREATE UNIQUE INDEX ActionType_UNIQUE ON ActionTypes (id ASC);

CREATE TABLE IF NOT EXISTS Log 
(
  id bigserial NOT NULL,
  enterlog bigINT NOT NULL,
  pageid INT NOT NULL,
  actiontype INT NOT NULL,
  dateentry timestamp without time zone NOT NULL,
  description CHARACTER VARYING,
  PRIMARY KEY (id),
  CONSTRAINT Log_ActionType
    FOREIGN KEY (actiontype)
    REFERENCES ActionTypes (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT Log_Enter
    FOREIGN KEY (enterlog)
    REFERENCES UserEntryLog (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT Log_page
    FOREIGN KEY (pageid)
    REFERENCES PageList (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE UNIQUE INDEX Log_UNIQUE ON Log (id ASC);
CREATE INDEX ActionType_idx ON Log (actiontype ASC);
CREATE INDEX Log_Enter_idx ON Log (enterlog ASC);
CREATE INDEX Log_Page_idx ON Log (pageid ASC);

CREATE TABLE IF NOT EXISTS PageAccess 
(
  id serial NOT NULL,
  userid INT NOT NULL,
  pageid INT NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT PA_PageList
    FOREIGN KEY (pageid)
    REFERENCES PageList (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT PA_User
    FOREIGN KEY (userid)
    REFERENCES Users (idUser)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);
CREATE UNIQUE INDEX PageAccess_UNIQUE ON PageAccess (id ASC);
CREATE INDEX PA_PageList_idx ON PageAccess (pageid ASC);
CREATE INDEX PA_User_idx ON PageAccess (userid ASC);

INSERT INTO pagelist VALUES (0,'Личный кабинет','/IVC/index.php',1);

INSERT INTO status 
VALUES (0,'Заблокированная учётная запись'),
(1,'Отключенная учетная запись'),
(2,'Пользователь'),
(3,'Администратор');

INSERT INTO actiontypes 
VALUES 
(0,'Вход на страницу'),
(1,'Попытка входа на недоступную страницу');

CREATE FUNCTION public.getuserauthinfo(loginusr character varying) RETURNS TABLE(pass character varying, id integer)
    LANGUAGE plpgsql
    AS $$
BEGIN
  Return query
  SELECT Password AS Pass, IdUser AS Id FROM AuthorizationInfo WHERE Login = LoginUsr; --INTO PasswordUsr, IdUsr;
END;
$$;