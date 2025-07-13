<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    public function switch($locale)
    {
        // چک می‌کنیم که زبان در لیست زبان‌های مجاز ما باشد
        if (in_array($locale, ['en', 'fa'])) {
            // زبان انتخاب شده را در سشن کاربر ذخیره می‌کنیم
            session()->put('locale', $locale);
        }

        // کاربر را به صفحه قبلی بازمی‌گردانیم
        return redirect()->back();
    }
}