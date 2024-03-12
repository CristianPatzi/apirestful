<?php

namespace Database\Factories;
require_once 'vendor/autoload.php';
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Seller;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
//USER-------------------------------------------------
class UserFactory extends Factory

{
    
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'name' => fake()->name(),
            'email' => fake() ->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'verified' => $verificado = fake() -> randomElement([User::USUARIO_VERIFICADO, User::USUARIO_NO_VERIFICADO]),
            'verification_token' => $verificado == User::USUARIO_VERIFICADO ? null : User::generarVerificationToken(),
            'admin' => fake() -> randomElement([User::USUARIO_ADMINISTRADOR, User::USUARIO_REGULAR]),
        ];
    }


}

//CATEGORY-------------------------------------------------
class CategoryFactory extends Factory

{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word,
            'description' => fake() -> paragraph(1),
            
        ];
    }

}

//PRODUCT-------------------------------------------------
class ProductFactory extends Factory

{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word,
            'description' => fake() -> paragraph(1),
            'quantity' => fake() -> numberBetween(1,10),
            'status' => fake() -> randomElement([Product::PRODUCTO_DISPONIBLE, Product::PRODUCTO_NO_DISPONIBLE]),
            'image' => fake() -> randomElement(['1.jpg', '2.jpg', '3.jpg']),
            //'seller_id' => User::inRandomOrder()->first()->id,
            'seller_id' => User::all()->random()->id,
        ];
    }

}

//TRANSACTION-------------------------------------------------
class TransactionFactory extends Factory

{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $vendedor = Seller::has('products')->get()->random();
        $comprador = User::all()->except($vendedor->id)->random();
        return [
            'quantity' => fake() -> numberBetween(1,3),
            'buyer_id' => $comprador->id,
            'product_id' => $vendedor->products->random()->id,
        ];
    }

}