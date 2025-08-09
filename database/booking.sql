create table bookings (
    id int auto_increment primary key,
    parent_id int,
    tutor_id int,
    subject_id int,
    session_date date not null,
    start_time time not null,
    end_time time not null,
    status enum('pending', 'confirmed', 'cancelled', 'completed') default 'pending',
    created_at timestamp default current_timestamp,
    foreign key (parent_id) references users(id) on delete cascade,
    foreign key (tutor_id) references users(id) on delete cascade,
    foreign key (subject_id) references subjects(id) on delete cascade,
);

