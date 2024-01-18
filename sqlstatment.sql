mysql -u root -p 
show databases;
create database name_database;
use name_database;
desc name_Table;

CREATE DATABASE project1;

CREATE TABLE category (
    id int primary key auto_increment,
    name varchar(200) not null,
    created_at datetime,
    updated_at datetime
);

    CREATE TABLE product (
        id int primary key auto_increment,
        title varchar(200) not null,
        price float,
        thumbnail varchar(500),
        content longtext,
        id_category int references category(id),
        created_at datetime,
        updated_at datetime
    );

    CREATE TABLE customer (     
        id int primary key auto_increment,
        username varchar(200) not null,
        pass varchar(200) not null,
        email varchar(200) not null,
        created_at datetime,
        updated_at datetime
    );

CREATE TABLE cart (
    id int primary key auto_increment,
    id_account int not null,
    id_product int not null,
    quantity int not null,
    created_at datetime,
    updated_at datetime,
    CONSTRAINT fk_id_account FOREIGN KEY (id_account) references customer(id),
    CONSTRAINT fk_id_product FOREIGN KEY (id_product) references product(id)
);

CREATE TABLE orderlist (
    id int primary key auto_increment,
    id_account int not null,
    total_money float not null,
    full_name varchar(200) not null,
    delivery_address varchar(400) not null,
    phone varchar(14) not null,
    email varchar(50) not null,
    status_order varchar(30) not null,
    created_at datetime,
    updated_at datetime,
    CONSTRAINT fk_id_account1 FOREIGN KEY (id_account) references customer(id)
);
CREATE TABLE order_item (
    id int primary key auto_increment,
    id_order int not null,
    id_product int not null,
    quantity int not null,
    created_at datetime,
    updated_at datetime,
    CONSTRAINT fk_id_product1 FOREIGN KEY (id_product) references product(id),
    CONSTRAINT fk_id_order FOREIGN KEY (id_order) references orderlist(id)
);

    ALTER TABLE category
MODIFY COLUMN name varchar(50) not null;

SELECT
    product.id,
    product.title,
    product.price,
    product.thumnail,
    product.updated_at,
    category.name category_name
FROM
    product
LEFT JOIN category ON product.id_category = category.id;

-- Giới hạn số hàng trả về. Nếu chỉ có một đối số --> đó là số hàng tối đa trả về.
SELECT
    column1,column2,...
FROM
    table
LIMIT offset, count;


--Đếm số lượng bản ghi:
SELECT count(id) FROM category;

select * from orderlist where 1 order by updated_at desc limit 0, 10;