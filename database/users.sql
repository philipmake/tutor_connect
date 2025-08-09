create table users (
    id int primary key auto_increment,
    role enum('tutor', 'parent') not null,
    fullname varchar(100) not null,
    email varchar(100) unique not null,
    phone varchar(20) unique not null,
    password varchar(255) not null,
    profile_picture varchar(255),
    created_at timestamp default current_timestamp
);
