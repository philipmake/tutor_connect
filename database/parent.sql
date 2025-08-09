create table parent (
    id int auto_increment primary key,
    foreign key (parent_id) references users(id) on delete cascade,
    foreign key (payment_id) references payments(id) on delete cascade,
    foreign key (booking_id) refer
);

create table payments (
    id int auto_increment primary key,
    booking_id int,
    amount decimal(8, 2) not null,
    method enum('card', 'bank_transfer', 'cash') not null,
    status enum('pending', 'paid', 'failed') default 'pending',
    payment_date timestamp default current_timestamp,
    foreign key (booking_id) references bookings(id) on delete cascade
);

