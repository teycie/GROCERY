<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateProductsCategoryCheck extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $categories = [
            'Frozen',
            'Beverage',
            'Snacks',
            'Fruits & Vegetables',
            'Pet Care',
            'Household Cleaning & Essentials',
        ];

        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            $quoted = array_map(function ($value) {
                return "'" . str_replace("'", "''", $value) . "'";
            }, $categories);

            $checkList = implode(', ', $quoted);

            Schema::disableForeignKeyConstraints();

            DB::statement('DROP TABLE IF EXISTS products_tmp');

            DB::statement("CREATE TABLE products_tmp (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                name VARCHAR NOT NULL,
                description TEXT NULL,
                price NUMERIC NOT NULL,
                stock INTEGER NOT NULL DEFAULT 0,
                category VARCHAR NOT NULL CHECK (category IN ($checkList)),
                image VARCHAR NULL,
                user_id INTEGER NOT NULL,
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )");

            DB::statement("INSERT INTO products_tmp (id, name, description, price, stock, category, image, user_id, created_at, updated_at)
                SELECT
                    id,
                    name,
                    description,
                    price,
                    stock,
                    CASE
                        WHEN category = 'Groceries' THEN 'Snacks'
                        WHEN category = 'Wines & Liquor' THEN 'Beverage'
                        ELSE category
                    END,
                    image,
                    user_id,
                    created_at,
                    updated_at
                FROM products");

            Schema::drop('products');
            DB::statement('ALTER TABLE products_tmp RENAME TO products');

            Schema::enableForeignKeyConstraints();

            return;
        }

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE products MODIFY category ENUM('Frozen', 'Beverage', 'Snacks', 'Fruits & Vegetables', 'Pet Care', 'Household Cleaning & Essentials') NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Intentionally left empty to avoid destructive category rollback on existing data.
    }
}
