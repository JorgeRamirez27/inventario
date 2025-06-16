CREATE TABLE productos (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    precio NUMERIC(10, 2) NOT NULL,
    cantidad INTEGER NOT NULL,
    proveedor VARCHAR(100) NOT NULL
);