-- Grocery System Common Queries (Production-Safe Version)
-- Useful queries for common operations
-- MySQL 5.7+ ONLY_FULL_GROUP_BY compatible - all GROUP BY queries explicitly list all non-aggregated columns

-- ========================================
-- USER QUERIES
-- ========================================

-- Get all sellers with their product counts (MySQL strict mode compatible)
SELECT 
    u.id,
    u.name,
    u.email,
    u.created_at,
    COUNT(p.id) AS product_count
FROM users u
LEFT JOIN products p ON u.id = p.user_id
WHERE u.role = 'seller'
GROUP BY u.id, u.name, u.email, u.created_at
ORDER BY product_count DESC;

-- Get all available riders
SELECT 
    id,
    name,
    email,
    username,
    address,
    is_rider_available,
    created_at
FROM users
WHERE role = 'rider' AND is_rider_available = TRUE
ORDER BY created_at DESC;

-- Get user delivery history (buyers)
SELECT 
    d.order_id,
    d.status,
    p.name AS product_name,
    p.category,
    d.quantity,
    (d.quantity * p.price) AS total_price,
    u_seller.name AS seller_name,
    d.estimated_date,
    d.delivered_date
FROM deliveries d
JOIN products p ON d.product_id = p.id
JOIN users u_seller ON d.seller_id = u_seller.id
WHERE d.user_id = ? -- Replace with buyer user_id
ORDER BY d.created_at DESC;

-- ========================================
-- PRODUCT QUERIES
-- ========================================

-- Get products by category with seller info (STRICT GROUP BY compatible)
SELECT 
    p.id,
    p.name,
    p.price,
    p.stock,
    p.category,
    u.id AS seller_id,
    u.name AS seller_name,
    u.email AS seller_email,
    COUNT(pi.id) AS image_count
FROM products p
JOIN users u ON p.user_id = u.id
LEFT JOIN product_images pi ON p.id = pi.product_id
WHERE p.category = ? -- Replace with category
GROUP BY p.id, p.name, p.price, p.stock, p.category, u.id, u.name, u.email
ORDER BY p.created_at DESC;

-- Get low-stock products (warning alert)
SELECT 
    p.id,
    p.name,
    p.stock,
    p.category,
    u.name AS seller_name,
    u.email AS seller_email
FROM products p
JOIN users u ON p.user_id = u.id
WHERE p.stock < 10
ORDER BY p.stock ASC;

-- Search products by name or description (compatible without FULLTEXT index)
SELECT 
    p.id,
    p.name,
    p.price,
    p.stock,
    p.category,
    u.id AS seller_id,
    u.name AS seller_name
FROM products p
JOIN users u ON p.user_id = u.id
WHERE LOWER(p.name) LIKE CONCAT('%', LOWER(?), '%')
   OR LOWER(p.description) LIKE CONCAT('%', LOWER(?), '%')
ORDER BY p.price ASC;

-- Get top-selling products in the last 30 days (STRICT GROUP BY compatible)
SELECT 
    p.id,
    p.name,
    p.category,
    u.id AS seller_id,
    u.name AS seller_name,
    SUM(d.quantity) AS total_sold,
    COUNT(DISTINCT d.user_id) AS buyers,
    (SUM(d.quantity) * p.price) AS revenue
FROM deliveries d
JOIN products p ON d.product_id = p.id
JOIN users u ON p.user_id = u.id
WHERE d.status IN ('delivered', 'out_for_delivery')
    AND d.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY p.id, p.name, p.category, u.id, u.name, p.price
ORDER BY total_sold DESC
LIMIT 10;

-- ========================================
-- CART QUERIES
-- ========================================

-- Get cart items for a user
SELECT 
    c.id AS cart_id,
    ci.id AS cart_item_id,
    p.id,
    p.name,
    p.price,
    p.category,
    ci.quantity,
    (ci.quantity * p.price) AS subtotal,
    u.name AS seller_name
FROM carts c
JOIN cart_items ci ON c.id = ci.cart_id
JOIN products p ON ci.product_id = p.id
JOIN users u ON p.user_id = u.id
WHERE c.user_id = ? -- Replace with user_id
ORDER BY ci.created_at DESC;

-- Calculate cart total
SELECT 
    SUM(ci.quantity * p.price) AS cart_total,
    COUNT(ci.id) AS item_count,
    COUNT(DISTINCT p.user_id) AS seller_count
FROM carts c
JOIN cart_items ci ON c.id = ci.cart_id
JOIN products p ON ci.product_id = p.id
WHERE c.user_id = ?; -- Replace with user_id

-- ========================================
-- DELIVERY & ORDER QUERIES
-- ========================================

-- Get all pending deliveries
SELECT 
    d.order_id,
    d.status,
    u_buyer.name AS buyer_name,
    u_buyer.email AS buyer_email,
    u_seller.name AS seller_name,
    p.name AS product_name,
    d.quantity,
    (d.quantity * p.price) AS amount,
    d.address,
    d.estimated_date,
    u_rider.name AS rider_name
FROM deliveries d
JOIN users u_buyer ON d.user_id = u_buyer.id
JOIN users u_seller ON d.seller_id = u_seller.id
JOIN products p ON d.product_id = p.id
LEFT JOIN users u_rider ON d.rider_id = u_rider.id
WHERE d.status NOT IN ('delivered', 'cancelled')
ORDER BY d.created_at ASC;

-- Get deliveries by status (STRICT GROUP BY compatible)
SELECT 
    d.status,
    COUNT(*) AS count,
    SUM(d.quantity) AS total_items,
    SUM(d.quantity * p.price) AS total_value
FROM deliveries d
JOIN products p ON d.product_id = p.id
GROUP BY d.status
ORDER BY d.status;

-- Get rider assignment history
SELECT 
    da.id,
    da.status,
    d.order_id,
    p.name AS product_name,
    u_rider.name AS rider_name,
    u_assigned.name AS assigned_by,
    d.quantity,
    da.created_at,
    da.updated_at,
    TIMESTAMPDIFF(HOUR, da.created_at, da.updated_at) AS hours_assigned
FROM delivery_assignments da
JOIN deliveries d ON da.delivery_id = d.id
JOIN products p ON d.product_id = p.id
JOIN users u_rider ON da.rider_id = u_rider.id
JOIN users u_assigned ON da.assigned_by = u_assigned.id
WHERE da.rider_id = ? -- Replace with rider_id
ORDER BY da.created_at DESC;

-- Get completed deliveries for a seller
SELECT 
    d.order_id,
    d.status,
    u_buyer.name AS buyer_name,
    u_buyer.email AS buyer_email,
    p.name AS product_name,
    d.quantity,
    (d.quantity * p.price) AS amount,
    d.delivered_date,
    u_rider.name AS rider_name
FROM deliveries d
JOIN users u_buyer ON d.user_id = u_buyer.id
JOIN products p ON d.product_id = p.id
LEFT JOIN users u_rider ON d.rider_id = u_rider.id
WHERE d.seller_id = ? -- Replace with seller_id
    AND d.status = 'delivered'
ORDER BY d.delivered_date DESC;

-- ========================================
-- ANALYTICS & REPORTING QUERIES
-- ========================================

-- Get sales summary by seller (last 30 days) (STRICT GROUP BY compatible)
SELECT 
    u.id,
    u.name,
    COUNT(DISTINCT d.order_id) AS orders,
    SUM(d.quantity) AS items_sold,
    SUM(d.quantity * p.price) AS total_revenue,
    AVG(p.price) AS avg_product_price,
    COUNT(DISTINCT d.user_id) AS unique_buyers
FROM deliveries d
JOIN products p ON d.product_id = p.id
JOIN users u ON d.seller_id = u.id
WHERE d.status IN ('delivered', 'out_for_delivery')
    AND d.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY u.id, u.name
ORDER BY total_revenue DESC;

-- Get buyer spending patterns (last 30 days) (STRICT GROUP BY compatible)
SELECT 
    u.id,
    u.name,
    u.email,
    COUNT(DISTINCT d.order_id) AS orders,
    SUM(d.quantity * p.price) AS total_spent,
    COUNT(DISTINCT d.product_id) AS product_types,
    COUNT(DISTINCT d.seller_id) AS seller_count,
    AVG(d.quantity * p.price) AS avg_order_value
FROM deliveries d
JOIN products p ON d.product_id = p.id
JOIN users u ON d.user_id = u.id
WHERE d.status IN ('delivered', 'out_for_delivery')
    AND d.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY u.id, u.name, u.email
ORDER BY total_spent DESC;

-- Category performance (STRICT GROUP BY compatible)
SELECT 
    p.category,
    COUNT(DISTINCT d.order_id) AS orders,
    SUM(d.quantity) AS units_sold,
    SUM(d.quantity * p.price) AS revenue,
    AVG(p.price) AS avg_price,
    COUNT(DISTINCT d.user_id) AS unique_buyers,
    COUNT(DISTINCT d.seller_id) AS sellers
FROM deliveries d
JOIN products p ON d.product_id = p.id
WHERE d.status IN ('delivered', 'out_for_delivery')
    AND d.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY p.category
ORDER BY revenue DESC;

-- Daily orders summary
SELECT 
    DATE(d.created_at) AS date,
    COUNT(DISTINCT d.order_id) AS orders,
    SUM(d.quantity) AS items,
    SUM(d.quantity * p.price) AS daily_revenue,
    COUNT(DISTINCT d.user_id) AS buyers,
    COUNT(DISTINCT d.seller_id) AS sellers
FROM deliveries d
JOIN products p ON d.product_id = p.id
WHERE d.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY DATE(d.created_at)
ORDER BY date DESC;

-- ========================================
-- DATA QUALITY CHECKS
-- ========================================

-- Check for orphaned cart items
SELECT 
    ci.id,
    ci.cart_id,
    ci.product_id,
    'Missing cart' AS issue
FROM cart_items ci
WHERE NOT EXISTS (SELECT 1 FROM carts c WHERE c.id = ci.cart_id)
UNION ALL
SELECT 
    ci.id,
    ci.cart_id,
    ci.product_id,
    'Missing product' AS issue
FROM cart_items ci
WHERE NOT EXISTS (SELECT 1 FROM products p WHERE p.id = ci.product_id);

-- Check for unassigned deliveries
SELECT 
    d.id,
    d.order_id,
    d.status,
    d.created_at,
    d.rider_id,
    TIMESTAMPDIFF(HOUR, d.created_at, NOW()) AS hours_since_creation
FROM deliveries d
WHERE d.status = 'processing'
    AND d.rider_id IS NULL
    AND d.created_at < DATE_SUB(NOW(), INTERVAL 24 HOUR)
ORDER BY d.created_at ASC;
