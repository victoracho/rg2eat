<?php

namespace App\Http\Controllers;

use App\Models\BusinessHour;
use App\Models\MenuCategory;
use App\Support\Site;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PublicSiteController extends Controller
{
    public function home(Request $request)
    {
        $lang = Site::lang();
        $categories = MenuCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->with(['activeItems'])
            ->get();
        $hoursByDay = BusinessHour::groupedByDay();

        return view('public.home', [
            'lang'        => $lang,
            'categories'  => $categories,
            'hoursByDay'  => $hoursByDay,
            'isOpenNow'   => Site::isOpenNow(),
            'todaySummary'=> Site::todaySummary($lang),
        ]);
    }

    public function menu(Request $request)
    {
        $lang = Site::lang();
        $categories = MenuCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->with(['activeItems'])
            ->get();

        return view('public.menu', [
            'lang'         => $lang,
            'categories'   => $categories,
            'isOpenNow'    => Site::isOpenNow(),
            'todaySummary' => Site::todaySummary($lang),
        ]);
    }

    /**
     * Render a PNG QR pointing to the public /menu.
     * Falls back to a remote QR generator if endroid/qr-code is not installed.
     */
    public function menuQr(Request $request)
    {
        $url = route('menu');

        if (class_exists(\chillerlan\QRCode\QRCode::class)) {
            $options = new \chillerlan\QRCode\QROptions([
                'version'         => 5,
                'outputInterface' => \chillerlan\QRCode\Output\QRGdImagePNG::class,
                'eccLevel'        => \chillerlan\QRCode\Common\EccLevel::H,
                'scale'           => 8,
                'outputBase64'    => false,
            ]);
            $png = (new \chillerlan\QRCode\QRCode($options))->render($url);
            return new Response($png, 200, [
                'Content-Type'  => 'image/png',
                'Cache-Control' => 'public, max-age=300',
            ]);
        }

        return redirect()->away(
            'https://api.qrserver.com/v1/create-qr-code/?size=360x360&data=' . urlencode($url)
        );
    }
}
