
SET search_path = lab5demo;

--
-- Using EXISTS
--
SELECT * FROM part AS p
WHERE EXISTS (SELECT 1
              FROM purchase_order_item AS poi
              WHERE poi.pid = p.pid
             );

--
-- Using NOT EXISTS
--
SELECT * FROM part AS p
WHERE NOT EXISTS (SELECT 1
                  FROM purchase_order_item AS poi
                  WHERE poi.pid = p.pid
                 );

--
-- Using IN.  Notice the distinction between this and EXISTS.  EXISTS simply checks to see if a result was returned.   This actually compares pid to the set that's returned from the inner query.  
--
SELECT * FROM part AS p
WHERE pid IN (SELECT poi.pid
  FROM purchase_order_item AS poi
);

--
-- Using NOT IN
--
SELECT * FROM part AS p
WHERE pid NOT IN (SELECT poi.pid
  FROM purchase_order_item AS poi
);

--
-- Table expressions.  Try running the inner query by itself.
--
SELECT *
FROM part AS p,
(
  SELECT *
  FROM purchase_order_item
  WHERE oid = 1
) AS some_order
WHERE p.pid = some_order.pid;

--
-- SELECT and WITH
--
WITH recent_orders AS (
  SELECT oid
  FROM purchase_order
  WHERE date >= '2014-09-20'
)
SELECT *
FROM purchase_order_item AS poi, recent_orders
WHERE recent_orders.oid = poi.oid;

--
-- DELETE and WITH
--
WITH slow_moving_items AS (
  DELETE FROM part
  WHERE pid NOT IN (
    SELECT pid
    FROM purchase_order_item
  ) RETURNING *
)
INSERT INTO part (description, price)
SELECT ‘(Clearance) ’ || description,
round(0.5*price, 2)
FROM slow_moving_items;

--
-- Reload the dataset
--

--
-- UPDATE and WITH
--
WITH slow_moving_items AS (
  UPDATE part
  SET description =
  ‘(Clearance) ’ || description,
  price = round(0.5*price, 2)
  WHERE pid NOT IN (
    SELECT pid FROM purchase_order_item
  )
  RETURNING *
)
SELECT * FROM slow_moving_items;

--
-- Reload the dataset
--

--
-- DELETE and WITH
--
WITH new_order AS (
  INSERT INTO purchase_order
  VALUES (DEFAULT, ‘Jane Smith’, DEFAULT)
  RETURNING *
)
SELECT oid, date
FROM purchase_order
WHERE customer_name IN (
  SELECT customer_name FROM new_order
)
ORDER BY date DESC;

