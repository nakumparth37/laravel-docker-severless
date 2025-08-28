<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class UpdateOrderStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the status of all orders every minute';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = Order::all();
        foreach ($orders as $order) {
            if (in_array($order->status, ['Cancelled', 'Delivered'])) {
                continue;
            }
            $current_status = $order->status;
            switch ($order->status) {
                case 'Pending':
                    $order->status = 'Inprogress';
                    break;
                case 'Inprogress':
                    $order->status = 'shipped';
                    break;
                case 'shipped':
                    $order->status = 'Out for Delivery';
                    break;
                case 'Out for Delivery':
                    $order->status = 'Delivered';
                    break;
                case 'Delivered':
                    break;
                case 'Cancelled':
                    break;
                default:
                    $order->status = 'Pending';
                    break;
            }
            \Log::info("Orders:update-status executed at: " . now());
            \Log::info("Update Order $order->id and change the status from \"$current_status\" to \"$order->status\"");
            $order->save();
        }
        $this->info('Order statuses updated successfully!');

    }
}
