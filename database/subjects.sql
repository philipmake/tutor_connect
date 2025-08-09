create table subjects(
    id int auto_increment primary key,
    name varchar(100) not null unique
);

create table tutor_subjects(
    tutor_id int,
    subject_id int,
    primary key (tutor_id, subject_id),
    foreign key (tutor_id) references users(id) on delete cascade,
    foreign key (subject_id) references subjects(id) on delete cascade
);


