CREATE SCHEMA executive_tasks;

CREATE TABLE executive_tasks.exec_list 
(
    id bigserial NOT NULL,
    idtask bigint NOT NULL,
    employee integer NOT NULL,
    date timestamp without time zone NOT NULL,
    text character varying NOT NULL
);

CREATE TABLE executive_tasks.tasks 
(
    id bigserial NOT NULL,
    text character varying NOT NULL,
    date timestamp without time zone NOT NULL,
    employee integer NOT NULL,
    division integer NOT NULL,
    deadline timestamp without time zone
);

CREATE TABLE executive_tasks.took_list 
(
    id bigserial NOT NULL,
    idtask bigint NOT NULL,
    employee integer NOT NULL,
    date timestamp without time zone NOT NULL
);

INSERT INTO pagelist VALUES ((SELECT max(id)+1 FROM pagelist),'Задачи','/IVC/Tasks/index.php',2);