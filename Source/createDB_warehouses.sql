CREATE SCHEMA IF NOT EXISTS Warehouses;
SET search_path TO Warehouses;

CREATE TABLE IF NOT EXISTS measure 
(
  id serial PRIMARY KEY,
  name CHARACTER VARYING(45) UNIQUE NOT NULL
);

CREATE TABLE IF NOT EXISTS categories 
(
  id serial PRIMARY KEY,
  name CHARACTER VARYING(100) UNIQUE NOT NULL
);

CREATE TABLE IF NOT EXISTS products 
(
  id bigserial PRIMARY KEY NOT NULL,
  name CHARACTER VARYING(255) NOT NULL,
  measure INT NOT NULL,
  category INT NOT NULL,
  buh_kode bigint NULL,
  designation CHARACTER VARYING(50) NULL,
  CONSTRAINT mesure_prod
    FOREIGN KEY (measure)
    REFERENCES measure (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT prod_category
    FOREIGN KEY (category)
    REFERENCES categories (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);
CREATE UNIQUE INDEX IdProduct_UNIQUE ON products(id ASC);
CREATE INDEX mesure_prod_idx ON products(measure);
CREATE INDEX prod_category_idx ON products(category);

CREATE TABLE IF NOT EXISTS contragent 
(
  id serial PRIMARY KEY,
  name CHARACTER VARYING(100) NOT NULL,
  city CHARACTER VARYING(100) NOT NULL,
  inn bigint NOT NULL
);

CREATE TABLE IF NOT EXISTS warehouses 
(
  id serial PRIMARY KEY,
  name CHARACTER VARYING(55) NOT NULL
);

CREATE TABLE IF NOT EXISTS income 
(
  id serial PRIMARY KEY,
  date timestamp without time zone NOT NULL,
  contragent INT NOT NULL,
  recieved INT NOT NULL,
  warehouse INT NOT NULL,
  CONSTRAINT inc_usr
    FOREIGN KEY (recieved)
    REFERENCES public.users (iduser)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT inc_contr
    FOREIGN KEY (contragent)
    REFERENCES contragent (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT icn_ware
    FOREIGN KEY (warehouse)
    REFERENCES warehouses (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);
CREATE INDEX icn_usr_idx ON income (recieved ASC);
CREATE INDEX inc_contr_idx ON income (contragent ASC);
CREATE INDEX icn_ware_idx ON income (warehouse ASC);

CREATE TABLE IF NOT EXISTS expense 
(
  id serial PRIMARY KEY,
  date timestamp without time zone NOT NULL,
  released INT NOT NULL,
  received INT NOT NULL,
  CONSTRAINT exp_usr
    FOREIGN KEY (released)
    REFERENCES public.users (iduser)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT exp_rec
    FOREIGN KEY (received)
    REFERENCES public.users (iduser)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);
CREATE INDEX exp_usr_idx ON expense (released ASC);
CREATE INDEX exp_rec_idx ON expense (received ASC);

CREATE TABLE IF NOT EXISTS income_composition 
(
  id serial PRIMARY KEY,
  product bigint NOT NULL,
  quantity real NOT NULL,
  price money NOT NULL,
  income_id INT NOT NULL,
  CONSTRAINT inc_prod_compos
    FOREIGN KEY (product)
    REFERENCES products (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT inc_id
    FOREIGN KEY (income_id)
    REFERENCES income (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);
CREATE INDEX inc_prod_compos_idx ON income_composition (product ASC);
CREATE INDEX inc_id_idx ON income_composition (income_id ASC);

CREATE TABLE IF NOT EXISTS expense_composition 
(
  id serial PRIMARY KEY,
  income_id INT NOT NULL,
  quntity real NOT NULL,
  expension_id INT NOT NULL,
  CONSTRAINT exp_id
    FOREIGN KEY (expension_id)
    REFERENCES expense (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT exp_inc_comp
    FOREIGN KEY (income_id)
    REFERENCES income_composition (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);
CREATE INDEX exp_id_idx ON expense_composition (expension_id ASC);
CREATE INDEX exp_inc_comp_idx ON expense_composition (income_id ASC);

CREATE TABLE IF NOT EXISTS moving 
(
  id serial PRIMARY KEY,
  date timestamp without time zone NOT NULL,
  released INT NOT NULL,
  received INT NOT NULL,
  received_warehouse INT NOT NULL,
  CONSTRAINT mov_usr
    FOREIGN KEY (released)
    REFERENCES public.users (iduser)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT mov_rec
    FOREIGN KEY (received)
    REFERENCES public.users (iduser)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT mov_ware
    FOREIGN KEY (received_warehouse)
    REFERENCES warehouses (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);
CREATE INDEX mov_usr_idx ON moving (released ASC);
CREATE INDEX mov_rec_idx ON moving (received ASC);
CREATE INDEX mov_ware_idx ON moving (received_warehouse ASC);

CREATE TABLE IF NOT EXISTS moving_composition 
(
  id serial PRIMARY KEY,
  income_id INT NOT NULL,
  quntity real NOT NULL,
  moving_id INT NOT NULL,
  CONSTRAINT mov_inc_id
    FOREIGN KEY (income_id)
    REFERENCES income_composition (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT mov_id
    FOREIGN KEY (moving_id)
    REFERENCES moving (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);
CREATE INDEX mov_inc_id_idx ON moving_composition (income_id ASC);
CREATE INDEX mov_id_idx ON moving_composition (moving_id ASC);

CREATE TABLE IF NOT EXISTS move_map 
(
  id serial PRIMARY KEY,
  move INT NOT NULL,
  income INT NOT NULL,
  CONSTRAINT map_income
    FOREIGN KEY (income)
    REFERENCES income (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT map_move
    FOREIGN KEY (move)
    REFERENCES moving (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);
CREATE INDEX map_income_idx ON move_map (income ASC);
CREATE INDEX map_move_idx ON move_map (move ASC);

CREATE TABLE IF NOT EXISTS booking 
(
  id serial PRIMARY KEY,
  booked INT NOT NULL,
  date timestamp without time zone NOT NULL,
  status INT NOT NULL,
  CONSTRAINT booking_usr
    FOREIGN KEY (booked)
    REFERENCES public.users (iduser)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);
CREATE INDEX booking_usr_idx ON booking (booked ASC);

CREATE TABLE IF NOT EXISTS booking_composition 
(
  id serial PRIMARY KEY,
  booking_id INT NOT NULL,
  income_composition_id INT NOT NULL,
  quantity real NOT NULL,
  CONSTRAINT bokking_id
    FOREIGN KEY (booking_id)
    REFERENCES booking (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT booking_inc_compostition
    FOREIGN KEY (income_composition_id)
    REFERENCES income_composition (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);
CREATE INDEX bokking_id_idx ON booking_composition (booking_id ASC);
CREATE INDEX booking_inc_compostition_idx ON booking_composition (income_composition_id ASC);

CREATE TABLE IF NOT EXISTS booking_map 
(
  id serial PRIMARY KEY,
  booking INT NOT NULL,
  expence INT NOT NULL,
  CONSTRAINT booking_map
    FOREIGN KEY (booking)
    REFERENCES booking (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT expensionc_map
    FOREIGN KEY (expence)
    REFERENCES expense (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);
CREATE INDEX boking_map_idx ON booking_map (booking ASC);
CREATE INDEX expire_map_idx ON booking_map (expence ASC);

CREATE TABLE IF NOT EXISTS order_status 
(
  id serial PRIMARY KEY,
  description CHARACTER VARYING(45) NOT NULL
);

CREATE TABLE IF NOT EXISTS orders 
(
  id serial PRIMARY KEY,
  ordered INT NOT NULL,
  date timestamp without time zone NOT NULL,
  contragent INT NOT NULL,
  status INT NOT NULL,
  CONSTRAINT ord_usr
    FOREIGN KEY (ordered)
    REFERENCES public.users (iduser)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT contragent_order
    FOREIGN KEY (contragent)
    REFERENCES contragent (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT ord_stat
    FOREIGN KEY (status)
    REFERENCES order_status (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);
CREATE INDEX ord_usr_idx ON orders (ordered ASC);
CREATE INDEX contragent_order_idx ON orders (contragent ASC);
CREATE INDEX ord_stat_idx ON orders (status ASC);

CREATE TABLE IF NOT EXISTS order_composition 
(
  id serial PRIMARY KEY,
  product bigint NOT NULL,
  quantity real NOT NULL,
  price CHARACTER VARYING(45) NOT NULL,
  order_id INT NOT NULL,
  CONSTRAINT order_id
    FOREIGN KEY (order_id)
    REFERENCES orders (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT product_id
    FOREIGN KEY (product)
    REFERENCES products (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);
CREATE INDEX order_id_idx ON order_composition (order_id ASC);
CREATE INDEX product_id_idx ON order_composition (product ASC);

CREATE TABLE IF NOT EXISTS order_map 
(
  id serial PRIMARY KEY,
  order_id INT NOT NULL,
  income INT NOT NULL,
  CONSTRAINT inc_map
    FOREIGN KEY (income)
    REFERENCES income (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT ord_map
    FOREIGN KEY (order_id)
    REFERENCES orders (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);
CREATE INDEX inc_map_idx ON order_map (income ASC);
CREATE INDEX ord_map_idx ON order_map (order_id ASC);

CREATE TABLE IF NOT EXISTS contracts 
(
  id serial PRIMARY KEY,
  name CHARACTER VARYING(45) NOT NULL
);

CREATE TABLE IF NOT EXISTS contract_map 
(
  id serial PRIMARY KEY,
  contract INT NOT NULL,
  product_in_order_composition INT NOT NULL,
  quantity real NOT NULL,
  CONSTRAINT contract_map_ord_comp
    FOREIGN KEY (product_in_order_composition)
    REFERENCES order_composition (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT contract_map
    FOREIGN KEY (contract)
    REFERENCES contracts (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);
CREATE INDEX contract_map_ord_comp_idx ON contract_map (product_in_order_composition ASC);
CREATE INDEX contract_map_idx ON contract_map (contract ASC);

CREATE TABLE IF NOT EXISTS Warehouses_managers 
(
  id serial PRIMARY KEY,
  manager INT NOT NULL,
  warehouse INT NOT NULL,
  CONSTRAINT manager_ref
    FOREIGN KEY (manager)
    REFERENCES public.users (iduser)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT warehouse_ref
    FOREIGN KEY (warehouse)
    REFERENCES warehouses (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);
CREATE INDEX manager_ref_idx ON Warehouses_managers (manager ASC);
CREATE INDEX warehouse_ref_idx ON Warehouses_managers (warehouse ASC);

CREATE TABLE IF NOT EXISTS categories_in_warehouses 
(
  id serial PRIMARY KEY,
  category INT NOT NULL,
  warehouse INT NOT NULL,
  CONSTRAINT categories_id_ref
    FOREIGN KEY (category)
    REFERENCES categories (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT warehouse_id_ref
    FOREIGN KEY (warehouse)
    REFERENCES warehouses (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);
CREATE INDEX categories_id_ref_idx ON categories_in_warehouses (category ASC);
CREATE INDEX warehouse_id_ref_idx ON categories_in_warehouses (warehouse ASC);

INSERT INTO measure (id, name) VALUES (006, 'Метр'),
(053,'Дециметр квадратный'),
(055, 'Метр квадратный'),
(113, 'Метр кубический'),
(120, 'Миллион декалитров'),
(163, 'Грамм'),
(166, 'Килограмм'),
(796, 'Штука');

SET search_path TO public;

INSERT INTO pagelist VALUES ((SELECT max(id)+1 FROM pagelist),'Складской учёт','/IVC/Warehouses/Inventory_control/index.php',2);
INSERT INTO pagelist VALUES ((SELECT max(id)+1 FROM pagelist),'Настройка складского учёта','/IVC/Warehouses/AdmPanel/index.php',2);