--PISCINA

--tabla usuarios
drop table if exists usuarios cascade;
create table usuarios
(
  id          bigserial        constraint pk_usuarios primary key,
  dni         varchar(9)       constraint uq_usuarios_dni unique,
  nombre      varchar(100)     not null,
  contrasena  varchar(32)      not null
);



--tabla reservas
drop table if exists reservas cascade;
create table reservas
(
  id          bigserial        constraint pk_reservas primary key,
  dia         char             constraint ck_dia_valido check (dia in('l','m','x','j','v')),
  hora        numeric(2)       not null constraint ck_hora_valida check(hora between 10 AND 20),
  id_usuario  bigint           not null constraint fk_reservas_usuarios references usuarios (id)
);