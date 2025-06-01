<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\KonsultasiService::class, function ($app) {
            return new \App\Services\KonsultasiService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Atur Carbon ke locale Indonesia
        Carbon::setLocale('id');
        
        // Tambahkan translator Carbon untuk bulan bahasa Indonesia
        // Gunakan format tanggal standar (Y-m-d) untuk operasi di backend
        
        // Cara alternatif untuk menerjemahkan bulan dalam Carbon
        // Pastikan Laravel sudah menyertakan lang/id.json atau resources/lang/id.json
        
        // Hari dalam bahasa Indonesia
        Carbon::setWeekendDays([
            Carbon::SATURDAY,
            Carbon::SUNDAY,
        ]);
        
        Carbon::macro('translatedFormat', function ($format = '') {
            $carbon = $this;
            $translations = [
                'January' => 'Januari',
                'February' => 'Februari',
                'March' => 'Maret',
                'April' => 'April',
                'May' => 'Mei',
                'June' => 'Juni',
                'July' => 'Juli',
                'August' => 'Agustus',
                'September' => 'September',
                'October' => 'Oktober',
                'November' => 'November',
                'December' => 'Desember',
                'Jan' => 'Jan',
                'Feb' => 'Feb',
                'Mar' => 'Mar',
                'Apr' => 'Apr',
                'May_Short' => 'Mei',
                'Jun' => 'Jun',
                'Jul' => 'Jul',
                'Aug' => 'Agt',
                'Sep' => 'Sep',
                'Oct' => 'Okt',
                'Nov' => 'Nov',
                'Dec' => 'Des',
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu',
                'Sunday' => 'Minggu',
                'Mon' => 'Sen',
                'Tue' => 'Sel',
                'Wed' => 'Rab',
                'Thu' => 'Kam',
                'Fri' => 'Jum',
                'Sat' => 'Sab',
                'Sun' => 'Min',
            ];
            
            $formattedDate = $carbon->format($format);
            
            foreach ($translations as $english => $indonesian) {
                $formattedDate = str_replace($english, $indonesian, $formattedDate);
            }
            
            // Penanganan khusus untuk 'May' singkat (karena kita menggunakan 'May_Short' sebagai kunci)
            $formattedDate = str_replace('May', 'Mei', $formattedDate);
            
            return $formattedDate;
        });
    }
}
