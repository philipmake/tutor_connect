create table tutor (
    tutor_id int primary key,
    bio text,
    hourly_rate decimal(8, 2),
    experience_yrs int,
    education varchar(255),
    location varchar(255),
    foreign key (tutor_id) references users(user_id) delete cascade
);

create table availability (
    id int auto_increment primary key,
    tutor_id int,
    day enum('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') not null,
    start_time time not null,
    end_time time not null,
    foreign key (tutor_id) references users(user_id) on delete cascade
);

create table reviews (
    id int auto_increment primary key,
    booking_id int,
    parent_id int,
    tutor_id int,
    rating int check (rating between 1 and 5),
    comment text,
    created_at timestamp default current_timestamp,
    foreign key (booking_id) references bookings(id) on delete cascade,
    foreign key (parent_id) references users(id) on delete cascade,
    foreign key (tutor_id) references users(id) on delete cascade,
);



