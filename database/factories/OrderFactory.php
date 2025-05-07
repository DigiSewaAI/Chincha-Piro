use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = \App\Models\Order::class;

    public function definition()
    {
        return [
            'dish_id' => rand(1, 10), // 1 देखि 10 सम्मको र्यान्डम डिश ID
            'quantity' => rand(1, 5),
            'total_price' => rand(100, 500),
            'customer_name' => $this->faker->name(),
            'phone' => '98' . rand(10000000, 99999999),
            'address' => $this->faker->address(),
            'status' => 'pending',
        ];
    }
}
