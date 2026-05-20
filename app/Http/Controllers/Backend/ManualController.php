<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class ManualController extends Controller
{
    public function index(Request $request)
    {
        $manuals = $this->manuals();
        $currentKey = $request->query('doc', 'quick-start');

        if (! array_key_exists($currentKey, $manuals)) {
            $currentKey = 'quick-start';
        }

        $current = $manuals[$currentKey];
        $path = base_path($current['path']);
        $markdown = File::exists($path)
            ? File::get($path)
            : "# ไม่พบคู่มือ\n\nไม่พบไฟล์ต้นฉบับของคู่มือนี้";

        return view('backend.manuals.index', [
            'manuals' => $manuals,
            'currentKey' => $currentKey,
            'current' => $current,
            'content' => $this->renderMarkdown($markdown),
        ]);
    }

    private function manuals(): array
    {
        return [
            'quick-start' => [
                'title' => 'คู่มือเริ่มใช้งานเร็ว',
                'description' => 'ขั้นตอนเปิดระบบ เข้าสู่ระบบเดโม และทดลองขายบิลแรก',
                'path' => 'docs/th/quick-start.md',
            ],
            'owner-manual' => [
                'title' => 'คู่มือเจ้าของร้าน',
                'description' => 'แนวทางดูยอดขาย ตรวจสต็อก คุมราคา และดูแลหน้าร้าน',
                'path' => 'docs/th/owner-manual.md',
            ],
            'admin-manual' => [
                'title' => 'คู่มือผู้ดูแลระบบ',
                'description' => 'การตั้งค่าร้าน ผู้ใช้ สิทธิ์ สินค้า รายงาน และคำสั่งดูแลระบบ',
                'path' => 'docs/th/admin-manual.md',
            ],
            'user-manual' => [
                'title' => 'คู่มือพนักงาน',
                'description' => 'ขั้นตอนใช้งานขายหน้าร้าน จัดการลูกค้า และออกใบเสร็จ',
                'path' => 'docs/th/user-manual.md',
            ],
            'system-doc' => [
                'title' => 'เอกสารระบบ',
                'description' => 'ภาพรวมระบบ โครงสร้างไฟล์ โมดูลหลัก และแนวทางพัฒนาต่อ',
                'path' => 'system doc.md',
            ],
        ];
    }

    private function renderMarkdown(string $markdown): HtmlString
    {
        if (method_exists(Str::class, 'markdown')) {
            $html = Str::markdown($markdown);

            return $html instanceof HtmlString ? $html : new HtmlString((string) $html);
        }

        return new HtmlString('<pre class="manual-fallback">' . e($markdown) . '</pre>');
    }
}
