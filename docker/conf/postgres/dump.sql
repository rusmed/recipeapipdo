BEGIN;

create table images
(
	id bigserial not null
		constraint images_pkey
			primary key,
	url varchar(255) not null
)
;

create table users
(
	id bigserial not null
		constraint users_pkey
			primary key,
	name varchar(255) not null,
	email varchar(255) not null,
	password varchar(255) not null,
	token varchar(255),
	token_expired timestamp
)
;

create unique index users_email_uindex
	on users (email)
;

create table recipes
(
	id bigserial not null
		constraint recipes_pkey
			primary key,
	title varchar(255) not null,
	body text not null,
	author_id bigint not null
		constraint recipes_users_id_fk
			references users,
	image_id bigint not null
		constraint recipes_images_id_fk
			references images
)
;

COMMIT;