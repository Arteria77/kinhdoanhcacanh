<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
<<<<<<< HEAD
        \Illuminate\Auth\Notifications\VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('Xác Thực Tài Khoản - Fashu Shop Cá Cảnh')
                ->greeting('Xin chào ' . $notifiable->name . '!')
                ->line('Cảm ơn bạn đã đăng ký tài khoản tại Fashu - Shop Cá Cảnh & Thủy Sinh.')
                ->line('Vui lòng nhấn vào nút bên dưới để xác thực địa chỉ email và kích hoạt tài khoản:')
                ->action('Xác Thực Email Ngay', $url)
                ->line('Sau khi xác thực, bạn sẽ có thể thoải mái thêm sản phẩm vào giỏ hàng và đặt hàng.')
                ->line('Liên kết này có hiệu lực trong vòng 60 phút. Nếu bạn không tạo tài khoản, xin vui lòng bỏ qua thư này.')
                ->salutation('Trân trọng, Đội ngũ Fashu Aqua');
        });
=======
        //
>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
    }
}
