DROP SCHEMA IF EXISTS lab5demo CASCADE;

CREATE SCHEMA lab5demo;

SET search_path = lab5demo;

--
-- Create some tables
--
CREATE TABLE part (
  pid SERIAL PRIMARY KEY,
  description varchar(50) NOT NULL,
  price numeric(8,2) NOT NULL
);

CREATE TABLE purchase_order (
  oid serial PRIMARY KEY,
  customer_name varchar(100) NOT NULL,
  date date NOT NULL DEFAULT NOW()
);
CREATE TABLE purchase_order_item (
  oid integer REFERENCES purchase_order,
  pid integer REFERENCES part,
  quantity integer DEFAULT 100,
  PRIMARY KEY (oid, pid)
);

--
-- Load some data
--
INSERT INTO part VALUES 
  (DEFAULT, 'stapler', 4.50),
  (DEFAULT, 'pen', 0.75),
  (DEFAULT, 'box', 7.00),
  (DEFAULT, 'tape', 2.50),
  (DEFAULT, 'pencil', 0.45);

INSERT INTO purchase_order VALUES 
  (DEFAULT, 'Mike Smith', '2014-09-13'),
  (DEFAULT, 'Lisa Wilson', '2014-09-29');

INSERT INTO purchase_order_item VALUES 
  (1, 2, 100),
  (1, 5, 250),
  (2, 3, 10);



