DROP DATABASE IF EXISTS stardunks;
CREATE DATABASE stardunks;
USE stardunks;

CREATE TABLE products(
    product_id INT AUTO_INCREMENT,
    product_type_code INT,
    supplier_id INT,
    product_name VARCHAR(30),
    product_price DECIMAL(6,2),
    other_product_details VARCHAR(20),
    PRIMARY KEY (product_id)
);

INSERT INTO products(product_type_code, supplier_id, product_name, product_price, other_product_details)
VALUES
(1,1,'sprinkled',1.29,'1 pc'),
(1,1,'chocolate', 1.49, '1 pc'),
(1,1,'maple', 1.49, '1 pc'),
(2,2,'dunkaccino', 1.69,'Small'),
(3,3,'Steak long Jim', 2.29, 'Steak Wrap');
