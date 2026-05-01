-- ========================================================
-- USERS
-- ========================================================

INSERT INTO users (role, first_name, last_name, email, password_hash, phone)
VALUES
('brand_owner', 'Omar', 'Nagy', 'owner@harbaa.com', 'pass123', '01000000001'),
('brand_owner', 'Marwan', 'Adel', 'owner@cielo.com', 'pass123', '01000000002'),
('brand_owner', 'Lina', 'Fathy', 'owner@27.com', 'pass123', '01000000003'),

('admin', 'Admin', 'User', 'admin@dolaby.com', 'pass123', '01099999999'),

('customer', 'Ahmed', 'Yasser', 'ahmed@dolaby.com', 'pass123', '0101000000'),
('customer', 'Sara', 'Ali', 'sara@dolaby.com', 'pass123', '0102000000'),
('customer', 'Mostafa', 'Hassan', 'm.hassan@dolaby.com', 'pass123', '0103000000');

-- ========================================================
-- BRANDS (matches schema sample)
-- ========================================================

INSERT INTO brands (brand_name, brand_logo, description) VALUES
('Harbaa', 'images/Harbaa2.png', 'Premium clothing brand'),
('Zedzee', 'images/Zedzee.png', 'Modern streetwear'),
('Spiral', 'images/spiral.png', 'Contemporary fashion'),
('Cielo', 'images/Cielo.png', 'Quality denim and basics'),
('TwentySeven', 'images/TwentySeven.png', 'Urban fashion'),
('Asili', 'images/Asili.png', 'Authentic designs'),
('Mavin', 'images/Mavin.png', 'Fashion forward');

-- ========================================================
-- CATEGORIES (matches schema sample)
-- ========================================================

INSERT INTO categories (category_name, description) VALUES
('Hoodies', 'Comfortable hooded sweatshirts'),
('Jackets', 'Outerwear jackets'),
('Sweaters', 'Warm sweaters'),
('Sets', 'Matching sets'),
('Denim', 'Denim clothing'),
('Crewnecks', 'Crewneck sweaters'),
('Jeans', 'Denim jeans'),
('T-shirts', 'Casual t-shirts'),
('Pyjamas', 'Sleepwear'),
('Kids wear', 'Children clothing'),
('Knit wear', 'Knit clothing'),
('Sports wear', 'Athletic wear'),
('Swim wear', 'Swimwear'),
('Casual', 'Casual clothing'),
('Street wear', 'Street style clothing'),
('Basics', 'Basic essentials');

-- ========================================================
-- PRODUCTS (30 highly realistic items)
-- ========================================================

INSERT INTO products (brand_id, category_id, product_name, description, price, original_price, stock_quantity, in_stock, on_sale, is_new, image_url)
VALUES
-- HARBAA
(1, 1, 'Harbaa Black Hoodie', 'Premium cotton oversized hoodie.', 699, 899, 45, 1, 1, 1, '/assets/images/shop/harbaa-black-hoodie.jpg'),
(1, 8, 'Harbaa Oversized Tee White', 'Minimal oversized tee.', 349, NULL, 70, 1, 0, 1, '/assets/images/shop/harbaa-tee-white.jpg'),
(1, 8, 'Harbaa Oversized Tee Black', 'Soft-touch cotton tee.', 349, NULL, 60, 1, 0, 1, '/assets/images/shop/harbaa-tee-black.jpg'),
(1, 1, 'Harbaa Pink Hoodie', 'Limited edition pink hoodie.', 749, 899, 25, 1, 0, 1, '/assets/images/shop/harbaa-pink.jpg'),

-- CIELO
(4, 2, 'Cielo Denim Jacket', 'High-quality washed denim jacket.', 999, 1199, 27, 1, 1, 0, '/assets/images/shop/cielo-denim.jpg'),
(4, 7, 'Cielo Blue Jeans', 'Slim fit premium denim.', 559, 699, 40, 1, 0, 0, '/assets/images/shop/cielo-jeans.jpg'),
(4, 11, 'Cielo Knit Sweater', 'Premium knit winter sweater.', 499, NULL, 33, 1, 0, 1, '/assets/images/shop/cielo-knit.jpg'),
(4, 1, 'Cielo Soft Hoodie', 'Ultra-soft fleece hoodie.', 699, NULL, 22, 1, 0, 1, '/assets/images/shop/cielo-soft-hoodie.jpg'),

-- TWENTY SEVEN
(5, 6, 'TwentySeven Crewneck Grey', 'Minimalist grey crewneck.', 399, NULL, 50, 1, 0, 0, '/assets/images/shop/27-crewneck.jpg'),
(5, 8, 'TwentySeven Basic Tee', 'Everyday essential.', 189, NULL, 90, 1, 0, 1, '/assets/images/shop/27-tee.jpg'),
(5, 4, 'TwentySeven Joggers Set', 'Matching joggers set.', 499, 599, 37, 1, 1, 0, '/assets/images/shop/27-joggers.jpg'),

-- ZEDZEE
(2, 1, 'ZedZee Urban Hoodie', 'Streetwear inspired hoodie.', 599, NULL, 33, 1, 0, 1, '/assets/images/shop/zedzee-hoodie.jpg'),
(2, 15, 'ZedZee Streetwear Tee', 'Graphic street tee.', 259, NULL, 70, 1, 0, 1, '/assets/images/shop/zedzee-tee.jpg'),
(2, 2, 'ZedZee Puffer Jacket', 'Warm winter puffer.', 1099, 1299, 19, 1, 1, 0, '/assets/images/shop/zedzee-puffer.jpg'),

-- SPIRAL
(3, 11, 'Spiral Knitwear Top', 'Handmade knit design.', 449, NULL, 32, 1, 0, 1, '/assets/images/shop/spiral-knit.jpg'),
(3, 1, 'Spiral Hoodie Black', 'Classic black hoodie.', 649, NULL, 26, 1, 0, 1, '/assets/images/shop/spiral-hoodie.jpg'),

-- ASILI
(6, 8, 'Asili Tribal Tee', 'Ethnic print premium tee.', 299, NULL, 50, 1, 0, 1, '/assets/images/shop/asili-tee.jpg'),
(6, 1, 'Asili Tribal Hoodie', 'Cultural inspired hoodie.', 559, NULL, 30, 1, 0, 1, '/assets/images/shop/asili-hoodie.jpg'),

-- MAVIN
(7, 2, 'Mavin Zip Jacket', 'Sleek and modern jacket.', 799, NULL, 28, 1, 0, 1, '/assets/images/shop/mavin-jacket.jpg'),
(7, 6, 'Mavin Crewneck Beige', 'Soft beige crewneck.', 379, NULL, 42, 1, 0, 1, '/assets/images/shop/mavin-crewneck.jpg');

-- ========================================================
-- PRODUCT SIZES for all items
-- ========================================================

INSERT INTO product_sizes (product_id, size, stock_quantity) VALUES
-- Sizes for first 20 products
(1, 'S', 10),(1, 'M', 15),(1, 'L', 20),(1, 'XL', 10),
(2, 'S', 25),(2, 'M', 25),(2, 'L', 20),
(3, 'S', 20),(3, 'M', 20),(3, 'L', 20),
(4, 'M', 10),(4, 'L', 15),(4, 'XL', 10),

(5, 'M', 15),(5, 'L', 12),(5, 'XL', 5),
(6, 'S', 30),(6, 'M', 30),
(7, 'M', 14),(7, 'L', 19),
(8, 'M', 12),(8, 'L', 10),

(9, 'S', 20),(9, 'M', 20),
(10, 'S', 40),(10, 'M', 30),(10, 'L', 20),
(11, 'M', 20),(11, 'L', 17),

(12, 'M', 10),(12, 'L', 14),
(13, 'S', 25),(13, 'M', 30),
(14, 'M', 9),(14, 'L', 18),

(15, 'M', 12),(15, 'L', 14),
(16, 'M', 18),(16, 'L', 10),
(17, 'M', 20),(17, 'L', 22),
(18, 'M', 14),(18, 'L', 12),

(19, 'M', 20),(19, 'L', 22),
(20, 'M', 14),(20, 'L', 18);

-- ========================================================
-- ADDRESSES
-- ========================================================

INSERT INTO addresses (user_id, address_type, area, building_name, apartment_number, floor, street, phone, is_default)
VALUES
(5, 'Home', 'Nasr City', 'Building 12', '14', '5', 'Street 10', '0101000000', 1),
(6, 'Home', 'Maadi', 'Building 9', '8', '3', 'Street 15', '0102000000', 1);

-- ========================================================
-- PAYMENT METHODS
-- ========================================================

INSERT INTO payment_methods (user_id, card_type, last_four_digits, cardholder_name, expiry_month, expiry_year, is_default)
VALUES
(5, 'VISA', '1234', 'Ahmed Yasser', 12, 2027, 1),
(6, 'MasterCard', '9876', 'Sara Ali', 3, 2026, 1);

-- ========================================================
-- REVIEWS
-- ========================================================

INSERT INTO product_reviews (product_id, user_id, rating, review_content)
VALUES
-- Harbaa Black Hoodie (Product 1) - 5 reviews
(1, 5, 5, 'Absolutely love this hoodie! The quality is amazing and it\'s so comfortable. The black color is perfect and it fits true to size. Highly recommend!'),
(1, 6, 5, 'Best hoodie I\'ve ever owned! The material is premium and the fit is perfect. Worth every penny.'),
(1, 7, 4, 'Great hoodie, very soft material. The only reason I\'m giving it 4 stars is because it\'s a bit thinner than I expected, but still great quality.'),
(1, 5, 5, 'Perfect fit and amazing quality! The color is exactly as shown. I\'ve washed it multiple times and it still looks brand new.'),
(1, 6, 5, 'Excellent purchase! The hoodie is warm, comfortable, and stylish. Shipping was fast too.'),

-- Harbaa Oversized Tee White (Product 2) - 4 reviews
(2, 5, 5, 'Love the oversized fit! Very comfortable and the white color is perfect. Great for casual wear.'),
(2, 7, 4, 'Nice tee, good quality fabric. The oversized fit is exactly what I wanted.'),
(2, 6, 5, 'Perfect everyday tee! Soft, comfortable, and looks great. Highly recommend!'),
(2, 5, 4, 'Good quality tee, fits well. The material is soft but could be a bit thicker.'),

-- Harbaa Oversized Tee Black (Product 3) - 3 reviews
(3, 6, 5, 'Amazing tee! The black color is rich and the fit is perfect. Very comfortable.'),
(3, 7, 5, 'Great quality and perfect fit. The black color doesn\'t fade after washing. Love it!'),
(3, 5, 4, 'Nice tee, good quality. The oversized fit is comfortable but maybe a bit too oversized for my taste.'),

-- Harbaa Pink Hoodie (Product 4) - 4 reviews
(4, 7, 5, 'Beautiful pink color! The hoodie is super soft and comfortable. Perfect for spring.'),
(4, 5, 5, 'Love this pink hoodie! The color is vibrant and the quality is excellent.'),
(4, 6, 4, 'Nice hoodie, good quality. The pink color is pretty but slightly different from the photo.'),
(4, 7, 5, 'Perfect fit and amazing color! Very happy with this purchase.'),

-- Cielo Denim Jacket (Product 5) - 5 reviews
(5, 6, 4, 'Fits well and looks good! The denim quality is nice, though it could be softer.'),
(5, 5, 5, 'Excellent denim jacket! Great quality and perfect fit. Very stylish.'),
(5, 7, 4, 'Good jacket, nice design. The denim is a bit stiff at first but softens after wearing.'),
(5, 6, 5, 'Love this jacket! The washed denim look is perfect and it fits great.'),
(5, 5, 4, 'Nice jacket overall. Good quality but the sizing runs a bit small.'),

-- Cielo Blue Jeans (Product 6) - 3 reviews
(6, 7, 5, 'Perfect fit! These jeans are comfortable and look great. The slim fit is exactly what I wanted.'),
(6, 5, 4, 'Good jeans, nice quality. The fit is good but could be a bit more stretchy.'),
(6, 6, 5, 'Excellent jeans! Great quality denim and perfect fit. Highly recommend!'),

-- Cielo Knit Sweater (Product 7) - 4 reviews
(7, 5, 5, 'Beautiful knit sweater! Very warm and comfortable. Perfect for winter.'),
(7, 6, 4, 'Nice sweater, good quality. The knit is soft but could be thicker.'),
(7, 7, 5, 'Love this sweater! The quality is excellent and it\'s very warm.'),
(7, 5, 4, 'Good sweater overall. Nice design but runs a bit large.'),

-- Cielo Soft Hoodie (Product 8) - 3 reviews
(8, 6, 5, 'Super soft hoodie! Very comfortable and warm. Perfect for lounging.'),
(8, 7, 5, 'Amazing quality! The fleece is incredibly soft. Best hoodie I\'ve bought.'),
(8, 5, 4, 'Nice hoodie, very soft material. Good quality but could be warmer.'),

-- TwentySeven Crewneck Grey (Product 9) - 3 reviews
(9, 7, 5, 'Perfect crewneck! Minimalist design and great quality. Very comfortable.'),
(9, 5, 4, 'Nice crewneck, good quality. The grey color is nice but could be darker.'),
(9, 6, 5, 'Excellent quality! Simple and stylish. Perfect for everyday wear.'),

-- TwentySeven Basic Tee (Product 10) - 5 reviews
(10, 5, 5, 'Super comfortable! Great basic tee at a good price. Perfect for everyday wear.'),
(10, 6, 5, 'Love this tee! Soft fabric and perfect fit. Great value for money.'),
(10, 7, 4, 'Good basic tee. Quality is decent but could be thicker.'),
(10, 5, 5, 'Perfect everyday essential! Comfortable and durable. Highly recommend!'),
(10, 6, 4, 'Nice tee overall. Good quality but the fabric could be softer.'),

-- TwentySeven Joggers Set (Product 11) - 4 reviews
(11, 7, 5, 'Amazing set! Very comfortable and stylish. Perfect for casual wear.'),
(11, 5, 4, 'Nice set, good quality. The matching pieces look great together.'),
(11, 6, 5, 'Love this set! Comfortable and perfect fit. Great for lounging.'),
(11, 7, 4, 'Good set overall. Quality is nice but could be more durable.'),

-- ZedZee Urban Hoodie (Product 12) - 4 reviews
(12, 5, 5, 'Great streetwear hoodie! Love the design and quality. Very comfortable.'),
(12, 6, 4, 'Nice hoodie, good streetwear style. Quality is decent.'),
(12, 7, 5, 'Perfect hoodie! Great design and excellent quality. Very happy!'),
(12, 5, 4, 'Good hoodie overall. Nice design but could be warmer.'),

-- ZedZee Streetwear Tee (Product 13) - 3 reviews
(13, 6, 5, 'Awesome graphic tee! Great design and good quality. Love it!'),
(13, 7, 4, 'Nice tee, cool graphics. Quality is good but could be better.'),
(13, 5, 5, 'Perfect streetwear tee! Great design and comfortable fit.'),

-- ZedZee Puffer Jacket (Product 14) - 4 reviews
(14, 7, 5, 'Very warm jacket! Perfect for winter. Great quality and stylish design.'),
(14, 5, 4, 'Good puffer jacket. Warm but could be lighter weight.'),
(14, 6, 5, 'Excellent jacket! Very warm and comfortable. Perfect for cold weather.'),
(14, 7, 4, 'Nice jacket overall. Good quality but sizing runs small.'),

-- Spiral Knitwear Top (Product 15) - 3 reviews
(15, 5, 5, 'Beautiful handmade design! The knit is unique and high quality. Love it!'),
(15, 6, 4, 'Nice knit top. Good quality but could be softer.'),
(15, 7, 5, 'Amazing design! The handmade knit is beautiful and well-made.'),

-- Spiral Hoodie Black (Product 16) - 4 reviews
(16, 6, 5, 'Classic black hoodie! Great quality and perfect fit. Very comfortable.'),
(16, 7, 4, 'Nice hoodie, good quality. The black color is rich and doesn\'t fade.'),
(16, 5, 5, 'Perfect hoodie! Classic design and excellent quality. Highly recommend!'),
(16, 6, 4, 'Good hoodie overall. Quality is nice but could be thicker.'),

-- Asili Tribal Tee (Product 17) - 3 reviews
(17, 7, 5, 'Beautiful ethnic print! Unique design and good quality. Love the tribal pattern.'),
(17, 5, 4, 'Nice tee with interesting design. Quality is good but could be better.'),
(17, 6, 5, 'Amazing design! The tribal print is beautiful and the quality is excellent.'),

-- Asili Tribal Hoodie (Product 18) - 4 reviews
(18, 5, 5, 'Love the cultural design! Great hoodie with unique patterns. Very comfortable.'),
(18, 6, 4, 'Nice hoodie with interesting design. Quality is good.'),
(18, 7, 5, 'Beautiful design! The tribal patterns are unique and well-made.'),
(18, 5, 4, 'Good hoodie overall. Nice design but could be warmer.'),

-- Mavin Zip Jacket (Product 19) - 3 reviews
(19, 6, 5, 'Sleek and modern! Great jacket with excellent quality. Perfect fit.'),
(19, 7, 4, 'Nice jacket, modern design. Quality is good but could be better.'),
(19, 5, 5, 'Perfect jacket! Sleek design and great quality. Very stylish.'),

-- Mavin Crewneck Beige (Product 20) - 4 reviews
(20, 7, 5, 'Beautiful beige color! Soft and comfortable. Perfect for everyday wear.'),
(20, 5, 4, 'Nice crewneck, good quality. The beige color is nice but could be darker.'),
(20, 6, 5, 'Excellent quality! Soft fabric and perfect fit. Love the beige color!'),
(20, 7, 4, 'Good crewneck overall. Quality is nice but could be thicker.'),

-- Additional reviews for more variety and coverage
-- Harbaa Black Hoodie (Product 1) - Additional 3 reviews
(1, 7, 5, 'Second purchase! Love this hoodie so much I bought another one. Quality is consistent and excellent.'),
(1, 5, 3, 'Decent hoodie but the material feels a bit cheap for the price. Still comfortable though.'),
(1, 6, 5, 'Perfect for winter! Very warm and the quality is outstanding. Highly recommend to everyone.'),

-- Harbaa Oversized Tee White (Product 2) - Additional 2 reviews
(2, 6, 3, 'The tee is okay but the white color gets dirty easily. Quality is decent.'),
(2, 7, 5, 'Perfect oversized fit! Love how comfortable it is. Great for layering.'),

-- Harbaa Oversized Tee Black (Product 3) - Additional 2 reviews
(3, 5, 5, 'Best black tee I own! The color stays rich and doesn\'t fade. Perfect fit.'),
(3, 6, 4, 'Nice tee, good quality. The oversized fit is exactly as described.'),

-- Harbaa Pink Hoodie (Product 4) - Additional 2 reviews
(4, 5, 5, 'Gorgeous color! The pink is vibrant and the hoodie is super soft. Love it!'),
(4, 6, 3, 'Color is nice but the hoodie shrunk a bit after first wash. Still wearable though.'),

-- Cielo Denim Jacket (Product 5) - Additional 2 reviews
(5, 7, 5, 'Amazing jacket! The denim quality is top-notch and it fits perfectly.'),
(5, 5, 2, 'The jacket is okay but the sizing is way off. Had to return and get a different size.'),

-- Cielo Blue Jeans (Product 6) - Additional 3 reviews
(6, 6, 5, 'Perfect jeans! Great fit and comfortable. The denim quality is excellent.'),
(6, 7, 4, 'Nice jeans, good quality. The slim fit is perfect for my style.'),
(6, 5, 3, 'Decent jeans but they stretch out a bit after wearing. Still good quality though.'),

-- Cielo Knit Sweater (Product 7) - Additional 2 reviews
(7, 6, 5, 'Beautiful sweater! Very warm and the knit pattern is lovely.'),
(7, 5, 4, 'Nice sweater, good quality. Perfect for cold weather.'),

-- Cielo Soft Hoodie (Product 8) - Additional 3 reviews
(8, 5, 5, 'Incredibly soft! This is the softest hoodie I\'ve ever owned. Worth every penny.'),
(8, 6, 4, 'Very soft and comfortable. Great for lounging around the house.'),
(8, 7, 5, 'Amazing quality! The fleece is so soft and warm. Perfect hoodie!'),

-- TwentySeven Crewneck Grey (Product 9) - Additional 3 reviews
(9, 5, 5, 'Perfect crewneck! Simple, stylish, and comfortable. Great for everyday wear.'),
(9, 6, 4, 'Nice crewneck, good quality. The grey color is versatile.'),
(9, 7, 3, 'Decent crewneck but the fabric could be softer. Still comfortable though.'),

-- TwentySeven Basic Tee (Product 10) - Additional 2 reviews
(10, 7, 5, 'Best basic tee! Great quality for the price. Will definitely buy more.'),
(10, 5, 4, 'Good basic tee, comfortable and durable. Great value.'),

-- TwentySeven Joggers Set (Product 11) - Additional 2 reviews
(11, 6, 5, 'Love this set! Very comfortable and stylish. Perfect for casual days.'),
(11, 5, 3, 'The set is okay but the material could be better quality.'),

-- ZedZee Urban Hoodie (Product 12) - Additional 3 reviews
(12, 6, 5, 'Great streetwear style! Love the design and the quality is excellent.'),
(12, 7, 4, 'Nice hoodie with cool streetwear vibes. Good quality overall.'),
(12, 5, 2, 'The hoodie is okay but the design wasn\'t what I expected from the photos.'),

-- ZedZee Streetwear Tee (Product 13) - Additional 3 reviews
(13, 5, 5, 'Awesome graphic! The design is cool and the quality is great.'),
(13, 6, 4, 'Nice tee with cool graphics. Good quality fabric.'),
(13, 7, 3, 'The graphics are nice but the fabric could be thicker.'),

-- ZedZee Puffer Jacket (Product 14) - Additional 2 reviews
(14, 5, 5, 'Very warm and stylish! Perfect for winter weather. Great quality.'),
(14, 6, 4, 'Good puffer jacket. Warm and comfortable. Sizing is accurate.'),

-- Spiral Knitwear Top (Product 15) - Additional 3 reviews
(15, 6, 5, 'Beautiful handmade design! The knit is unique and well-crafted.'),
(15, 5, 4, 'Nice knit top with interesting design. Good quality.'),
(15, 7, 3, 'The design is nice but the knit feels a bit rough. Still wearable.'),

-- Spiral Hoodie Black (Product 16) - Additional 2 reviews
(16, 7, 5, 'Classic and comfortable! Great quality black hoodie.'),
(16, 5, 4, 'Nice hoodie, good quality. The black color is rich and doesn\'t fade.'),

-- Asili Tribal Tee (Product 17) - Additional 3 reviews
(17, 5, 5, 'Beautiful tribal design! Unique and well-made. Love the ethnic patterns.'),
(17, 6, 4, 'Nice tee with interesting tribal patterns. Good quality.'),
(17, 7, 3, 'The design is unique but the fabric could be softer.'),

-- Asili Tribal Hoodie (Product 18) - Additional 2 reviews
(18, 6, 5, 'Love the cultural design! The tribal patterns are beautiful and unique.'),
(18, 7, 4, 'Nice hoodie with interesting design. Good quality overall.'),

-- Mavin Zip Jacket (Product 19) - Additional 3 reviews
(19, 7, 5, 'Sleek and modern design! Great quality and perfect fit.'),
(19, 5, 4, 'Nice jacket, modern style. Good quality but could be warmer.'),
(19, 6, 3, 'The jacket is okay but the zipper feels a bit cheap. Still functional.'),

-- Mavin Crewneck Beige (Product 20) - Additional 3 reviews
(20, 5, 5, 'Beautiful beige color! Soft fabric and perfect fit. Love it!'),
(20, 6, 4, 'Nice crewneck, good quality. The beige color is versatile.'),
(20, 5, 3, 'Decent crewneck but the color is lighter than expected. Still nice though.'),

-- EXTENSIVE REVIEWS - Adding 10-15 more reviews per product for comprehensive coverage
-- Harbaa Black Hoodie (Product 1) - Additional 10 reviews
(1, 7, 5, 'This is my third purchase! Can\'t get enough of this hoodie. Quality is consistently excellent.'),
(1, 5, 5, 'Perfect for all seasons! The material is breathable yet warm. Love the fit and style.'),
(1, 6, 4, 'Great hoodie overall. The quality is good but I wish it came in more colors.'),
(1, 7, 5, 'Best purchase I\'ve made this year! The hoodie is comfortable, stylish, and durable.'),
(1, 5, 4, 'Nice hoodie, good quality. The black color is rich and doesn\'t fade easily.'),
(1, 6, 5, 'Excellent value for money! The quality exceeds expectations. Highly recommend!'),
(1, 7, 4, 'Very comfortable hoodie. The fit is perfect and the material is soft.'),
(1, 5, 5, 'Love everything about this hoodie! The design, quality, and comfort are all top-notch.'),
(1, 6, 4, 'Good hoodie for the price. Quality is decent and it looks great.'),
(1, 7, 5, 'Amazing hoodie! I get compliments every time I wear it. Worth every penny.'),

-- Harbaa Oversized Tee White (Product 2) - Additional 10 reviews
(2, 6, 5, 'Perfect oversized tee! I\'ve bought multiple colors. Great quality and fit.'),
(2, 7, 4, 'Nice tee, comfortable fit. The white color stays bright after multiple washes.'),
(2, 5, 5, 'Love this tee! It\'s become my go-to casual shirt. Quality is excellent.'),
(2, 6, 4, 'Good basic tee. The oversized fit is exactly what I wanted.'),
(2, 7, 5, 'Excellent quality! Soft fabric and perfect fit. Highly recommend!'),
(2, 5, 4, 'Nice tee overall. Good quality but could be slightly thicker.'),
(2, 6, 5, 'Best white tee I own! The quality is outstanding and it fits perfectly.'),
(2, 7, 4, 'Comfortable and stylish. Great for everyday wear.'),
(2, 5, 5, 'Perfect tee! I wear it all the time. Quality is top-notch.'),
(2, 6, 4, 'Good value for money. Nice quality and comfortable fit.'),

-- Harbaa Oversized Tee Black (Product 3) - Additional 10 reviews
(3, 5, 5, 'Amazing black tee! The color is rich and doesn\'t fade. Perfect fit.'),
(3, 6, 4, 'Nice tee, good quality. The black color stays vibrant.'),
(3, 7, 5, 'Love this tee! Great quality and perfect oversized fit.'),
(3, 5, 4, 'Good quality tee. Comfortable and stylish.'),
(3, 6, 5, 'Excellent tee! The black color is perfect and the quality is great.'),
(3, 7, 4, 'Nice oversized fit. Good quality fabric.'),
(3, 5, 5, 'Perfect black tee! I\'ve bought several. Quality is consistent.'),
(3, 6, 4, 'Good tee overall. Quality is decent and it looks great.'),
(3, 7, 5, 'Amazing quality! Soft fabric and perfect fit. Highly recommend!'),
(3, 5, 4, 'Nice tee, comfortable fit. Good value for money.'),

-- Harbaa Pink Hoodie (Product 4) - Additional 10 reviews
(4, 6, 5, 'Gorgeous pink color! The hoodie is beautiful and comfortable.'),
(4, 5, 4, 'Nice pink hoodie. The color is vibrant and the quality is good.'),
(4, 7, 5, 'Love this pink hoodie! It\'s my favorite piece in my wardrobe.'),
(4, 6, 4, 'Beautiful color! Good quality hoodie.'),
(4, 5, 5, 'Perfect pink hoodie! The color is exactly as shown and quality is excellent.'),
(4, 7, 4, 'Nice hoodie, good quality. The pink color is pretty.'),
(4, 6, 5, 'Amazing hoodie! The pink color is beautiful and the quality is top-notch.'),
(4, 5, 4, 'Good quality hoodie. The pink color is nice but slightly different from photo.'),
(4, 7, 5, 'Love everything about this hoodie! Color, quality, and fit are all perfect.'),
(4, 6, 4, 'Nice hoodie overall. Good quality and comfortable.'),

-- Cielo Denim Jacket (Product 5) - Additional 10 reviews
(5, 7, 5, 'Amazing denim jacket! Great quality and perfect fit.'),
(5, 5, 4, 'Nice jacket, good quality. The denim is soft and comfortable.'),
(5, 6, 5, 'Excellent jacket! The quality is outstanding and it fits perfectly.'),
(5, 7, 4, 'Good denim jacket. Quality is nice and it looks great.'),
(5, 5, 5, 'Perfect jacket! I wear it all the time. Quality is excellent.'),
(5, 6, 4, 'Nice jacket overall. Good quality but sizing runs a bit small.'),
(5, 7, 5, 'Love this jacket! The denim quality is top-notch.'),
(5, 5, 4, 'Good jacket, nice design. Quality is decent.'),
(5, 6, 5, 'Excellent quality! The jacket is well-made and stylish.'),
(5, 7, 4, 'Nice jacket. Good quality and comfortable fit.'),

-- Cielo Blue Jeans (Product 6) - Additional 10 reviews
(6, 5, 5, 'Perfect jeans! Great fit and excellent quality.'),
(6, 6, 4, 'Nice jeans, good quality. The slim fit is perfect.'),
(6, 7, 5, 'Excellent jeans! Quality is outstanding and they fit perfectly.'),
(6, 5, 4, 'Good jeans overall. Quality is nice and they look great.'),
(6, 6, 5, 'Love these jeans! They\'re comfortable and stylish.'),
(6, 7, 4, 'Nice jeans, good quality. The fit is good.'),
(6, 5, 5, 'Perfect fit! These jeans are amazing quality.'),
(6, 6, 4, 'Good jeans. Quality is decent and they look nice.'),
(6, 7, 5, 'Excellent jeans! Great quality denim and perfect fit.'),
(6, 5, 4, 'Nice jeans overall. Quality is good.'),

-- Cielo Knit Sweater (Product 7) - Additional 10 reviews
(7, 6, 5, 'Beautiful sweater! Very warm and comfortable.'),
(7, 7, 4, 'Nice sweater, good quality. The knit is soft.'),
(7, 5, 5, 'Perfect sweater! Quality is excellent and it\'s very warm.'),
(7, 6, 4, 'Good sweater overall. Quality is nice and it looks great.'),
(7, 7, 5, 'Love this sweater! It\'s warm and stylish.'),
(7, 5, 4, 'Nice sweater, good quality. Perfect for winter.'),
(7, 6, 5, 'Excellent quality! The sweater is well-made and comfortable.'),
(7, 7, 4, 'Good sweater. Quality is decent and it\'s warm.'),
(7, 5, 5, 'Perfect for cold weather! Quality is outstanding.'),
(7, 6, 4, 'Nice sweater overall. Good quality and comfortable.'),

-- Cielo Soft Hoodie (Product 8) - Additional 10 reviews
(8, 7, 5, 'Incredibly soft! This is the softest hoodie I\'ve ever owned.'),
(8, 5, 4, 'Nice hoodie, very soft material. Good quality.'),
(8, 6, 5, 'Amazing hoodie! The softness is incredible.'),
(8, 7, 4, 'Good hoodie, soft fabric. Quality is nice.'),
(8, 5, 5, 'Perfect hoodie! So soft and comfortable.'),
(8, 6, 4, 'Nice hoodie overall. Very soft and warm.'),
(8, 7, 5, 'Excellent quality! The softness is amazing.'),
(8, 5, 4, 'Good hoodie. Soft material and comfortable.'),
(8, 6, 5, 'Love this hoodie! It\'s incredibly soft.'),
(8, 7, 4, 'Nice hoodie, good quality. Very soft fabric.'),

-- TwentySeven Crewneck Grey (Product 9) - Additional 10 reviews
(9, 5, 5, 'Perfect crewneck! Simple and stylish.'),
(9, 6, 4, 'Nice crewneck, good quality. The grey color is versatile.'),
(9, 7, 5, 'Excellent quality! Simple design and perfect fit.'),
(9, 5, 4, 'Good crewneck overall. Quality is nice.'),
(9, 6, 5, 'Love this crewneck! It\'s my favorite piece.'),
(9, 7, 4, 'Nice crewneck, good quality. Perfect for everyday wear.'),
(9, 5, 5, 'Perfect for casual wear! Quality is excellent.'),
(9, 6, 4, 'Good crewneck. Quality is decent and it looks great.'),
(9, 7, 5, 'Excellent quality! Simple and stylish design.'),
(9, 5, 4, 'Nice crewneck overall. Good quality and comfortable.'),

-- TwentySeven Basic Tee (Product 10) - Additional 10 reviews
(10, 7, 5, 'Best basic tee! Great quality for the price.'),
(10, 5, 4, 'Nice tee, good quality. Comfortable and durable.'),
(10, 6, 5, 'Excellent tee! Quality is outstanding.'),
(10, 7, 4, 'Good basic tee. Quality is nice and it\'s comfortable.'),
(10, 5, 5, 'Perfect everyday tee! I\'ve bought multiple.'),
(10, 6, 4, 'Nice tee overall. Good quality and good value.'),
(10, 7, 5, 'Love this tee! Quality is excellent.'),
(10, 5, 4, 'Good tee, nice quality. Comfortable fit.'),
(10, 6, 5, 'Excellent value! Great quality basic tee.'),
(10, 7, 4, 'Nice tee. Quality is decent and it looks good.'),

-- TwentySeven Joggers Set (Product 11) - Additional 10 reviews
(11, 5, 5, 'Perfect set! Very comfortable and stylish.'),
(11, 6, 4, 'Nice set, good quality. The matching pieces look great.'),
(11, 7, 5, 'Excellent quality! Comfortable and perfect fit.'),
(11, 5, 4, 'Good set overall. Quality is nice.'),
(11, 6, 5, 'Love this set! It\'s my go-to for lounging.'),
(11, 7, 4, 'Nice set, good quality. Comfortable and stylish.'),
(11, 5, 5, 'Perfect for casual wear! Quality is excellent.'),
(11, 6, 4, 'Good set. Quality is decent and it looks great.'),
(11, 7, 5, 'Excellent quality! Very comfortable set.'),
(11, 5, 4, 'Nice set overall. Good quality and comfortable.'),

-- ZedZee Urban Hoodie (Product 12) - Additional 10 reviews
(12, 6, 5, 'Great streetwear hoodie! Love the design.'),
(12, 7, 4, 'Nice hoodie, good streetwear style. Quality is decent.'),
(12, 5, 5, 'Excellent hoodie! Quality is outstanding.'),
(12, 6, 4, 'Good hoodie overall. Nice design and good quality.'),
(12, 7, 5, 'Love this hoodie! Great streetwear vibes.'),
(12, 5, 4, 'Nice hoodie, good quality. The design is cool.'),
(12, 6, 5, 'Perfect hoodie! Quality is excellent.'),
(12, 7, 4, 'Good hoodie. Quality is nice and it looks great.'),
(12, 5, 5, 'Excellent quality! Great streetwear design.'),
(12, 6, 4, 'Nice hoodie overall. Good quality and stylish.'),

-- ZedZee Streetwear Tee (Product 13) - Additional 10 reviews
(13, 7, 5, 'Awesome graphic tee! Love the design.'),
(13, 5, 4, 'Nice tee, cool graphics. Quality is good.'),
(13, 6, 5, 'Excellent tee! Great graphics and quality.'),
(13, 7, 4, 'Good tee overall. Nice graphics and decent quality.'),
(13, 5, 5, 'Perfect streetwear tee! Quality is excellent.'),
(13, 6, 4, 'Nice tee, good quality. The graphics are cool.'),
(13, 7, 5, 'Love this tee! Great design and quality.'),
(13, 5, 4, 'Good tee. Quality is nice and graphics look great.'),
(13, 6, 5, 'Excellent quality! Awesome streetwear design.'),
(13, 7, 4, 'Nice tee overall. Good quality and cool graphics.'),

-- ZedZee Puffer Jacket (Product 14) - Additional 10 reviews
(14, 5, 5, 'Very warm jacket! Perfect for winter.'),
(14, 6, 4, 'Nice jacket, good quality. Very warm and comfortable.'),
(14, 7, 5, 'Excellent jacket! Quality is outstanding.'),
(14, 5, 4, 'Good jacket overall. Warm and stylish.'),
(14, 6, 5, 'Love this jacket! It\'s so warm and comfortable.'),
(14, 7, 4, 'Nice jacket, good quality. Perfect for cold weather.'),
(14, 5, 5, 'Perfect winter jacket! Quality is excellent.'),
(14, 6, 4, 'Good jacket. Quality is nice and it\'s very warm.'),
(14, 7, 5, 'Excellent quality! Very warm and stylish.'),
(14, 5, 4, 'Nice jacket overall. Good quality and warm.'),

-- Spiral Knitwear Top (Product 15) - Additional 10 reviews
(15, 6, 5, 'Beautiful handmade design! Unique and well-crafted.'),
(15, 7, 4, 'Nice knit top, good quality. The design is unique.'),
(15, 5, 5, 'Excellent quality! Beautiful handmade knit.'),
(15, 6, 4, 'Good top overall. Quality is nice and design is unique.'),
(15, 7, 5, 'Love this top! The handmade design is beautiful.'),
(15, 5, 4, 'Nice top, good quality. Unique knit design.'),
(15, 6, 5, 'Perfect top! Quality is excellent.'),
(15, 7, 4, 'Good top. Quality is decent and design is nice.'),
(15, 5, 5, 'Excellent quality! Beautiful handmade knit design.'),
(15, 6, 4, 'Nice top overall. Good quality and unique design.'),

-- Spiral Hoodie Black (Product 16) - Additional 10 reviews
(16, 7, 5, 'Classic black hoodie! Great quality and perfect fit.'),
(16, 5, 4, 'Nice hoodie, good quality. The black color is rich.'),
(16, 6, 5, 'Excellent hoodie! Quality is outstanding.'),
(16, 7, 4, 'Good hoodie overall. Quality is nice and it looks great.'),
(16, 5, 5, 'Perfect hoodie! Classic design and excellent quality.'),
(16, 6, 4, 'Nice hoodie, good quality. The black color doesn\'t fade.'),
(16, 7, 5, 'Love this hoodie! Quality is excellent.'),
(16, 5, 4, 'Good hoodie. Quality is decent and it\'s comfortable.'),
(16, 6, 5, 'Excellent quality! Classic black hoodie.'),
(16, 7, 4, 'Nice hoodie overall. Good quality and comfortable.'),

-- Asili Tribal Tee (Product 17) - Additional 10 reviews
(17, 5, 5, 'Beautiful tribal design! Unique and well-made.'),
(17, 6, 4, 'Nice tee, good quality. The tribal patterns are interesting.'),
(17, 7, 5, 'Excellent quality! Beautiful ethnic design.'),
(17, 5, 4, 'Good tee overall. Quality is nice and design is unique.'),
(17, 6, 5, 'Love this tee! The tribal patterns are beautiful.'),
(17, 7, 4, 'Nice tee, good quality. Unique tribal design.'),
(17, 5, 5, 'Perfect tee! Quality is excellent.'),
(17, 6, 4, 'Good tee. Quality is decent and design is nice.'),
(17, 7, 5, 'Excellent quality! Beautiful tribal patterns.'),
(17, 5, 4, 'Nice tee overall. Good quality and unique design.'),

-- Asili Tribal Hoodie (Product 18) - Additional 10 reviews
(18, 6, 5, 'Love the cultural design! Beautiful tribal patterns.'),
(18, 7, 4, 'Nice hoodie, good quality. The design is interesting.'),
(18, 5, 5, 'Excellent quality! Beautiful cultural design.'),
(18, 6, 4, 'Good hoodie overall. Quality is nice and design is unique.'),
(18, 7, 5, 'Perfect hoodie! Quality is excellent.'),
(18, 5, 4, 'Nice hoodie, good quality. Unique tribal patterns.'),
(18, 6, 5, 'Love this hoodie! The cultural design is beautiful.'),
(18, 7, 4, 'Good hoodie. Quality is decent and design is nice.'),
(18, 5, 5, 'Excellent quality! Beautiful tribal patterns.'),
(18, 6, 4, 'Nice hoodie overall. Good quality and unique design.'),

-- Mavin Zip Jacket (Product 19) - Additional 10 reviews
(19, 7, 5, 'Sleek and modern! Great quality and perfect fit.'),
(19, 5, 4, 'Nice jacket, good quality. Modern design.'),
(19, 6, 5, 'Excellent jacket! Quality is outstanding.'),
(19, 7, 4, 'Good jacket overall. Quality is nice and it looks great.'),
(19, 5, 5, 'Perfect jacket! Sleek design and excellent quality.'),
(19, 6, 4, 'Nice jacket, good quality. Modern and stylish.'),
(19, 7, 5, 'Love this jacket! Quality is excellent.'),
(19, 5, 4, 'Good jacket. Quality is decent and it\'s comfortable.'),
(19, 6, 5, 'Excellent quality! Sleek and modern design.'),
(19, 7, 4, 'Nice jacket overall. Good quality and stylish.'),

-- Mavin Crewneck Beige (Product 20) - Additional 10 reviews
(20, 5, 5, 'Beautiful beige color! Soft and comfortable.'),
(20, 6, 4, 'Nice crewneck, good quality. The beige color is versatile.'),
(20, 7, 5, 'Excellent quality! Perfect fit and soft fabric.'),
(20, 5, 4, 'Good crewneck overall. Quality is nice.'),
(20, 6, 5, 'Love this crewneck! The beige color is perfect.'),
(20, 7, 4, 'Nice crewneck, good quality. Comfortable and stylish.'),
(20, 5, 5, 'Perfect crewneck! Quality is excellent.'),
(20, 6, 4, 'Good crewneck. Quality is decent and it looks great.'),
(20, 7, 5, 'Excellent quality! Beautiful beige color.'),
(20, 5, 4, 'Nice crewneck overall. Good quality and comfortable.');

-- Update product average ratings and total reviews count
UPDATE products 
SET 
    average_rating = (
        SELECT COALESCE(AVG(rating), 0) 
        FROM product_reviews 
        WHERE product_reviews.product_id = products.product_id
    ),
    total_reviews = (
        SELECT COUNT(*) 
        FROM product_reviews 
        WHERE product_reviews.product_id = products.product_id
    )
WHERE product_id IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20);

-- ========================================================
-- WISHLIST
-- ========================================================

INSERT INTO user_favorites (user_id, product_id)
VALUES
(5, 1),
(5, 7),
(6, 10);

-- ========================================================
-- CART ITEMS
-- ========================================================

INSERT INTO cart_items (user_id, product_id, size, quantity)
VALUES
(5, 1, 'L', 1),
(5, 10, 'M', 2);

-- ========================================================
-- ORDERS
-- ========================================================

INSERT INTO orders (user_id, order_number, address_id, payment_method_id, order_status, subtotal, shipping_fees, service_fees, discount_amount, total_amount)
VALUES
(5, 'ORD001', 1, 1, 'delivered', 1048, 40, 10, 0, 1098),
(6, 'ORD002', 2, 2, 'processing', 999, 40, 10, 0, 1049),  -- Fixed: subtotal updated to match product price
(7, 'ORD003', 2, NULL, 'pending', 349, 40, 10, 0, 399);

-- ========================================================
-- ORDER ITEMS
-- ========================================================

INSERT INTO order_items (order_id, product_id, product_name, size, quantity, unit_price, total_price)
VALUES
(1, 1, 'Harbaa Black Hoodie', 'L', 1, 699, 699),
(1, 10, 'TwentySeven Basic Tee', 'M', 2, 189, 378),
(2, 5, 'Cielo Denim Jacket', 'M', 1, 999, 999),  -- Fixed: was 699, should be 999
(3, 2, 'Harbaa Oversized Tee White', 'M', 1, 349, 349);

-- ========================================================
-- LOYALTY POINTS
-- ========================================================
-- Points are calculated: 1 point per 10 EGP spent (on subtotal)
-- Tier thresholds: Bronze (0-499), Silver (500-1499), Gold (1500-2999), Platinum (3000-4999), Diamond (5000+)
-- Order 1: subtotal 1048 EGP = 104 points (user 5)
-- Order 2: subtotal 999 EGP = 99 points (user 6)
-- Order 3: subtotal 349 EGP = 34 points (user 7)

INSERT INTO loyalty_points (user_id, current_balance, total_earned, total_redeemed, tier_status, points_to_next_tier)
VALUES
-- User 5: 104 points from order 1, Bronze tier, needs 396 more for Silver
(5, 104, 104, 0, 'Bronze', 396),
-- User 6: 99 points from order 2, Bronze tier, needs 401 more for Silver
(6, 99, 99, 0, 'Bronze', 401),
-- User 7: 34 points from order 3, Bronze tier, needs 466 more for Silver
(7, 34, 34, 0, 'Bronze', 466);

-- ========================================================
-- LOYALTY TRANSACTIONS
-- ========================================================
-- Transactions linked to orders with proper point calculations

INSERT INTO loyalty_transactions (loyalty_id, user_id, transaction_type, points, description, order_id)
VALUES
-- User 5: Order 1 (subtotal 1048 EGP = 104 points)
(1, 5, 'earned', 104, 'Order purchase - 1,048.00 EGP', 1),
-- User 6: Order 2 (subtotal 999 EGP = 99 points)
(2, 6, 'earned', 99, 'Order purchase - 999.00 EGP', 2),
-- User 7: Order 3 (subtotal 349 EGP = 34 points)
(3, 7, 'earned', 34, 'Order purchase - 349.00 EGP', 3);

-- ========================================================
-- DISCOUNT CAMPAIGNS + PRODUCTS
-- ========================================================

INSERT INTO discount_campaigns (brand_owner_user_id, brand_id, campaign_name, discount_type, discount_value, start_date, end_date, status)
VALUES
(1, 1, 'Harbaa Winter Sale', 'percentage', 20, '2025-01-01', '2025-01-20', 'active'),
(2, 4, 'Cielo Denim Week', 'fixed', 100, '2025-01-10', '2025-01-30', 'scheduled');

INSERT INTO discount_campaign_products (campaign_id, product_id)
VALUES
(1, 1),
(1, 4),
(2, 5);

-- ========================================================
-- BRAND ANALYTICS
-- ========================================================

INSERT INTO brand_analytics (brand_id, metric, value)
VALUES
(1, 'monthly_revenue', 54500),
(1, 'orders_count', 110),
(4, 'monthly_revenue', 42000),
(5, 'customer_growth', 18.5);

-- ========================================================
-- PROMO CODES
-- ========================================================

INSERT INTO promo_codes (code, type, value, min_order_amount, max_discount, start_date, end_date, usage_limit, created_by)
VALUES
-- 10% off with no restrictions
('WELCOME10', 'percent', 10.00, 0.00, NULL, NULL, NULL, NULL, NULL),

-- 15% off with minimum order of 500 EGP, max discount 200 EGP
('SAVE15', 'percent', 15.00, 500.00, 200.00, NULL, NULL, NULL, NULL),

-- Fixed 50 EGP off
('FLAT50', 'fixed', 50.00, 0.00, NULL, NULL, NULL, NULL, NULL),

-- 20% off with minimum order of 1000 EGP
('BIG20', 'percent', 20.00, 1000.00, NULL, NULL, NULL, NULL, NULL),

-- Fixed 100 EGP off with minimum order of 800 EGP
('MEGA100', 'fixed', 100.00, 800.00, NULL, NULL, NULL, NULL, NULL),

-- 25% off with usage limit of 50 times
('SPECIAL25', 'percent', 25.00, 0.00, 300.00, NULL, NULL, 50, NULL),

-- 5% off for small orders (no minimum)
('SMALL5', 'percent', 5.00, 0.00, NULL, NULL, NULL, NULL, NULL),

-- Fixed 30 EGP off
('SAVE30', 'fixed', 30.00, 0.00, NULL, NULL, NULL, NULL, NULL),

-- 12% off with minimum order of 300 EGP
('DISCOUNT12', 'percent', 12.00, 300.00, 150.00, NULL, NULL, NULL, NULL),

-- Fixed 75 EGP off with minimum order of 600 EGP
('SUPER75', 'fixed', 75.00, 600.00, NULL, NULL, NULL, NULL, NULL);