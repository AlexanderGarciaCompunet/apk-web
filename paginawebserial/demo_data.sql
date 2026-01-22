-- =============================================
-- SCRIPT DE DATOS DEMO - Compunet SerialCapture
-- Ejecutar en SQL Server Management Studio
-- =============================================

-- 1. Insertar Company (si no existe)
IF NOT EXISTS (SELECT 1 FROM company WHERE id = 1)
BEGIN
    SET IDENTITY_INSERT company ON;
    INSERT INTO company (id, name, description, license, valid_date, status, created_at, updated_at)
    VALUES (1, 'Compunet', 'Compunet Demo Company', 'DEMO-2026', '2026-12-31', 1, GETDATE(), GETDATE());
    SET IDENTITY_INSERT company OFF;
END
ELSE
BEGIN
    UPDATE company SET name = 'Compunet', description = 'Compunet Demo Company' WHERE id = 1;
END

-- 2. Insertar Customers de demo
IF NOT EXISTS (SELECT 1 FROM customer WHERE code = 'CLI001')
BEGIN
    INSERT INTO customer (code, name, description, location, company_id, status, created_at, updated_at)
    VALUES ('CLI001', 'Cliente Demo 1', 'Cliente de demostración', 'Bogotá', 1, 1, GETDATE(), GETDATE());
END

IF NOT EXISTS (SELECT 1 FROM customer WHERE code = 'CLI002')
BEGIN
    INSERT INTO customer (code, name, description, location, company_id, status, created_at, updated_at)
    VALUES ('CLI002', 'Cliente Demo 2', 'Segundo cliente de demo', 'Medellín', 1, 1, GETDATE(), GETDATE());
END

IF NOT EXISTS (SELECT 1 FROM customer WHERE code = 'CLI003')
BEGIN
    INSERT INTO customer (code, name, description, location, company_id, status, created_at, updated_at)
    VALUES ('CLI003', 'Retail Store SA', 'Cadena de tiendas', 'Cali', 1, 1, GETDATE(), GETDATE());
END

-- 3. Insertar Warehouses de demo (code max 4 caracteres)
IF NOT EXISTS (SELECT 1 FROM warehouse WHERE code = 'WH01')
BEGIN
    INSERT INTO warehouse (code, name, description, company_id, status, created_at, updated_at)
    VALUES ('WH01', 'Almacén Principal', 'Almacén central de Compunet', 1, 1, GETDATE(), GETDATE());
END

IF NOT EXISTS (SELECT 1 FROM warehouse WHERE code = 'WH02')
BEGIN
    INSERT INTO warehouse (code, name, description, company_id, status, created_at, updated_at)
    VALUES ('WH02', 'Almacén Secundario', 'Almacén de respaldo', 1, 1, GETDATE(), GETDATE());
END

-- 4. Actualizar descripción de warehouse existente (cambiar Almaviva por Compunet)
UPDATE warehouse SET description = REPLACE(description, 'Almaviva', 'Compunet') WHERE description LIKE '%Almaviva%';

-- 5. Insertar Stores (Bodegas) de demo
DECLARE @warehouseId INT;
SELECT TOP 1 @warehouseId = id FROM warehouse WHERE code = 'WH01';

IF @warehouseId IS NOT NULL AND NOT EXISTS (SELECT 1 FROM store WHERE code = 'BOD001')
BEGIN
    INSERT INTO store (code, name, description, warehouse_id, status, created_at, updated_at)
    VALUES ('BOD001', 'Bodega Principal', 'Bodega de almacenamiento principal', @warehouseId, 1, GETDATE(), GETDATE());
END

IF @warehouseId IS NOT NULL AND NOT EXISTS (SELECT 1 FROM store WHERE code = 'BOD002')
BEGIN
    INSERT INTO store (code, name, description, warehouse_id, status, created_at, updated_at)
    VALUES ('BOD002', 'Bodega Despachos', 'Bodega para preparación de despachos', @warehouseId, 1, GETDATE(), GETDATE());
END

-- 6. Actualizar cualquier referencia a Almaviva en store
UPDATE store SET description = REPLACE(description, 'Almaviva', 'Compunet') WHERE description LIKE '%Almaviva%';

-- 7. Insertar Items (Materiales) de demo
DECLARE @customerId INT;
SELECT TOP 1 @customerId = id FROM customer WHERE code = 'CLI001';

IF @customerId IS NOT NULL AND NOT EXISTS (SELECT 1 FROM item WHERE code = 'PROD001')
BEGIN
    INSERT INTO item (code, name, description, customer_id, company_id, status, created_at, updated_at)
    VALUES ('PROD001', 'Producto Demo 1', 'Producto serializado de prueba', @customerId, 1, 1, GETDATE(), GETDATE());
END

IF @customerId IS NOT NULL AND NOT EXISTS (SELECT 1 FROM item WHERE code = 'PROD002')
BEGIN
    INSERT INTO item (code, name, description, customer_id, company_id, status, created_at, updated_at)
    VALUES ('PROD002', 'Producto Demo 2', 'Segundo producto de prueba', @customerId, 1, 1, GETDATE(), GETDATE());
END

IF @customerId IS NOT NULL AND NOT EXISTS (SELECT 1 FROM item WHERE code = 'PROD003')
BEGIN
    INSERT INTO item (code, name, description, customer_id, company_id, status, created_at, updated_at)
    VALUES ('PROD003', 'Laptop HP 15', 'Laptop serializada', @customerId, 1, 1, GETDATE(), GETDATE());
END

-- 8. Limpiar cualquier otra referencia a Almaviva en otras tablas
UPDATE company SET name = 'Compunet', description = REPLACE(description, 'Almaviva', 'Compunet') WHERE name = 'Almaviva' OR description LIKE '%Almaviva%';

PRINT 'Datos de demo insertados correctamente';
